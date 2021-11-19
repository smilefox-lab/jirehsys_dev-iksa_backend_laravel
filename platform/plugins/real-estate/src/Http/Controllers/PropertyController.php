<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\PropertyForm;
use Botble\RealEstate\Http\Requests\PropertyRequest;
use Botble\RealEstate\IksaMedia;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Tables\PropertyTable;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class PropertyController extends BaseController
{
    /**
     * @var PropertyInterface $propertyRepository
     */
    protected $propertyRepository;

    /**
     * @var FeatureInterface
     */
    protected $featureRepository;

    /**
     * PropertyController constructor.
     * @param PropertyInterface $propertyRepository
     * @param FeatureInterface $featureRepository
     */
    public function __construct(
        PropertyInterface $propertyRepository,
        FeatureInterface $featureRepository
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->featureRepository = $featureRepository;
    }

    /**
     * @param PropertyTable $dataTable
     * @return JsonResponse|View
     * @throws Throwable
     */
    public function index(PropertyTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/real-estate::property.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::property.create'));

        return $formBuilder->create(PropertyForm::class)->renderForm();
    }

    /**
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     */
    public function store(PropertyRequest $request, BaseHttpResponse $response)
    {
        try {
            $property = $this->propertyRepository->getModel();

            $property = $property->fill($request->except(['image', 'images', 'technical', 'technicals', 'legal', 'legals', 'plane', 'planes']));

            $property->save();

            $images = [];
            $technicals = [];
            $legals = [];
            $planes = [];

            if ($request->hasFile('image')) {
                array_push($images, ...IksaMedia::handleImage($request->file('image'), $property->id));
            }

            if ($request->hasFile('technical')) {
                array_push($technicals, ...IksaMedia::handleFile($request->file('technical'), 'properties', "{$property->id}/technicals"));
            }

            if ($request->hasFile('legal')) {
                array_push($legals, ...IksaMedia::handleFile($request->file('legal'), 'properties', "{$property->id}/legals"));
            }

            if ($request->hasFile('plane')) {
                array_push($planes, ...IksaMedia::handleFile($request->file('plane'), 'properties', "{$property->id}/planes"));
            }

            $property->images = json_encode($images);
            $property->files_technical = json_encode($technicals);
            $property->files_legal = json_encode($legals);
            $property->files_plane = json_encode($planes);

            $property->save();

        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }

        event(new CreatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        if ($property) {
            $property->features()->sync($request->input('features', []));
        }

        return $response
            ->setPreviousUrl(route('property.index'))
            ->setNextUrl(route('property.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, Request $request, FormBuilder $formBuilder)
    {

        $property = $this->propertyRepository->findOrFail($id, ['features', 'author']);
        page_title()->setTitle(trans('plugins/real-estate::property.edit') . ' "' . $property->name . '"');

        event(new BeforeEditContentEvent($request, $property));

        return $formBuilder->create(PropertyForm::class, ['model' => $property])->renderForm();
    }

    /**
     * @param int $id
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws FileNotFoundException
     */
    public function update($id, PropertyRequest $request, BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->findOrFail($id);
        $property->fill($request->except(['image', 'images', 'technical', 'technicals', 'legal', 'legals', 'plane', 'planes']));



        $images = [];
        $technicals = [];
        $legals = [];
        $planes = [];

        if (!empty($request->images)) {
            array_push($images, ...$request->images);
        }

        if (!empty($request->technicals)) {
            array_push($technicals, ...$request->technicals);
        }

        if (!empty($request->legals)) {
            array_push($legals, ...$request->legals);
        }

        if (!empty($request->planes)) {
            array_push($planes, ...$request->planes);
        }

        try {
            if ($request->hasFile('image')) {
                array_push($images, ...IksaMedia::handleImage($request->file('image'), $property->id));
            }

            if ($request->hasFile('technical')) {
                array_push($technicals, ...IksaMedia::handleFile($request->file('technical'), 'properties', "{$property->id}/technicals"));
            }

            if ($request->hasFile('legal')) {
                array_push($legals, ...IksaMedia::handleFile($request->file('legal'), 'properties', "{$property->id}/legals"));
            }

            if ($request->hasFile('plane')) {
                array_push($planes, ...IksaMedia::handleFile($request->file('plane'), 'properties', "{$property->id}/planes"));
            }

            $property->images = json_encode($images);
            $property->files_technical = json_encode($technicals);
            $property->files_legal = json_encode($legals);
            $property->files_plane = json_encode($planes);

            $this->propertyRepository->createOrUpdate($property);

        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }


        event(new UpdatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $property->features()->sync($request->input('features', []));

        return $response
            ->setPreviousUrl(route('property.index'))
            ->setNextUrl(route('property.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy($id, BaseHttpResponse $response)
    {
        try {
            $property = $this->propertyRepository->findOrFail($id);
            $property->features()->detach();
            $this->propertyRepository->delete($property);

            event(new DeletedContentEvent(PROPERTY_MODULE_SCREEN_NAME, request(), $property));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $property = $this->propertyRepository->findOrFail($id);
            $property->features()->detach();
            $this->propertyRepository->delete($property);

            event(new DeletedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

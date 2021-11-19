<?php

namespace Botble\Location\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Location\Forms\RegionForm;
use Botble\Location\Http\Requests\RegionRequest;
use Botble\Location\Http\Resources\RegionResource;
use Botble\Location\Repositories\Interfaces\RegionInterface;
use Botble\Location\Tables\RegionTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Location;
use Throwable;

class RegionController extends BaseController
{
    /**
     * @var RegionInterface
     */
    protected $regionRepository;

    /**
     * RegionController constructor.
     * @param RegionInterface $regionRepository
     */
    public function __construct(RegionInterface $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @param RegionTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(RegionTable $table)
    {

        page_title()->setTitle(trans('plugins/location::region.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/location::region.create'));

        return $formBuilder->create(RegionForm::class)->renderForm();
    }

    /**
     * @param RegionRequest $request
     * @return BaseHttpResponse
     */
    public function store(RegionRequest $request, BaseHttpResponse $response)
    {
        $region = $this->regionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));

        return $response
            ->setPreviousUrl(route('region.index'))
            ->setNextUrl(route('region.edit', $region->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $region = $this->regionRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $region));

        page_title()->setTitle(trans('plugins/location::region.edit') . ' "' . $region->name . '"');

        return $formBuilder->create(RegionForm::class, ['model' => $region])->renderForm();
    }

    /**
     * @param $id
     * @param RegionRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, RegionRequest $request, BaseHttpResponse $response)
    {
        $region = $this->regionRepository->findOrFail($id);

        $region->fill($request->input());

        $this->regionRepository->createOrUpdate($region);

        event(new UpdatedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));

        return $response
            ->setPreviousUrl(route('region.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $region = $this->regionRepository->findOrFail($id);

            $this->regionRepository->delete($region);

            event(new DeletedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
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
            $region = $this->regionRepository->findOrFail($id);
            $this->regionRepository->delete($region);
            event(new DeletedContentEvent(REGION_MODULE_SCREEN_NAME, $request, $region));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     */
    public function getList(Request $request, BaseHttpResponse $response)
    {
        $keyword = $request->input('q');

        if (!$keyword) {
            return $response->setData([]);
        }

        $data = $this->regionRepository->advancedGet([
            'condition' => [
                ['regions.name', 'LIKE', '%' . $keyword . '%'],
            ],
            'select'    => ['regions.id', 'regions.name'],
            'take'      => 10,

        ]);

        return $response->setData(RegionResource::collection($data));
    }

    /**
     * @param BaseHttpResponse $response
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getArrayRegions(BaseHttpResponse $response)
    {
        return $response->setData(Location::getRegions());
    }
}

<?php

namespace Botble\Vendor\Http\Controllers;

use Assets;
use Auth;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Vendor\Forms\PropertyForm;
use Botble\Vendor\Http\Requests\PropertyRequest;
use Botble\Vendor\Models\Vendor;
use Botble\Vendor\Repositories\Interfaces\VendorActivityLogInterface;
use Botble\Vendor\Repositories\Interfaces\VendorInterface;
use Botble\Vendor\Tables\PropertyTable;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SeoHelper;

class PropertyController extends Controller
{
    /**
     * @var VendorInterface
     */
    protected $vendorRepository;

    /**
     * @var PropertyInterface
     */
    protected $propertyRepository;

    /**
     * @var VendorActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * PublicController constructor.
     * @param Repository $config
     * @param VendorInterface $vendorRepository
     * @param PropertyInterface $propertyRepository
     * @param VendorActivityLogInterface $vendorActivityLogRepository
     */
    public function __construct(
        Repository $config,
        VendorInterface $vendorRepository,
        PropertyInterface $propertyRepository,
        VendorActivityLogInterface $vendorActivityLogRepository
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->propertyRepository = $propertyRepository;
        $this->activityLogRepository = $vendorActivityLogRepository;

        Assets::setConfig($config->get('plugins.vendor.assets'));
    }

    /**
     * @param Request $request
     * @param PropertyTable $propertyTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View|\Response
     * @throws \Throwable
     */
    public function index(PropertyTable $propertyTable)
    {
        SeoHelper::setTitle(__('Properties'));

        return $propertyTable->render('plugins/vendor::table.base');
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @throws \Throwable
     */
    public function create(FormBuilder $formBuilder)
    {
        if (!Auth::guard('vendor')->user()->canPost()) {
            abort(403);
        }

        SeoHelper::setTitle(__('Write a property'));

        return $formBuilder->create(PropertyForm::class)->renderForm();
    }

    /**
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @param VendorInterface $vendorRepository
     * @return BaseHttpResponse
     */
    public function store(PropertyRequest $request, BaseHttpResponse $response, VendorInterface $vendorRepository)
    {
        if (!Auth::guard('vendor')->user()->canPost()) {
            abort(403);
        }

        $request->merge(['expire_date' => now()->addDays(45)]);

        /**
         * @var Property $property
         */
        $property = $this->propertyRepository->createOrUpdate(array_merge($request->input(), [
            'author_id'   => auth()->guard('vendor')->user()->getKey(),
            'author_type' => Vendor::class,
        ]));

        if ($property) {
            $property->features()->sync($request->input('features', []));
        }

        event(new CreatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'create_property',
            'reference_name' => $property->name,
            'reference_url'  => route('public.vendor.properties.edit', $property->id),
        ]);

        $vendor = $vendorRepository->findOrFail(auth()->guard('vendor')->user()->getKey());
        $vendor->credits--;
        $vendor->save();

        return $response
            ->setPreviousUrl(route('public.vendor.properties.index'))
            ->setNextUrl(route('public.vendor.properties.edit', $property->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     *
     * @throws \Throwable
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth()->guard('vendor')->user()->getKey(),
            'author_type' => Vendor::class,
        ]);

        if (!$property) {
            abort(404);
        }

        event(new BeforeEditContentEvent($request, $property));

        SeoHelper::setTitle(trans('plugins/real-estate::property.edit') . ' "' . $property->name . '"');

        return $formBuilder
            ->create(PropertyForm::class, ['model' => $property])
            ->renderForm();
    }

    /**
     * @param int $id
     * @param PropertyRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function update($id, PropertyRequest $request, BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth()->guard('vendor')->user()->getKey(),
            'author_type' => Vendor::class,
        ]);

        if (!$property) {
            abort(404);
        }

        $property->fill($request->except(['expire_date']));

        $this->propertyRepository->createOrUpdate($property);

        $property->features()->sync($request->input('features', []));

        event(new UpdatedContentEvent(PROPERTY_MODULE_SCREEN_NAME, $request, $property));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'update_property',
            'reference_name' => $property->name,
            'reference_url'  => route('public.vendor.properties.edit', $property->id),
        ]);

        return $response
            ->setPreviousUrl(route('public.vendor.properties.index'))
            ->setNextUrl(route('public.vendor.properties.edit', $property->id))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function destroy($id, BaseHttpResponse $response)
    {
        $property = $this->propertyRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth()->guard('vendor')->user()->getKey(),
            'author_type' => Vendor::class,
        ]);

        if (!$property) {
            abort(404);
        }

        $this->propertyRepository->delete($property);

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'delete_property',
            'reference_name' => $property->name,
        ]);

        return $response->setMessage(__('Delete property successfully!'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function renew($id, BaseHttpResponse $response)
    {
        $job = $this->propertyRepository->findOrFail($id);

        $account = auth('vendor')->user();

        if ($account->credits < 1) {
            return $response->setError(true)->setMessage(__('You don\'t have enough credit to renew this property!'));
        }

        $job->expire_date = $job->expire_date->addDays(config('plugins.real-estate.real-estate.property_expired_after_x_days'));
        $job->save();

        $account->credits--;
        $account->save();

        return $response->setMessage(__('Renew property successfully'));
    }
}

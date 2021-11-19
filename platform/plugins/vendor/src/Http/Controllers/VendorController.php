<?php

namespace Botble\Vendor\Http\Controllers;

use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\Vendor\Forms\VendorForm;
use Botble\Vendor\Http\Resources\VendorResource;
use Botble\Vendor\Tables\VendorTable;
use Botble\Vendor\Http\Requests\VendorCreateRequest;
use Botble\Vendor\Http\Requests\VendorEditRequest;
use Botble\Vendor\Repositories\Interfaces\VendorInterface;
use Exception;
use Illuminate\Http\Request;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;

class VendorController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var VendorInterface
     */
    protected $vendorRepository;

    /**
     * @param VendorInterface $vendorRepository
     */
    public function __construct(VendorInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @param VendorTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(VendorTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/vendor::vendor.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/vendor::vendor.create'));

        return $formBuilder
            ->create(VendorForm::class)
            ->remove('is_change_password')
            ->renderForm();
    }

    /**
     * @param VendorCreateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(VendorCreateRequest $request, BaseHttpResponse $response)
    {
        $request->merge([
	    'password'     => bcrypt($request->input('password')),
            'confirmed_at' => now(),
	]);

        $vendor = $this->vendorRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(VENDOR_MODULE_SCREEN_NAME, $request, $vendor));

        return $response
            ->setPreviousUrl(route('vendor.index'))
            ->setNextUrl(route('vendor.edit', $vendor->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param FormBuilder $formBuilder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $vendor = $this->vendorRepository->findOrFail($id);
        page_title()->setTitle(trans('plugins/vendor::vendor.edit'));

        $vendor->password = null;

        return $formBuilder
            ->create(VendorForm::class, ['model' => $vendor])
            ->renderForm();
    }

    /**
     * @param $id
     * @param VendorEditRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, VendorEditRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_change_password') == 1) {
            $request->merge(['password' => bcrypt($request->input('password'))]);
            $data = $request->input();
        } else {
            $data = $request->except('password');
        }
        $vendor = $this->vendorRepository->createOrUpdate($data, ['id' => $id]);

        event(new UpdatedContentEvent(VENDOR_MODULE_SCREEN_NAME, $request, $vendor));

        return $response
            ->setPreviousUrl(route('vendor.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $vendor = $this->vendorRepository->findOrFail($id);
            $this->vendorRepository->delete($vendor);
            event(new DeletedContentEvent(VENDOR_MODULE_SCREEN_NAME, $request, $vendor));

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
        return $this->executeDeleteItems($request, $response, $this->vendorRepository, VENDOR_MODULE_SCREEN_NAME);
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

        $data = $this->vendorRepository->getModel()
            ->where('vendors.first_name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('vendors.last_name', 'LIKE', '%' . $keyword . '%')
            ->select(['vendors.id', 'vendors.first_name', 'vendors.last_name'])
            ->take(10)
            ->get();

        return $response->setData(VendorResource::collection($data));
    }
}

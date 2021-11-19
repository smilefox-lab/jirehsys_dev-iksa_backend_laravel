<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Forms\CompanyForm;
use Botble\ACL\Http\Requests\CompanyRequest;
use Botble\ACL\Repositories\Interfaces\CompanyInterface;
use Botble\ACL\Tables\CompanyTable;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\RealEstate\IksaMedia;

class CompanyController extends BaseController
{
    /**
     * @var CompanyInterface
     */
    protected $companyRepository;

    /**
     * CompanyController constructor.
     * @param CompanyInterface $companyRepository
     */
    public function __construct(CompanyInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Display all companies
     * @param CompanyTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(CompanyTable $table)
    {

        page_title()->setTitle(trans('core/acl::company.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('core/acl::company.create'));

        return $formBuilder->create(CompanyForm::class)->renderForm();
    }

    /**
     * Insert new Company into database
     *
     * @param CompanyRequest $request
     * @return BaseHttpResponse
     */
    public function store(CompanyRequest $request, BaseHttpResponse $response)
    {
        try {
            $files = [];

            $company = $this->companyRepository->createOrUpdate($request->except(['file', 'files']));

            if ($request->hasFile('file')) {
                array_push($files, ...IksaMedia::handleFile($request->file('file'), 'company', $company->id));
            }
            $company->files = json_encode($files);
            $company->save();

        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }

        event(new CreatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

        return $response
            ->setPreviousUrl(route('company.index'))
            ->setNextUrl(route('company.edit', $company->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $company = $this->companyRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $company));

        page_title()->setTitle(trans('core/acl::company.edit') . ' "' . $company->name . '"');

        return $formBuilder->create(CompanyForm::class, ['model' => $company])->renderForm();
    }

    /**
     * @param $id
     * @param CompanyRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, CompanyRequest $request, BaseHttpResponse $response)
    {
        $company = $this->companyRepository->findOrFail($id);

        $company->fill($request->except(['file', 'filesInput']));

        $files = [];

        if (!empty($request->filesInput)) {
            array_push($files, ...$request->filesInput);
        }

        try {
            if ($request->hasFile('file')) {
                array_push($files, ...IksaMedia::handleFile($request->file('file'), 'company', $company->id));
            }
            $company->files = json_encode($files);

        } catch (Exception $exception) {
            return [
                'error'   => true,
                'message' => $exception->getMessage(),
            ];
        }

        $this->companyRepository->createOrUpdate($company);

        event(new UpdatedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

        return $response
            ->setPreviousUrl(route('company.index'))
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
            $company = $this->companyRepository->findOrFail($id);

            $this->companyRepository->delete($company);

            event(new DeletedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));

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
            $company = $this->companyRepository->findOrFail($id);
            $this->companyRepository->delete($company);
            event(new DeletedContentEvent(COMPANY_MODULE_SCREEN_NAME, $request, $company));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

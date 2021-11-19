<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\RealEstate\Http\Requests\ContractRequest;
use Botble\RealEstate\Repositories\Interfaces\ContractInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Exception;
use Botble\RealEstate\Tables\ContractTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\ContractForm;
use Botble\Base\Forms\FormBuilder;
use Botble\RealEstate\Http\Resources\ContractResource;
use Botble\RealEstate\Models\Property;
use Illuminate\View\View;
use Throwable;

class ContractController extends BaseController
{
    /**
     * @var ContractInterface
     */
    protected $contractRepository;

    /**
     * ContractController constructor.
     * @param ContractInterface $contractRepository
     */
    public function __construct(ContractInterface $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    /**
     * @param ContractTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(ContractTable $table)
    {

        page_title()->setTitle(trans('plugins/real-estate::contract.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::contract.create'));

        return $formBuilder->create(ContractForm::class)->renderForm();
    }

    /**
     * Insert new Contract into database
     *
     * @param ContractRequest $request
     * @return BaseHttpResponse
     */
    public function store(ContractRequest $request, BaseHttpResponse $response)
    {
        $contract = $this->contractRepository->createOrUpdate($request->input());

        $property = $contract->property;

        $property->profitability = ($contract->income / $property->pesos) * 100;
        $property->save();

        event(new CreatedContentEvent(CONTRACT_MODULE_SCREEN_NAME, $request, $contract));

        return $response
            ->setPreviousUrl(route('contract.index'))
            ->setNextUrl(route('contract.edit', $contract->id))
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
        $contract = $this->contractRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $contract));

        page_title()->setTitle(trans('plugins/real-estate::contract.edit') . ' "' . $contract->name . '"');

        return $formBuilder->create(ContractForm::class, ['model' => $contract])->renderForm();
    }

    /**
     * @param $id
     * @param ContractRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, ContractRequest $request, BaseHttpResponse $response)
    {
        $contract = $this->contractRepository->findOrFail($id);

        $contract->fill($request->input());

        $this->contractRepository->createOrUpdate($contract);

        $property = $contract->property;

        $property->profitability = ($contract->income / $property->pesos) * 100;
        $property->save();

        event(new UpdatedContentEvent(CONTRACT_MODULE_SCREEN_NAME, $request, $contract));

        return $response
            ->setPreviousUrl(route('contract.index'))
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
            $contract = $this->contractRepository->findOrFail($id);

            $this->contractRepository->delete($contract);

            event(new DeletedContentEvent(CONTRACT_MODULE_SCREEN_NAME, $request, $contract));

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
            $contract = $this->contractRepository->findOrFail($id);
            $this->contractRepository->delete($contract);
            event(new DeletedContentEvent(CONTRACT_MODULE_SCREEN_NAME, $request, $contract));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getContratcsByProperty(Request $request, BaseHttpResponse $response, Property $property)
    {
        return $response->setData(ContractResource::collection($property->contracts));
    }
}

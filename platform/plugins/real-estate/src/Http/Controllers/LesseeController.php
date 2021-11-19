<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\RealEstate\Http\Requests\LesseeRequest;
use Botble\RealEstate\Repositories\Interfaces\LesseeInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Exception;
use Botble\RealEstate\Tables\LesseeTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\LesseeForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable;

class LesseeController extends BaseController
{
    /**
     * @var LesseeInterface
     */
    protected $lesseeRepository;

    /**
     * LesseeController constructor.
     * @param LesseeInterface $lesseeRepository
     */
    public function __construct(LesseeInterface $lesseeRepository)
    {
        $this->lesseeRepository = $lesseeRepository;
    }

    /**
     * @param LesseeTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(LesseeTable $table)
    {

        page_title()->setTitle(trans('plugins/real-estate::lessee.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::lessee.create'));

        return $formBuilder->create(LesseeForm::class)->renderForm();
    }

    /**
     * Insert new Lessee into database
     *
     * @param LesseeRequest $request
     * @return BaseHttpResponse
     */
    public function store(LesseeRequest $request, BaseHttpResponse $response)
    {
        $lessee = $this->lesseeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DESTINY_MODULE_SCREEN_NAME, $request, $lessee));

        return $response
            ->setPreviousUrl(route('lessee.index'))
            ->setNextUrl(route('lessee.edit', $lessee->id))
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
        $lessee = $this->lesseeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $lessee));

        page_title()->setTitle(trans('plugins/real-estate::lessee.edit') . ' "' . $lessee->name . '"');

        return $formBuilder->create(LesseeForm::class, ['model' => $lessee])->renderForm();
    }

    /**
     * @param $id
     * @param LesseeRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, LesseeRequest $request, BaseHttpResponse $response)
    {
        $lessee = $this->lesseeRepository->findOrFail($id);

        $lessee->fill($request->input());

        $this->lesseeRepository->createOrUpdate($lessee);

        event(new UpdatedContentEvent(DESTINY_MODULE_SCREEN_NAME, $request, $lessee));

        return $response
            ->setPreviousUrl(route('lessee.index'))
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
            $lessee = $this->lesseeRepository->findOrFail($id);

            $this->lesseeRepository->delete($lessee);

            event(new DeletedContentEvent(LESSEE_MODULE_SCREEN_NAME, $request, $lessee));

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
            $lessee = $this->lesseeRepository->findOrFail($id);
            $this->lesseeRepository->delete($lessee);
            event(new DeletedContentEvent(DESTINY_MODULE_SCREEN_NAME, $request, $lessee));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

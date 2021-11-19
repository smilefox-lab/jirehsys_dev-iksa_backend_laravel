<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\PaymentForm;
use Botble\RealEstate\Http\Requests\PaymentRequest;
use Botble\RealEstate\Repositories\Interfaces\PaymentInterface;
use Botble\RealEstate\Tables\PaymentTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class PaymentController extends BaseController
{
    /**
     * @var PaymentInterface
     */
    protected $paymentRepository;

    /**
     * PaymentController constructor.
     * @param PaymentInterface $paymentRepository
     */
    public function __construct(PaymentInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param PaymentTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(PaymentTable $table)
    {

        page_title()->setTitle(trans('plugins/real-estate::payment.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::payment.create'));

        return $formBuilder->create(PaymentForm::class)->renderForm();
    }

    /**
     * Insert new Payment into database
     *
     * @param PaymentRequest $request
     * @return BaseHttpResponse
     */
    public function store(PaymentRequest $request, BaseHttpResponse $response)
    {
        $payment = $this->paymentRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(PAYMENT_MODULE_SCREEN_NAME, $request, $payment));

        return $response
            ->setPreviousUrl(route('payment.index'))
            ->setNextUrl(route('payment.edit', $payment->id))
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
        $payment = $this->paymentRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $payment));

        page_title()->setTitle(trans('plugins/real-estate::payment.edit') . ' "' . $payment->name . '"');

        return $formBuilder->create(PaymentForm::class, ['model' => $payment])->renderForm();
    }

    /**
     * @param $id
     * @param PaymentRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, PaymentRequest $request, BaseHttpResponse $response)
    {
        $payment = $this->paymentRepository->findOrFail($id);

        $payment->fill($request->input());

        $this->paymentRepository->createOrUpdate($payment);

        event(new UpdatedContentEvent(PAYMENT_MODULE_SCREEN_NAME, $request, $payment));

        return $response
            ->setPreviousUrl(route('payment.index'))
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
            $payment = $this->paymentRepository->findOrFail($id);

            $this->paymentRepository->delete($payment);

            event(new DeletedContentEvent(LESSEE_MODULE_SCREEN_NAME, $request, $payment));

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
            $payment = $this->paymentRepository->findOrFail($id);
            $this->paymentRepository->delete($payment);
            event(new DeletedContentEvent(PAYMENT_MODULE_SCREEN_NAME, $request, $payment));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

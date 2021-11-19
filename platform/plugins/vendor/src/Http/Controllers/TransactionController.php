<?php

namespace Botble\Vendor\Http\Controllers;

use Auth;
use Botble\Vendor\Http\Requests\CreateTransactionRequest;
use Botble\Vendor\Repositories\Interfaces\VendorInterface;
use Botble\Vendor\Repositories\Interfaces\TransactionInterface;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;

class TransactionController extends BaseController
{
    /**
     * @var TransactionInterface
     */
    protected $transactionRepository;

    /**
     * @var VendorInterface
     */
    protected $accountRepository;

    /**
     * TransactionController constructor.
     * @param TransactionInterface $transactionRepository
     * @param VendorInterface $accountRepository
     */
    public function __construct(TransactionInterface $transactionRepository, VendorInterface $accountRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Insert new Transaction into database
     *
     * @param $id
     * @param CreateTransactionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postCreate($id, CreateTransactionRequest $request, BaseHttpResponse $response)
    {
        $account = $this->accountRepository->findOrFail($id);

        $request->merge([
            'user_id'    => Auth::user()->getKey(),
            'account_id' => $id,
        ]);

        $this->transactionRepository->createOrUpdate($request->input());

        $account->credits += $request->input('credits');
        $this->accountRepository->createOrUpdate($account);

        return $response
            ->setMessage(trans('core/base::notices.create_success_message'));
    }
}

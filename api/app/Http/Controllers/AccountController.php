<?php

namespace App\Http\Controllers;

use App\Repository\AccountRepositoryInterface;
use App\Repository\Eloquent\AccountRepository;
use App\Repository\Eloquent\TransactionRepository;
use App\Repository\TransactionRepositoryInterface;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * @var TransactionService
     */
    protected $transactionService;

    /**
     * AccountController constructor.
     * @param  AccountRepositoryInterface  $accountRepository
     * @param  TransactionRepositoryInterface  $transactionRepository
     * @param  TransactionService  $transactionService
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        TransactionRepositoryInterface $transactionRepository,
        TransactionService $transactionService
    ) {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
        $this->transactionService = $transactionService;
    }

    /**
     * @param  int  $id
     * @return array
     */
    public function show(int $id): array
    {
        return ['data' => $this->accountRepository->find($id)];
    }

    /**
     * @param  int  $id
     * @return array
     */
    public function getRelatedTransactions(int $id): array
    {
        return ['data' => $this->transactionRepository->findByAccount($id)];
    }

    /**
     * @param  Request  $request
     * @param  int  $id
     * @return array
     * @throws ValidationException
     * @throws \Exception
     */
    public function storeTransaction(Request $request, int $id): array
    {
        $data = array_merge(['from' => $id], $request->post());
        $this->transactionService->validateTransaction($id, $data);

        return [
            'data' => $this->transactionService->updateBalance($id, $data),
            'message' => 'Transaction has been created successfully.'
        ];
    }
}

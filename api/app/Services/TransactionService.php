<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repository\AccountRepositoryInterface;
use App\Repository\Eloquent\AccountRepository;
use App\Repository\Eloquent\TransactionRepository;
use App\Repository\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class TransactionService
 * @package App\Services
 */
class TransactionService
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
     * TransactionService constructor.
     * @param  AccountRepositoryInterface  $accountRepository
     * @param  TransactionRepositoryInterface  $transactionRepository
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param  int  $accountId
     * @param  array  $data
     * @throws ValidationException
     */
    public function validateTransaction(int $accountId, array $data)
    {
        $rules = [
            'to' => 'required|integer',
            'amount' => 'required|integer|min:1',
            'details' => 'required'
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ((int)$data['to'] === $accountId) {
            $validator->errors()->add('to', "You cannot make transaction with yourself");

            throw new ValidationException($validator);
        }

        $accountFrom = $this->accountRepository->find($accountId);

        if (!$accountFrom) {
            $validator
                ->errors()
                ->add(
                    'from',
                    "There is not account with such id( ${accountId})"
                );
        }

        $accountTo = $this->accountRepository->find($data['to']);

        if (!$accountTo) {
            $validator
                ->errors()
                ->add(
                    'to',
                    "There is not account with such id( ${$data['to']})"
                );
        }

        // TODO: add currency checking
        if ($accountFrom && $accountFrom->balance < $data['amount']) {
            $validator->errors()->add('from', "You don't have enough money");
        }

        if (count($validator->errors()->all())) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param  int  $accountId
     * @param  array  $data
     * @return Transaction
     * @throws \Exception
     */
    public function updateBalance(int $accountId, array $data): Transaction
    {
        DB::beginTransaction();

        $transaction = null;

        try {
            $this->accountRepository->updateBalance(
                $accountId,
                $data['to'],
                $data['amount']
            );
            $transaction = $this->transactionRepository->create(
                array_merge(['from' => $accountId], $data)
            );
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e);
        }

        DB::commit();

        return $transaction;
    }
}

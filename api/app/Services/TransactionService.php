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
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * TransactionService constructor.
     * @param  AccountRepositoryInterface  $accountRepository
     * @param  TransactionRepositoryInterface  $transactionRepository
     * @param  CurrencyService  $currencyService
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        TransactionRepositoryInterface $transactionRepository,
        CurrencyService $currencyService
    ) {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
        $this->currencyService = $currencyService;
    }

    /**
     * @param  int  $accountId
     * @param  array  $data
     * @throws ValidationException
     */
    public function validateTransaction(int $accountId, array $data)
    {
        $rules = [
            'to' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:1',
            'details' => 'required',
            'from' => 'required|exists:accounts,id'
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ((int) $data['to'] === $accountId) {
            $validator
                ->errors()
                ->add('to', "You cannot make transaction with yourself.");

            throw new ValidationException($validator);
        }

        $account = $this->accountRepository->find($accountId);

        // we store amount in USD, so we need check user currency and transform amount into right units if it needs
        $amount = $this->currencyService->getAmountInUSD(
            $account,
            $data['amount']
        );

        if ($account->balance < $amount) {
            $validator->errors()->add('from', "You don't have enough money.");
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
            $account = $this->accountRepository->find($accountId);

            $data['amount'] = $this->currencyService->getAmountInUSD(
                $account,
                $data['amount']
            );

            $this->accountRepository->updateBalance(
                $accountId,
                $data['to'],
                $data['amount']
            );
            $transaction = $this->transactionRepository->create($data);
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e);
        }

        DB::commit();

        return $transaction;
    }
}

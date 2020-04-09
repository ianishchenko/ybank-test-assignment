<?php

namespace App\Repository\Eloquent;

use App\Models\Transaction;
use App\Repository\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TransactionRepository
 * @package App\Repository\Eloquent
 */
class TransactionRepository extends BaseRepository implements
    TransactionRepositoryInterface
{
    /**
     * TransactionRepository constructor.
     * @param  Transaction  $model
     */
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  int  $accountId
     * @return array
     */
    public function findByAccount(int $accountId): Collection
    {
        return $this->model
            ->query()
            ->where('to', $accountId)
            ->orWhere('from', $accountId)
            ->get();
    }
}

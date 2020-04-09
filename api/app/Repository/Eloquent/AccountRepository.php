<?php

namespace App\Repository\Eloquent;

use App\Models\Account;
use App\Repository\AccountRepositoryInterface;

/**
 * Class AccountRepository
 * @package App\Repository\Eloquent
 */
class AccountRepository extends BaseRepository implements
    AccountRepositoryInterface
{
    /**
     * AccountRepository constructor.
     * @param  Account  $model
     */
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  int  $fromId
     * @param  int  $toId
     * @param  float  $amount is usd
     */
    public function updateBalance(int $fromId, int $toId, float $amount): void
    {
        $from = $this->find($fromId);
        $to = $this->find($toId);

        $from->balance -= $amount;
        $to->balance += $amount;

        $from->save();
        $to->save();
    }
}

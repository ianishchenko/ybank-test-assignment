<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 * @package App\Models
 */
class Account extends Model
{
    /**
     * @var string
     */
    const USD_CURRENCY = 'usd';

    /**
     * @var string
     */
    const EURO_CURRENCY = 'eur';

    /**
     * The attributes that are mass assignable.
     *
     * Currency is stored in USD
     *
     * @var array
     */
    protected $fillable = ['name', 'balance', 'currency'];
}

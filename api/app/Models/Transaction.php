<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @package App\Models
 */
class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * Amount is stored in USD
     *
     * @var array
     */
    protected $fillable = ['from', 'to', 'amount', 'details'];
}

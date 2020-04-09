<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
*/

Route::get('accounts/{id}', 'AccountController@show');

Route::get('accounts/{id}/transactions', 'AccountController@getRelatedTransactions');

Route::post('accounts/{id}/transactions', 'AccountController@storeTransaction');

Route::get('currencies', 'CurrencyController@index');

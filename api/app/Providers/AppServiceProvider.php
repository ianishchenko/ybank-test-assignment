<?php

namespace App\Providers;

use App\Services\CurrencyService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            TransactionService::class,
            TransactionService::class
        );
        $this->app->singleton(
            CurrencyService::class,
            CurrencyService::class
        );
    }
}

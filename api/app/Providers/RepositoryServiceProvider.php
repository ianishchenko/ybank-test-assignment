<?php

namespace App\Providers;

use App\Repository\AccountRepositoryInterface;
use App\Repository\Eloquent\AccountRepository;
use App\Repository\Eloquent\TransactionRepository;
use App\Repository\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }
}

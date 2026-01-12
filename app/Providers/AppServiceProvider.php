<?php

namespace App\Providers;

use App\Persistence\Implementation\Repositories\FundsRepository;
use App\Persistence\Implementation\Repositories\TransactionsRepository;
use App\Persistence\Implementation\Repositories\UsersRepository;
use App\Persistence\Implementation\RepositoryManager;
use App\Persistence\Interfaces\Repositories\FundsRepositoryInterface;
use App\Persistence\Interfaces\Repositories\TransactionsRepositoryInterface;
use App\Persistence\Interfaces\Repositories\UsersRepositoryInterface;
use App\Persistence\Interfaces\RepositoryManagerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryManagerInterface::class, RepositoryManager::class);
        $this->app->bind(FundsRepositoryInterface::class, FundsRepository::class);
        $this->app->bind(TransactionsRepositoryInterface::class, TransactionsRepository::class);
        $this->app->bind(UsersRepositoryInterface::class, UsersRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

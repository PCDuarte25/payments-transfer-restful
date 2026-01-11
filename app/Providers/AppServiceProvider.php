<?php

namespace App\Providers;

use App\Persistence\Implementation\RepositoryManager;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

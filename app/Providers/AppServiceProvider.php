<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    protected $listen = [
        'App\Events\VacationRequestSubmitted' => [
            'App\Listeners\SendVacationRequestEmail',
        ],
    ];

    public function register(): void
    {
        Paginator::useBootstrap();
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

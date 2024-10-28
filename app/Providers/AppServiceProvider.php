<?php

namespace App\Providers;

use App\Services\SearchService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\SearchServiceInterface;
use App\Repositories\VacationRepository;
use App\Interfaces\VacationRepositoryInterface;

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
        $this->app->bind(SearchServiceInterface::class, SearchService::class);
        $this->app->bind(VacationRepositoryInterface::class, VacationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

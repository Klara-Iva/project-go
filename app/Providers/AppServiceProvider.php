<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Interfaces\SearchServiceInterface;
use App\Http\Controllers\SearchController;

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
        $this->app->bind(SearchServiceInterface::class, SearchController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

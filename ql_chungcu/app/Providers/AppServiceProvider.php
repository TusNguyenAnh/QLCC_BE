<?php

namespace App\Providers;

use App\Repositories\BuildingRepository\BuildingRepository;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Services\Building\BuildingService;
use App\Services\Building\IBuildingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(IBuildingRepository::class, BuildingRepository::class);
        $this->app->bind(IBuildingService::class, BuildingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

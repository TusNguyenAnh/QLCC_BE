<?php

namespace App\Providers;

use App\Repositories\OrganizationRepository\IOrgRepository;
use App\Repositories\OrganizationRepository\OrgRepository;
use App\Repositories\OrgBuildingRepository\IOrgBuildingRepository;
use App\Repositories\OrgBuildingRepository\OrgBuildingRepository;
use App\Services\OrganizationService\IOrgService;
use App\Services\OrganizationService\OrgService;
use Illuminate\Support\ServiceProvider;

class OrganizationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IOrgRepository::class, OrgRepository::class);
        $this->app->bind(IOrgBuildingRepository::class, OrgBuildingRepository::class);
        $this->app->bind(IOrgService::class, OrgService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/UserAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/OrganizationAPI.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/BuildingAPI.php'));
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/ApartmentAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/ResidentAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/WorkflowAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/TaskAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/TaskTypeAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/PriorityAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/RoleAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/PermissionAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/MediaFileAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/ComplexAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/ServiceUnitPriceAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/RevenueAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/ExpenseAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/LedgerAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/AdjustmentTransactionAPI.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/StaffAPI.php'));
        });
    }
}

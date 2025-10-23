<?php

namespace App\Providers;

use App\Repositories\ApartmentRepository\ApartmentRepository;
use App\Repositories\ApartmentRepository\IApartmentRepository;
use App\Repositories\AptResidentRepository\AptResidentRepository;
use App\Repositories\AptResidentRepository\IAptResidentRepository;
use App\Repositories\BuildingRepository\BuildingRepository;
use App\Repositories\BuildingRepository\IBuildingRepository;
use App\Repositories\PriorityRepository\IPriorityRepository;
use App\Repositories\PriorityRepository\PriorityRepository;
use App\Repositories\ResidentRepository\IResidentRepository;
use App\Repositories\ResidentRepository\ResidentRepository;
use App\Repositories\TaskRepository\ITaskHistoryRepository;
use App\Repositories\TaskRepository\ITaskRepository;
use App\Repositories\TaskRepository\ITaskTypeRepository;
use App\Repositories\TaskRepository\TaskHistoryRepository;
use App\Repositories\TaskRepository\TaskRepository;
use App\Repositories\TaskRepository\TaskTypeRepository;
use App\Repositories\UserRepository\IUserRepository;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\WorkflowRepository\IWorkflowRepository;
use App\Repositories\WorkflowRepository\IWorkflowStepRepository;
use App\Repositories\WorkflowRepository\WorkflowRepository;
use App\Repositories\WorkflowRepository\WorkflowStepRepository;
use App\Services\ApartmentService\ApartmentService;
use App\Services\ApartmentService\IApartmentService;
use App\Services\AuthService\AuthService;
use App\Services\AuthService\IAuthService;
use App\Services\BuildingService\BuildingService;
use App\Services\BuildingService\IBuildingService;
use App\Services\PriorityService\IPriorityService;
use App\Services\PriorityService\PriorityService;
use App\Services\ResidentService\IResidentService;
use App\Services\ResidentService\ResidentService;
use App\Services\TaskService\ITaskService;
use App\Services\TaskService\ITaskTypeService;
use App\Services\TaskService\TaskService;
use App\Services\TaskService\TaskTypeService;
use App\Services\UserService\IUserService;
use App\Services\UserService\UserService;
use App\Services\WorkflowService\IWorkflowService;
use App\Services\WorkflowService\WorkflowService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //building
        $this->app->bind(IBuildingRepository::class, BuildingRepository::class);
        $this->app->bind(IBuildingService::class, BuildingService::class);

        //apartment
        $this->app->bind(IApartmentRepository::class, ApartmentRepository::class);
        $this->app->bind(IApartmentService::class, ApartmentService::class);

        //user
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IUserService::class, UserService::class);

        //resident
        $this->app->bind(IResidentRepository::class, ResidentRepository::class);
        $this->app->bind(IAptResidentRepository::class, AptResidentRepository::class);
        $this->app->bind(IResidentService::class, ResidentService::class);

        //workflow
        $this->app->bind(IWorkflowRepository::class, WorkflowRepository::class);
        $this->app->bind(IWorkflowStepRepository::class, WorkflowStepRepository::class);
        $this->app->bind(IWorkflowService::class, WorkflowService::class);

        //task
        $this->app->bind(ITaskRepository::class, TaskRepository::class);
        $this->app->bind(ITaskService::class, TaskService::class);

        //taskType
        $this->app->bind(ITaskTypeRepository::class, TaskTypeRepository::class);
        $this->app->bind(ITaskTypeService::class, TaskTypeService::class);

        //taskHistory
        $this->app->bind(ITaskHistoryRepository::class, TaskHistoryRepository::class);

        //priority
        $this->app->bind(IPriorityRepository::class, PriorityRepository::class);
        $this->app->bind(IPriorityService::class, PriorityService::class);

        //auth
        $this->app->bind(IAuthService::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

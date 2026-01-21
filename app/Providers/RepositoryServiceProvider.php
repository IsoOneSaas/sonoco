<?php namespace App\Providers;

use App\Interfaces\Set\LocationRepositoryInterface;
use App\Repositories\Set\LocationRepository;
use App\Interfaces\Set\DepartmentRepositoryInterface;
use App\Repositories\Set\DepartmentRepository;
use App\Interfaces\Set\JobRepositoryInterface;
use App\Repositories\Set\JobRepository;
use App\Interfaces\Set\UserRepositoryInterface;
use App\Repositories\Set\UserRepository;
use App\Interfaces\Set\SystemRepositoryInterface;
use App\Repositories\Set\SystemRepository;
use App\Interfaces\Set\AdminRepositoryInterface;
use App\Repositories\Set\AdminRepository;
use App\Interfaces\Set\ProcessRepositoryInterface;
use App\Repositories\Set\ProcessRepository;

// use App\Interfaces\DashboardRepositoryInterface;
// use App\Repositories\DashboardRepository;

// use App\Interfaces\Document\DashboardRepositoryInterface;
// use App\Repositories\Document\DashboardRepository;


use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // General
        $this->app->bind(\App\Interfaces\HomeRepositoryInterface::class, \App\Repositories\HomeRepository::class);         
        $this->app->bind(\App\Interfaces\DashboardRepositoryInterface::class, \App\Repositories\DashboardRepository::class);         
        // Settings
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(JobRepositoryInterface::class, JobRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SystemRepositoryInterface::class, SystemRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(ProcessRepositoryInterface::class, ProcessRepository::class);
        $this->app->bind(\App\Interfaces\Set\ProfileRepositoryInterface::class, \App\Repositories\Set\ProfileRepository::class);               
        // Documentos
        $this->app->bind(\App\Interfaces\Document\DashboardRepositoryInterface::class, \App\Repositories\Document\DashboardRepository::class);
        $this->app->bind(\App\Interfaces\Document\TemplateRepositoryInterface::class, \App\Repositories\Document\TemplateRepository::class);
        $this->app->bind(\App\Interfaces\Document\TypeRepositoryInterface::class, \App\Repositories\Document\TypeRepository::class);
        $this->app->bind(\App\Interfaces\Document\CustomizeRepositoryInterface::class, \App\Repositories\Document\CustomizeRepository::class);
        $this->app->bind(\App\Interfaces\Document\DocumentRepositoryInterface::class, \App\Repositories\Document\DocumentRepository::class);
        $this->app->bind(\App\Interfaces\Document\ControlRepositoryInterface::class, \App\Repositories\Document\ControlRepository::class);
        $this->app->bind(\App\Interfaces\Document\MasterRepositoryInterface::class, \App\Repositories\Document\MasterRepository::class);
        $this->app->bind(\App\Interfaces\Document\LinkRepositoryInterface::class, \App\Repositories\Document\LinkRepository::class);
        $this->app->bind(\App\Interfaces\Document\SuggestionRepositoryInterface::class, \App\Repositories\Document\SuggestionRepository::class);
        $this->app->bind(\App\Interfaces\Document\SightingRepositoryInterface::class, \App\Repositories\Document\SightingRepository::class);
        $this->app->bind(\App\Interfaces\Document\FollowupRepositoryInterface::class, \App\Repositories\Document\FollowupRepository::class);
        $this->app->bind(\App\Interfaces\Document\ValidationRepositoryInterface::class, \App\Repositories\Document\ValidationRepository::class);
        $this->app->bind(\App\Interfaces\Document\AuthorizationRepositoryInterface::class, \App\Repositories\Document\AuthorizationRepository::class);
        $this->app->bind(\App\Interfaces\Document\RecordRepositoryInterface::class, \App\Repositories\Document\RecordRepository::class);
        $this->app->bind(\App\Interfaces\Document\FileRepositoryInterface::class, \App\Repositories\Document\FileRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

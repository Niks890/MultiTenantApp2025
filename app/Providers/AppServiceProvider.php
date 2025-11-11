<?php

namespace App\Providers;

use App\Models\AdminTenant;
use App\Models\Plan;
use App\Models\Tax;
use App\Observers\AdminTenantObserver;
use App\Observers\PlanObserver;
use App\Observers\TaxObserver;
use App\Policies\PlanPolicy;
use App\Services\AdminTenantService;
use App\Services\Contracts\AdminTenantServiceInterface;
use App\Services\Contracts\PlanServiceInterface;
use App\Services\Contracts\TaxServiceInterface;
use App\Services\PlanService;
use App\Services\TaxService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $singletonRepos = config('repositories.singleton', []);
        $contractPath = app_path('Repositories/Contracts');

        foreach (glob($contractPath . '/*RepositoryInterface.php') as $file) {
            $name = basename($file, 'RepositoryInterface.php');

            $interface = "App\\Repositories\\Contracts\\{$name}RepositoryInterface";
            $implementation = "App\\Repositories\\Eloquent\\{$name}Repository";

            if (interface_exists($interface) && class_exists($implementation)) {
                if (in_array($name, $singletonRepos)) {
                    // Đăng ký singleton
                    $this->app->singleton($interface, $implementation);
                } else {
                    // Mặc định là bind
                    $this->app->bind($interface, $implementation);
                }
            }
        }

        $this->app->bind(AdminTenantServiceInterface::class, AdminTenantService::class);
        $this->app->bind(PlanServiceInterface::class, PlanService::class);
        $this->app->bind(TaxServiceInterface::class, TaxService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // phân trang custom
        Paginator::defaultView('components.admin.paginate.pagination');
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Plan::observe(PlanObserver::class);
        Tax::observe(TaxObserver::class);
        AdminTenant::observe(AdminTenantObserver::class);
        Gate::define('delete-plan', [PlanPolicy::class, 'delete']);
    }
}

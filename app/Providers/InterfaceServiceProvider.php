<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\AuthInterface;
use App\Interfaces\RoleServiceInterface;
use App\Interfaces\StaffInterface;
use App\Interfaces\SupplierInterface;
use App\Services\AuthService;
use App\Services\RoleService;
use App\Services\StaffService;
use App\Services\SupplierService;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthInterface::class,AuthService::class);
        $this->app->bind(StaffInterface::class,StaffService::class);
        $this->app->bind(SupplierInterface::class,SupplierService::class);
        $this->app->bind(RoleServiceInterface::class,RoleService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

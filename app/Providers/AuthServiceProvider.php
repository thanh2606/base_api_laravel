<?php

namespace App\Providers;

use App\Models\Admin;
use App\Policies\AdminPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Admin::class => AdminPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('can.update', function (Admin $admin) {
            return $admin->hasAction('update');
        });

        Gate::define('can.store', function (Admin $admin) {
            return $admin->hasAction('store');
        });

        Gate::define('can.show', function (Admin $admin) {
            return $admin->hasAction('show');
        });

        Gate::define('can.view', function (Admin $admin) {
            return $admin->hasAction('view');
        });

        Gate::define('can.delete', function (Admin $admin) {
            return $admin->hasAction('delete');
        });
    }
}

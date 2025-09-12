<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // システム管理者のみ
        Gate::define('sys-admin', function ($user) {
            return $user->role === 1;
        });

        // システム管理者 or 管理者
        Gate::define('admin', function ($user) {
            return in_array($user->role, [1, 2]);
        });
    }
}

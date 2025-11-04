<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Gunakan Bootstrap 4 untuk pagination
        Paginator::useBootstrapFour();

        // Register Klasifikasi Observer untuk auto-update is_leaf
        \App\Models\Klasifikasi::observe(\App\Observers\KlasifikasiObserver::class);
    }
}

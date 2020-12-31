<?php

namespace Laravel\Analytics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/analytics.php' => config_path('analytics.php'),
        ]);

        Route::group([
            'namespace' => 'Laravel\Analytics\Http\Controllers',
            'prefix' => 'analytics',
            // 'middleware' => 'analytics',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'analytics');
    }

    public function register(): void
    {
        //
    }
}

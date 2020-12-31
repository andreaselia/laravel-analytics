<?php

namespace Laravel\Analytics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Analytics\Http\Middleware\Track;
use Laravel\Analytics\Console\InstallCommand;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Commands...
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        // Config...

        $this->publishes([
            __DIR__.'/../config/analytics.php' => config_path('analytics.php'),
        ]);

        // Middleware...

        Route::middlewareGroup('analytics', ['web', Track::class]);

        // Routes...

        Route::group([
            'namespace' => 'Laravel\Analytics\Http\Controllers',
            'prefix' => 'analytics',
            'middleware' => 'analytics',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        // Views...

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'analytics');
    }

    public function register(): void
    {
        //
    }
}

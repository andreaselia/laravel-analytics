<?php

namespace AndreasElia\Analytics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use AndreasElia\Analytics\Http\Middleware\Analytics;
use AndreasElia\Analytics\Console\InstallCommand;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../config/analytics.php' => config_path('analytics.php'),
            ], 'analytics-config');
        }

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Middleware
        Route::middlewareGroup('analytics', [
            Analytics::class,
        ]);

        // Routes
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        // Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'analytics');
    }

    protected function routeConfig(): array
    {
        return [
            'namespace' => 'AndreasElia\Analytics\Http\Controllers',
            'prefix' => config('analytics.prefix'),
            'middleware' => config('analytics.middleware'),
        ];
    }

    public function register(): void
    {
        //
    }
}

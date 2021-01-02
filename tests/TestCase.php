<?php

namespace Laravel\Analytics\Tests;

use Laravel\Analytics\AnalyticsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            AnalyticsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__.'/../database/migrations/2020_01_01_100000_create_page_views_table.php';

        (new \CreatePageViewsTable)->up();
    }
}

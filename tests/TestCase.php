<?php

namespace Laravel\Analytics\Tests;

use Orchestra\Testbench\TestCase;
use Laravel\Analytics\AnalyticsServiceProvider;

class TestCase extends TestCase
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
        //
    }
}

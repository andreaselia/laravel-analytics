<?php

namespace Laravel\Analytics\Tests\Unit;

use Laravel\Analytics\Tests\TestCase;
use Laravel\Analytics\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_page_view_can_be_tracked()
    {
        $this->assertTrue(true);
    }
}

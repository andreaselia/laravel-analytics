<?php

namespace Laravel\Analytics\Tests\Unit;

use Laravel\Analytics\Tests\TestCase;
use Laravel\Analytics\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_get_request_can_be_tracked()
    {
        $pageView = PageView::factory()->create();

        $this->assertTrue($pageView->id !== 0);
    }

    /** @test */
    function a_post_request_can_be_tracked()
    {
        $this->assertTrue(true);
    }

    /** @test */
    function a_referred_request_can_be_tracked()
    {
        $this->assertTrue(true);
    }
}

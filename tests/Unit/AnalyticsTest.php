<?php

namespace AndreasElia\Analytics\Tests\Unit;

use AndreasElia\Analytics\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_page_view_can_be_tracked()
    {
        $response = $this->get('/test');

        dd($response->getContent());

        $response->assertStatus(200);
    }
}

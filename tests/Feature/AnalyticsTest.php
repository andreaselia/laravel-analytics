<?php

namespace AndreasElia\Analytics\Tests\Feature;

use Illuminate\Http\Request;
use AndreasElia\Analytics\Tests\TestCase;
use AndreasElia\Analytics\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AndreasElia\Analytics\Http\Middleware\Analytics;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_page_view_can_be_tracked()
    {
        $request = Request::create('/test', 'GET');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics)->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'device' => 'desktop',
        ]);
    }
}

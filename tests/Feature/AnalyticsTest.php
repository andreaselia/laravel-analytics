<?php

namespace AndreasElia\Analytics\Tests\Unit;

use Illuminate\Http\Request;
use AndreasElia\Analytics\Tests\TestCase;
use AndreasElia\Analytics\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AndreasElia\Analytics\Http\Middleware\Analytics;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase, InteractsWithSession;

    function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    function a_page_view_can_be_tracked()
    {
        $request = Request::create('/test', 'GET');
        $request->setLaravelSession(app('session')->driver());

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

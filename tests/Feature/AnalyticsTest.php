<?php

namespace AndreasElia\Analytics\Tests\Feature;

use AndreasElia\Analytics\Http\Middleware\Analytics;
use AndreasElia\Analytics\Models\PageView;
use AndreasElia\Analytics\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('analytics.mask', [
            '/test/*',
        ]);
    }

    /** @test */
    public function a_page_view_can_be_tracked()
    {
        $request = Request::create('/test', 'GET');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'device' => 'desktop',
        ]);
    }

    /** @test */
    public function a_page_view_can_be_masked()
    {
        $request = Request::create('/test/123', 'GET');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test/123', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test/∗︎',
            'device' => 'desktop',
        ]);
    }

    /** @test */
    public function a_page_view_can_be_excluded()
    {
        $request = Request::create('/analytics/123', 'GET');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, fn ($req) => null);

        $this->assertCount(0, PageView::all());
        $this->assertDatabaseMissing('page_views', [
            'uri' => '/analytics/123',
        ]);
    }

    /** @test */
    public function a_page_view_from_robot_can_be_tracked_if_enabled()
    {
        Config::set('analytics.ignoreRobots', false);

        $request = Request::create('/test', 'GET');
        $request->headers->set('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'device' => 'robot',
        ]);
    }

    /** @test */
    public function a_page_view_from_robot_is_not_tracked_if_enabled()
    {
        Config::set('analytics.ignoreRobots', true);

        $request = Request::create('/test', 'GET');
        $request->headers->set('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(0, PageView::all());
        $this->assertDatabaseMissing('page_views', [
            'uri' => '/test',
        ]);
    }

    /** @test */
    public function a_page_view_from_an_excluded_ip_is_not_tracked_if_enabled()
    {
        Config::set('analytics.ignoredIPs', ['127.0.0.2']);

        $request = Request::create('/test', 'GET', [], [], [], ['REMOTE_ADDR' => '127.0.0.2']);
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(0, PageView::all());
        $this->assertDatabaseMissing('page_views', [
            'uri' => '/test',
        ]);
    }
}

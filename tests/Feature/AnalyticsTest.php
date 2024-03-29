<?php

namespace AndreasElia\Analytics\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use AndreasElia\Analytics\Http\Middleware\Analytics;
use AndreasElia\Analytics\Models\PageView;
use AndreasElia\Analytics\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('analytics.mask', [
            '/test/*',
        ]);
    }

    #[Test]
    public function a_page_view_can_be_tracked()
    {
        $this->markTestSkipped('This test is not yet fixed.');

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

    #[Test]
    public function page_views_arent_tracked_when_not_enabled()
    {
        Config::set('analytics.enabled', false);
        $request = Request::create('/page');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, fn ($req) => null);

        $this->assertCount(0, PageView::all());
        $this->assertDatabaseMissing('page_views', [
            'uri' => '/page',
        ]);
    }

    #[Test]
    public function a_page_view_can_be_masked()
    {
        $this->markTestSkipped('This test is not yet fixed.');

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

    #[Test]
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

    #[Test]
    public function methods_can_be_excluded()
    {
        Config::set('analytics.ignoreMethods', ['POST']);
        $request = Request::create('/users', 'POST');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, fn ($req) => null);

        $this->assertCount(0, PageView::all());
        $this->assertDatabaseMissing('page_views', [
            'uri' => '/users',
        ]);
    }

    #[Test]
    public function a_page_view_from_robot_can_be_tracked_if_enabled()
    {
        $this->markTestSkipped('This test is not yet fixed.');

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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function utm_details_can_be_saved_with_page_views()
    {
        $this->markTestSkipped('This test is not yet fixed.');

        $request = Request::create('/test', 'GET', [
            'utm_source' => 'test-source',
            'utm_medium' => 'test-medium',
            'utm_campaign' => 'test-campaign',
            'utm_term' => 'test-term',
            'utm_content' => 'test-content',
        ]);
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'device' => 'desktop',
            'utm_source' => 'test-source',
            'utm_medium' => 'test-medium',
            'utm_campaign' => 'test-campaign',
            'utm_term' => 'test-term',
            'utm_content' => 'test-content',
        ]);
    }

    #[Test]
    public function utm_details_will_be_trimmed()
    {
        $this->markTestSkipped('This test is not yet fixed.');

        $string = Str::random(300);
        $request = Request::create('/test', 'GET', [
            'utm_source' => $string,
        ]);
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'device' => 'desktop',
            'utm_source' => substr($string, 0, 255),
            'utm_medium' => null,
            'utm_campaign' => null,
            'utm_term' => null,
            'utm_content' => null,
        ]);
    }
}

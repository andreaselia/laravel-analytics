<?php

namespace AndreasElia\Analytics\Tests\Feature;

use AndreasElia\Analytics\Http\Middleware\Analytics;
use AndreasElia\Analytics\Models\PageView;
use AndreasElia\Analytics\Tests\TestCase;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function it_can_ignore_some_columns_when_saving()
    {
        $request = Request::create('/test', 'GET', [
            'utm_source' => 'test-source',
            'utm_medium' => 'test-medium',
            'utm_campaign' => 'test-campaign',
            'utm_term' => 'test-term',
            'utm_content' => 'test-content',
        ]);
        $request->setLaravelSession($this->app['session']->driver());
        config()->set('analytics.ignoredColumns', [
            'device',
            'utm_source',
            'utm_term',
            'host',
        ]);

        (new Analytics())->handle($request, function ($req) {
            $this->assertEquals('test', $req->path());
            $this->assertEquals('GET', $req->method());
        });

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'device' => null,
            'host' => null,
            'utm_source' => null,
            'utm_medium' => 'test-medium',
            'utm_campaign' => 'test-campaign',
            'utm_term' => null,
            'utm_content' => 'test-content',
        ]);
    }

    #[Test]
    public function non_string_utm_details_are_ignored()
    {
        $request = Request::create('/test', 'GET', [
            'utm_source' => ['test-source', 'other-source'],
            'utm_medium' => 'test-medium',
        ]);
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, fn ($req) => null);

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'utm_source' => null,
            'utm_medium' => 'test-medium',
        ]);
    }

    #[Test]
    public function the_accept_language_header_from_the_request_is_used()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'fr-FR';

        try {
            $request = Request::create('/test', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'de-DE,de;q=0.9']);
            $request->setLaravelSession($this->app['session']->driver());

            (new Analytics())->handle($request, fn ($req) => null);
        } finally {
            unset($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        }

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'country' => 'Germany',
        ]);
    }

    #[Test]
    public function the_session_id_is_stored_hashed()
    {
        $session = $this->app['session']->driver();
        $request = Request::create('/test', 'GET');
        $request->setLaravelSession($session);

        (new Analytics())->handle($request, fn ($req) => null);

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'session' => hash('sha256', $session->getId()),
        ]);
        $this->assertDatabaseMissing('page_views', [
            'session' => $session->getId(),
        ]);
    }

    #[Test]
    public function a_page_view_without_a_session_is_tracked_without_one()
    {
        $request = Request::create('/test', 'GET');

        (new Analytics())->handle($request, fn ($req) => null);

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'session' => null,
        ]);
    }

    #[Test]
    public function an_unknown_browser_is_stored_as_null()
    {
        $request = Request::create('/test', 'GET');
        $request->headers->set('User-Agent', 'curl/8.0');
        $request->setLaravelSession($this->app['session']->driver());

        (new Analytics())->handle($request, fn ($req) => null);

        $this->assertCount(1, PageView::all());
        $this->assertDatabaseHas('page_views', [
            'uri' => '/test',
            'browser' => null,
        ]);
    }

    #[Test]
    public function a_tracking_failure_does_not_break_the_response()
    {
        $this->mock(ExceptionHandler::class)
            ->shouldReceive('report')
            ->once()
            ->with(Mockery::type(QueryException::class));

        Schema::drop('page_views');

        $request = Request::create('/test', 'GET');
        $request->setLaravelSession($this->app['session']->driver());
        $response = new Response('ok');

        $this->assertSame($response, (new Analytics())->handle($request, fn ($req) => $response));
    }
}

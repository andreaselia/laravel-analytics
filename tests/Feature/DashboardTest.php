<?php

namespace AndreasElia\Analytics\Tests\Unit;

use Illuminate\Http\Request;
use AndreasElia\Analytics\Tests\TestCase;
use AndreasElia\Analytics\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AndreasElia\Analytics\Http\Middleware\Analytics;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        PageView::create([
            'session' => '123',
            'uri' => '/test',
            'source' => 'example.com',
            'country' => 'us',
            'browser' => 'chrome',
            'device' => 'desktop',
            'created_at' => now(),
        ]);

        PageView::create([
            'session' => '123',
            'uri' => '/test2',
            'source' => 'example.com',
            'country' => 'us',
            'browser' => 'chrome',
            'device' => 'desktop',
            'created_at' => now(),
        ]);

        PageView::insert([
            'session' => '5555',
            'uri' => '/test2',
            'source' => 'example.com',
            'country' => 'us',
            'browser' => 'chrome',
            'device' => 'desktop',
            'created_at' => now()->subDay(),
        ]);

        PageView::insert([
            'session' => '9123',
            'uri' => '/test6',
            'source' => 'example.com',
            'country' => 'us',
            'browser' => 'chrome',
            'device' => 'desktop',
            'created_at' => now()->subWeek(),
        ]);

        PageView::insert([
            'session' => '9123',
            'uri' => '/test2',
            'source' => 'example.com',
            'country' => 'us',
            'browser' => 'chrome',
            'device' => 'desktop',
            'created_at' => now()->subWeek(),
        ]);

        PageView::insert([
            'session' => '9123',
            'uri' => '/test2',
            'source' => 'example.com',
            'country' => 'us',
            'browser' => 'chrome',
            'device' => 'desktop',
            'created_at' => now()->subWeeks(3),
        ]);
    }

    /** @test */
    function it_can_get_data_from_today()
    {
        $this->get('analytics')
            ->assertViewHas('period', 'today')
            ->assertViewHas('stats', [
                [
                    'key' => 'Unique Users',
                    'value' => 1,
                ],
                [
                    'key' => 'Page Views',
                    'value' => 2,
                ],
        ]);
    }

    /** @test */
    function it_can_get_data_from_yesterday()
    {
        $this->get(route('analytics', ['period' => 'yesterday']))
            ->assertViewHas('period', 'yesterday')
            ->assertViewHas('stats', [
                [
                    'key' => 'Unique Users',
                    'value' => 1,
                ],
                [
                    'key' => 'Page Views',
                    'value' => 1,
                ],
        ]);
    }

    /** @test */
    function it_can_get_data_for_1_week()
    {
        $this->get(route('analytics', ['period' => '1_week']))
            ->assertViewHas('period', '1_week')
            ->assertViewHas('stats', [
                [
                    'key' => 'Unique Users',
                    'value' => 3,
                ],
                [
                    'key' => 'Page Views',
                    'value' => 5,
                ],
        ]);
    }

    /** @test */
    function it_can_get_data_for_30_days()
    {
        $this->get(route('analytics', ['period' => '30_days']))
            ->assertViewHas('period', '30_days')
            ->assertViewHas('stats', [
                [
                    'key' => 'Unique Users',
                    'value' => 3,
                ],
                [
                    'key' => 'Page Views',
                    'value' => 6,
                ],
        ]);
    }
}

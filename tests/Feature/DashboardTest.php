<?php

namespace AndreasElia\Analytics\Tests\Feature;

use AndreasElia\Analytics\Database\Factories\PageViewFactory;
use AndreasElia\Analytics\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        PageViewFactory::new()->count(2)->create([
            'session' => 'abc123',
        ]);

        $this->travelTo(now()->subDay(), function () {
            PageViewFactory::new()->create();
        });

        $this->travelTo(now()->subDays(5), function () {
            PageViewFactory::new()->count(2)
                ->sequence(
                    ['uri' => '/test1'],
                    ['uri' => '/test2']
                )
                ->create(['session' => 'foo']);
        });

        $this->travelTo(now()->subWeeks(3), function () {
            PageViewFactory::new()->create();
        });

    }

    /** @test */
    function it_can_get_data_from_today()
    {
        $this->get('analytics')
            ->assertViewHas('period', 'today')
            ->assertViewHas('stats', [
                [
                    'key'   => 'Unique Users',
                    'value' => 1,
                ],
                [
                    'key'   => 'Page Views',
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
                    'key'   => 'Unique Users',
                    'value' => 1,
                ],
                [
                    'key'   => 'Page Views',
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
                    'key'   => 'Unique Users',
                    'value' => 3,
                ],
                [
                    'key'   => 'Page Views',
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
                    'key'   => 'Unique Users',
                    'value' => 4,
                ],
                [
                    'key'   => 'Page Views',
                    'value' => 6,
                ],
            ]);
    }
}

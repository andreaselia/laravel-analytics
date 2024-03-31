<?php

namespace AndreasElia\Analytics\Tests\Feature;

use AndreasElia\Analytics\Database\Factories\PageViewFactory;
use AndreasElia\Analytics\Models\PageView;
use AndreasElia\Analytics\Tests\TestCase;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TimezoneTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $date = CarbonImmutable::today('America/Los_Angeles')
            ->setTimezone(config('app.timezone'));

        PageViewFactory::new()->count(2)->create([
            'session' => 'abc123',
        ]);

        $this->travelTo($date->subDay(), function () {
            PageViewFactory::new()->create();
        });

        $this->travelTo(today()->subDays(3), function () {
            PageViewFactory::new()->count(2)->create();
        });

        $this->travelTo($date->subWeeks(3), function () {
            PageViewFactory::new()->create();
        });

        PageView::resolveTimezoneUsing(fn () => 'America/Los_Angeles');
    }

     #[Test]
    public function it_can_resolve_timezone()
    {
        $pageView = new PageView();

        $this->assertEquals('America/Los_Angeles', $pageView->getTimezone());

        PageView::resolveTimezoneUsing(fn () => null);
        $this->assertEquals(config('app.timezone'), $pageView->getTimezone());
    }

     #[Test]
    public function it_can_get_data_from_today()
    {
        $views = PageView::query()
            ->filter('today')
            ->count();

        $this->assertEquals(2, $views);
    }

     #[Test]
    public function it_can_get_data_from_yesterday()
    {
        $views = PageView::query()
            ->filter('yesterday')
            ->count();

        $this->assertEquals(1, $views);
    }
}

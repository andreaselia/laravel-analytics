<?php

namespace AndreasElia\Analytics\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use AndreasElia\Analytics\Models\PageView;

class HomeController extends Controller
{
    /** @var string */
    protected $period;

    public function index(Request $request): View
    {
        $this->period = $request->get('period', 'today');

        return view('analytics::dashboard', [
            'period' => $this->period,
            'periods' => $this->periods(),
            'stats' => $this->stats(),
            'pages' => $this->pages(),
            'sources' => $this->sources(),
            'users' => $this->users(),
            'devices' => $this->devices(),
        ]);
    }

    protected function periods(): array
    {
        return [
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            '1_week' => 'Last 7 days',
            '30_days' => 'Last 30 days',
            '6_months' => 'Last 6 months',
            '12_months' => 'Last 12 months',
        ];
    }

    protected function stats(): array
    {
        return [
            [
                'key' => 'Unique Users',
                'value' => PageView::query()
                    ->scopes(['filter' => [$this->period]])
                    ->groupBy('session')
                    ->pluck('session')
                    ->count(),
            ],
            [
                'key' => 'Page Views',
                'value' => PageView::query()
                    ->scopes(['filter' => [$this->period]])
                    ->count(),
            ],
        ];
    }

    protected function pages(): Collection
    {
        return PageView::query()
            ->scopes(['filter' => [$this->period]])
            ->select('uri as page', DB::raw('count(*) as users'))
            ->groupBy('page')
            ->get();
    }

    protected function sources(): Collection
    {
        return PageView::query()
            ->scopes(['filter' => [$this->period]])
            ->select('source as page', DB::raw('count(*) as users'))
            ->whereNotNull('source')
            ->groupBy('source')
            ->get();
    }

    protected function users(): Collection
    {
        return PageView::query()
            ->scopes(['filter' => [$this->period]])
            ->select('country', DB::raw('count(*) as users'))
            ->groupBy('country')
            ->get();
    }

    protected function devices(): Collection
    {
        return PageView::query()
            ->scopes(['filter' => [$this->period]])
            ->select('device as type', DB::raw('count(*) as users'))
            ->groupBy('type')
            ->get();
    }
}

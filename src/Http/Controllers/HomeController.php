<?php

namespace Laravel\Analytics\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Analytics\Models\PageView;

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
            '1week' => 'Last 7 days',
            '30days' => 'Last 30 days',
            '6months' => 'Last 6 months',
            '12months' => 'Last 12 months',
        ];
    }

    protected function stats(): array
    {
        return [
            [
                'key' => 'Unique Users',
                'value' => PageView::filter($this->period)->groupBy('ip_address')->count(),
            ],
            [
                'key' => 'Page Views',
                'value' => PageView::filter($this->period)->count(),
            ],
        ];
    }

    protected function pages(): Collection
    {
        return PageView::filter($this->period)
            ->select('uri as page', DB::raw('count(*) as users'))
            ->groupBy('page')
            ->get();
    }

    protected function sources(): Collection
    {
        return PageView::filter($this->period)
            ->select('source as page', DB::raw('count(*) as users'))
            ->whereNotNull('page')
            ->groupBy('page')
            ->get();
    }

    protected function users(): Collection
    {
        return PageView::filter($this->period)
            ->select('country', DB::raw('count(*) as users'))
            ->groupBy('country')
            ->get();
    }

    protected function devices(): Collection
    {
        return PageView::filter($this->period)
            ->select('device as type', DB::raw('count(*) as users'))
            ->groupBy('type')
            ->get();
    }
}

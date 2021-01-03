<?php

namespace Laravel\Analytics\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Analytics\Models\PageView;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('analytics::dashboard', [
            'filters' => $this->filters(),
            'stats' => $this->stats(),
            'pages' => $this->pages(),
            'sources' => $this->sources(),
            'users' => $this->users(),
            'devices' => $this->devices(),
        ]);
    }

    protected function filters(): array
    {
        return [
            'today' => 'Today',
            '7d' => 'Last 7 days',
            '30d' => 'Last 30 days',
            '6m' => 'Last 6 months',
            '12m' => 'Last 12 months',
        ];
    }

    protected function stats(): array
    {
        $uniqueUsers = PageView::groupBy('ip_address')->count();
        $pageViews = PageView::count();

        return [
            [
                'key' => 'Unique Users',
                'value' => $uniqueUsers,
            ],
            [
                'key' => 'Page Views',
                'value' => $pageViews,
            ],
            [
                'key' => 'Bounce Rate',
                'value' => '?%',
            ],
            [
                'key' => 'Average Visit',
                'value' => '?m ?s',
            ],
        ];
    }

    protected function pages(): Collection
    {
        return PageView::select('uri as page', DB::raw('count(*) as users'))
            ->groupBy('page')
            ->get();
    }

    protected function sources(): Collection
    {
        return PageView::select('source as page', DB::raw('count(*) as users'))
            ->whereNotNull('page')
            ->groupBy('page')
            ->get();
    }

    protected function users(): Collection
    {
        return PageView::select('country', DB::raw('count(*) as users'))
            ->groupBy('country')
            ->get();
    }

    protected function devices(): Collection
    {
        return PageView::select('device as type', DB::raw('count(*) as users'))
            ->groupBy('type')
            ->get();
    }
}

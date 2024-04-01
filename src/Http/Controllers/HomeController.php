<?php

namespace AndreasElia\Analytics\Http\Controllers;

use AndreasElia\Analytics\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected array $scopes = [];

    public function index(Request $request): View
    {
        $period = $request->input('period', 'today');
        $uri = $request->input('uri');

        $this->scopes = [
            'filter' => [$period],
            'uri' => [$uri],
        ];

        return view('analytics::dashboard', [
            'period'  => $period,
            'uri'     => $uri,
            'periods' => $this->periods(),
            'stats'   => $this->stats(),
            'pages'   => $this->pages(),
            'sources' => $this->sources(),
            'users'   => $this->users(),
            'devices' => $this->devices(),
            'utm'     => $this->utm(),
        ]);
    }

    protected function periods(): array
    {
        return [
            'today'     => 'Today',
            'yesterday' => 'Yesterday',
            '1_week'    => 'Last 7 days',
            '30_days'   => 'Last 30 days',
            '6_months'  => 'Last 6 months',
            '12_months' => 'Last 12 months',
        ];
    }

    protected function stats(): array
    {
        return [
            [
                'key'   => 'Unique Users',
                'value' => PageView::query()
                    ->scopes($this->scopes)
                    ->groupBy('session')
                    ->pluck('session')
                    ->count(),
            ],
            [
                'key'   => 'Page Views',
                'value' => PageView::query()
                    ->scopes($this->scopes)
                    ->count(),
            ],
        ];
    }

    protected function pages(): Collection
    {
        return PageView::query()
            ->scopes($this->scopes)
            ->select('uri as page', DB::raw('count(*) as users'))
            ->groupBy('page')
            ->orderBy('users', 'desc')
            ->get();
    }

    protected function sources(): Collection
    {
        return PageView::query()
            ->scopes($this->scopes)
            ->select('source as page', DB::raw('count(*) as users'))
            ->whereNotNull('source')
            ->groupBy('source')
            ->orderBy('users', 'desc')
            ->get();
    }

    protected function users(): Collection
    {
        return PageView::query()
            ->scopes($this->scopes)
            ->select('country', DB::raw('count(*) as users'))
            ->groupBy('country')
            ->orderBy('users', 'desc')
            ->get();
    }

    protected function devices(): Collection
    {
        return PageView::query()
            ->scopes($this->scopes)
            ->select('device as type', DB::raw('count(*) as users'))
            ->groupBy('type')
            ->orderBy('users', 'desc')
            ->get();
    }

    protected function utm(): Collection
    {
        return collect([
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ])->mapWithKeys(fn (string $key) => [$key => [
            'key'   => $key,
            'items' => PageView::query()
                ->select([$key, DB::raw('count(*) as count')])
                ->scopes($this->scopes)
                ->whereNotNull($key)
                ->groupBy($key)
                ->orderBy('count', 'desc')
                ->get()
                ->map(fn ($item) => [
                    'value' => $item->{$key},
                    'count' => $item->count,
                ]),
        ]])->filter(fn (array $set) => $set['items']->count() > 0);
    }
}

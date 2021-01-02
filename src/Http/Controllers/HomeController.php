<?php

namespace Laravel\Analytics\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Laravel\Analytics\Models\PageView;

class HomeController extends Controller
{
    public function index(): View
    {
        $pages = PageView::groupBy('uri')->latest()->take(10)->get();

        return view('analytics::dashboard', [
            'filters' => [
                'today' => 'Today',
                '7d' => 'Last 7 days',
                '30d' => 'Last 30 days',
                '6m' => 'Last 6 months',
                '12m' => 'Last 12 months',
            ],
            'stats' => [
                [
                    'key' => 'Unique Users',
                    'value' => '51,900',
                    'increase' => true,
                    'percentage' => 13,
                ],
                [
                    'key' => 'Page Views',
                    'value' => '157,000',
                    'increase' => false,
                    'percentage' => 7,
                ],
                [
                    'key' => 'Bounce Rate',
                    'value' => '60%',
                    'increase' => true,
                    'percentage' => 15,
                ],
                [
                    'key' => 'Average Visit',
                    'value' => '1m 23s',
                    'increase' => false,
                    'percentage' => 3,
                ],
            ],
            'pages' => [
                ['page' => '/', 'users' => '25.8k'],
                ['page' => '/download', 'users' => '12k'],
            ],
            'sources' => [
                [
                    'icon' => 'https://github.githubassets.com/favicons/favicon.svg',
                    'page' => 'github.com',
                    'users' => '32.4k',
                ],
                [
                    'icon' => 'https://abs.twimg.com/favicons/twitter.ico',
                    'page' => 't.co',
                    'users' => '17.8k',
                ],
            ],
            'users' => [
                ['country' => 'United Kingdom', 'users' => '24.9k'],
                ['country' => 'United States', 'users' => '8.5k'],
                ['country' => 'Germany', 'users' => '3.8k'],
                ['country' => 'Netherlands', 'users' => '1.7k'],
            ],
            'devices' => [
                ['type' => 'Desktop', 'users' => '192k'],
                ['type' => 'Mobile', 'users' => '133k'],
                ['type' => 'Laptop', 'users' => '75.5k'],
                ['type' => 'Tablet', 'users' => '16.3k'],
            ],
        ]);
    }
}

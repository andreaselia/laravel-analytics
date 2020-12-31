<?php

namespace Laravel\Analytics\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('analytics::dashboard', [
            'stats' => [
                ['key' => 'Unique Users', 'value' => '51,900', 'increase' => true],
                ['key' => 'Page Views', 'value' => '157,000', 'increase' => false],
                ['key' => 'Bounce Rate', 'value' => '60%', 'increase' => true],
                ['key' => 'Average Visit', 'value' => '1m 23s', 'increase' => false],
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

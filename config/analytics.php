<?php

use Laravel\Analytics\Http\Middleware\Track;

return [

    /**
     * Analytics Dashboard
     *
     * The prefix and middleware for the analytics dashboard.
     */

    'prefix' => 'analytics',

    'middleware' => [
        'web',
    ],

    /**
     * Exclude
     *
     * The routes excluded from page view tracking (e.g. /admin);
     */

    'exclude' => [
        '/analytics',
    ],

];

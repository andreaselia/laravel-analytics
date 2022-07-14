<?php

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
     * The routes excluded from page view tracking.
     */

    'exclude' => [
        '/analytics',
    ],

    'session' => [
        'provider' = AndreasElia\Analytics\RequestSessionIdProvider::class,
    ],

];

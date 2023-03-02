<?php

return [

    /**
     * Analytics Dashboard.
     *
     * The prefix and middleware for the analytics dashboard.
     */
    'prefix' => 'analytics',

    'middleware' => [
        'web',
    ],

    /**
     * Exclude.
     *
     * The routes excluded from page view tracking.
     */
    'exclude' => [
        '/analytics',
        '/analytics/*',
    ],

    /**
     * Mask.
     *
     * Mask routes so they are tracked together.
     */
    'mask' => [
        // '/users/*',
    ],

    'session' => [
        'provider' => \AndreasElia\Analytics\RequestSessionProvider::class,
    ],

];

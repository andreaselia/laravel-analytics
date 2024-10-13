<?php

return [

    'enabled' => env('ANALYTICS_ENABLED', true),
    'db_connection' => env('ANALYTICS_DB_CONNECTION', env('DB_CONNECTION')),

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
     * Determine if traffic from robots should be tracked.
     */
    'ignoreRobots' => false,

    /**
     * Ignored IP addresses.
     *
     * The IP addresses excluded from page view tracking.
     */
    'ignoredIPs' => [
        // '192.168.1.1',
    ],

    /**
     * Mask.
     *
     * Mask routes so they are tracked together.
     */
    'mask' => [
        // '/users/*',
    ],

    /**
     * Ignore methods.
     *
     * The HTTP verbs/methods that should be excluded from page view tracking.
     */
    'ignoreMethods' => [
        // 'OPTIONS', 'POST',
    ],

    /**
     * Columns that won't be tracked.
     *
     * List the columns you want to ignore from the page view tracking.
     */
    'ignoredColumns' => [
        // 'source',
        // 'country',
        // 'browser',
        // 'device',
        // 'host',
        // 'utm_source',
        // 'utm_medium',
        // 'utm_campaign',
        // 'utm_term',
        // 'utm_content',
    ],

    'session' => [
        'provider' => \AndreasElia\Analytics\RequestSessionProvider::class,
    ],

    'model' =>  \AndreasElia\Analytics\Models\PageView::class,


];

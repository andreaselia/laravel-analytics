# Laravel Analytics

[![Latest Stable Version](https://poser.pugx.org/andreaselia/analytics/v)](//packagist.org/packages/andreaselia/analytics)

Easily collect page view analytics with a beautifully simple to use dashboard.

![Laravel Analytics Dashboard](/screenshot.png?raw=true "Laravel Analytics Dashboard")

## Installation

Install the package:

```bash
composer require andreaselia/analytics
```

Publish the config file and assets:

```bash
php artisan vendor:publish --provider="AndreasElia\Analytics\AnalyticsServiceProvider"
```

Don't forget to run the migrations:

```bash
php artisan migrate
```

You can add the page view middleware to a specific route group, e.g. `web.php` like so:

```php
Route::middleware('analytics')->group(function () {
    // ...
});
```

Or add the page view to all middlewares/on an application level like so:

```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \AndreasElia\Analytics\Http\Middleware\Analytics::class,
];
```

On Laravel 11 and later, register it in `bootstrap/app.php` instead, e.g. on the `web` middleware group:

```php
// bootstrap/app.php

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \AndreasElia\Analytics\Http\Middleware\Analytics::class,
    ]);
})
```

Requests that run outside the `web` middleware group (e.g. API routes) have no session, so they are tracked without one unless you configure a custom session provider (see below).

## Configuration

### Dashboard

The dashboard is available at `/analytics` by default. You can change its URL with the `prefix` option, serve it on a specific (sub)domain with the `domain` option (e.g. `'analytics.example.com'`), and set the route middleware with the `middleware` option. If you change the `prefix`, update the `exclude` option too so that dashboard visits aren't tracked.

> [!WARNING]
> The dashboard is public by default. The default `middleware` is only `['web']`, so anyone who knows the URL can see your analytics. Protect it by adding authentication (and optionally authorization) middleware in the `analytics.php` config file:
>
> ```php
> 'middleware' => [
>     'web',
>     'auth',
>     // 'can:viewAnalytics', // with Gate::define('viewAnalytics', fn ($user) => ...)
> ],
> ```

### Disabling tracking

You can disable tracking by setting the environment variable `ANALYTICS_ENABLED` or the `enabled` property in the `analytics.php` config file to `false`.

### Excluding routes

You can exclude certain routes from being tracked by adding them to the `exclude` array in the `analytics.php` config file.

### Ignore robots

You can ignore requests from robots by setting the `ignoreRobots` property in the `analytics.php` config file.

### Ignore specific IP addresses

You can ignore requests from specific IP addresses by adding them to the `ignoredIPs` array in the `analytics.php` config file.

### Masking routes

You can mask certain routes from being tracked by adding them to the `mask` array in the `analytics.php` config file. 
This is useful if you want to track the same route with different parameters, e.g. `/users/1` and `/users/2` will be tracked as `/users/∗︎`.

### Ignoring certain HTTP verbs/methods

You can ignore the tracking of some methods by adding them to the `analytics.ignoreMethods` config option. For example, if you don't want to track `POST` requests, you can configure it like so:

```php
'ignoreMethods' => [
    'POST',
],
```

### Ignoring columns

You can stop certain details from being stored by adding their column names to the `ignoredColumns` array in the `analytics.php` config file. The available columns are `source`, `country`, `browser`, `device`, `host`, `utm_source`, `utm_medium`, `utm_campaign`, `utm_term` and `utm_content`. Ignored columns are saved as `null`.

```php
'ignoredColumns' => [
    'source',
    'host',
],
```

### Changing how the session is determined

By default, `session` in the `page_views` table is filled with a SHA-256 hash of the session ID of the current request. However, in certain scenarios (for example, for API and other requests not using cookies), the session is unavailable, and the page view is stored with a `null` session.

In these cases, you can create a custom session ID provider: create a class that implements the `AndreasElia\Analytics\Contracts\SessionProvider` interface and set its name as the `provider` option in the `analytics.php` config file. The configured class object is resolved from the container, therefore, dependency injection can be used via the `__constructor`. 

One example of a custom way to generate the session ID in cookie-less environment is to hash IP address + User Agent + some other headers from the request.

Feel free to take a look at `AndreasElia\Analytics\RequestSessionProvider` for an example of implementing the `SessionProvider` interface.

### Changing how the timezone for "today" and "yesterday" is determined

Since timestamps are stored using your application's timezone, you may get mixed results depending on when you check views for "today" and "yesterday" and your actual timezone. You can change the relative time for whatever "now" is by setting a callback in a service provider.

```php
use AndreasElia\Analytics\Models\PageView;

public function boot()
{
    PageView::resolveTimezoneUsing(function () {
        return request()->user()?->timezone;
    });
}
```

You can return a dynamic value like the example above, or a static value. If one isn't determined, it will just fall back to the `config('app.timezone')` value.

## Laravel Nova

The package comes with a dashboard and metrics for Laravel Nova.

### Dashboard

You can add the dashboard to Laravel Nova by adding `new \AndreasElia\Analytics\Nova\Dashboards\Analytics` to `dashboards` array in your `NovaServiceProvider`:

```php
    protected function dashboards(): array
    {
        return [
            new \AndreasElia\Analytics\Nova\Dashboards\Analytics,
        ];
    }
```

### Metrics

Alternatively, you can add the metrics to your own Laravel Nova dashboard by adding them to the `cards` array in your dashboard file.

```php
    protected function cards(): array
    {
        return [
            new \AndreasElia\Analytics\Nova\Metrics\Devices,
            new \AndreasElia\Analytics\Nova\Metrics\PageViews,
            new \AndreasElia\Analytics\Nova\Metrics\UniqueUsers,
        ];
    }
```

## Contributing

You're more than welcome to submit a pull request, or if you're not feeling up to it - create an issue so someone else can pick it up.

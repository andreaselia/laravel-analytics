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

## Configuration

### Changing how session_id is determined

By default, `session_id` in the `page_views` table is filled with the session ID of the current request. However, in certain scenarios (for example, for API and other requests not using cookies), the session is unavailable.

In these cases, you can create a custom session ID provider: create a class that implements the `AndreasElia\Analytics\Contracts\SessionProvider` interface and set its name as the `provider` option in the `analytics.php` config file. The configured class object is resolved from the container, therefore, dependency injection can be used via the `__constructor`. 

One example of a custom way to generate the session ID in cookie-less environment is to hash IP address + User Agent + some other headers from the request.

Feel free to take a look at `AndreasElia\Analytics\RequestSessionProvider` for an example of implementing the `SessionIdProvider` interface.

## Contributing

You're more than welcome to submit a pull request, or if you're not feeling up to it - create an issue so someone else can pick it up.

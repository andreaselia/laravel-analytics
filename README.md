# Laravel Analytics

Easily collect page view analytics with a beautifully simple to use dashboard.

## Installation

Install the package:

```bash
composer require laravel/analytics
```

Run the install command below. You can remove the `--migrate` tag if you wish to migrate yourself.

```bash
php artisan analytics:install --migrate
```

Add the page view middleware to a specific route group, e.g. `web.php` like so:

```bash
Route::middleware('analytics')->group(function () {
    // ...
});
```

Add the page view to all middlewares/on an application level like so:

```bash
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \Laravel\Analytics\Http\Middleware\Analytics::class,
];
```

## Contributing

You're more that welcome to submit a Pull Request, or if you're not feeling up to it - post an issue and someone else may pick it up.

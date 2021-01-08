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

```php
Route::middleware('analytics')->group(function () {
    // ...
});
```

Add the page view to all middlewares/on an application level like so:

```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \Laravel\Analytics\Http\Middleware\Analytics::class,
];
```

## Contributing

You're more than welcome to submit a pull request, or if you're not feeling up to it - create an issue so someone else can pick it up.

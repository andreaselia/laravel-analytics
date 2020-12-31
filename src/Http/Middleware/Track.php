<?php

namespace Laravel\Analytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Track
{
    public function handle(Request $request, Closure $next)
    {
        // ...

        return $next($request);
    }
}

<?php

namespace Laravel\Telescope\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Telescope\Telescope;

class Track
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}

<?php

namespace Laravel\Analytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Analytics\Models\PageView;

class Analytics
{
    public function handle(Request $request, Closure $next)
    {
        $uri = str_replace($request->root(), '', $request->fullUrl()) ?: '/';

        $response = $next($request);

        if (\in_array($uri, config('analytics.exclude'))) {
            return $response;
        }

        PageView::create($data = [
            'ip_address' => $request->ip(),
            'uri' => $uri,
            'source' => 'todo',
            'country_code' => 'todo',
            'device_type' => 'todo',
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'payload' => $this->input($request),
            'session' => $request->hasSession() ? $request->session()->all() : [],
            'response_status' => $response->getStatusCode(),
        ]);

        return $response;
    }

    protected function input(Request $request): array
    {
        $files = $request->files->all();

        array_walk_recursive($files, function (&$file) {
            $file = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->isFile() ? ($file->getSize() / 1000).'KB' : '0',
            ];
        });

        return array_replace_recursive($request->input(), $files);
    }
}

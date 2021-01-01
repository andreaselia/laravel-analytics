<?php

namespace Laravel\Analytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Track
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = defined('LARAVEL_START') ? LARAVEL_START : $event->request->server('REQUEST_TIME_FLOAT');

        $response = $next($request);

        $data = [
            'ip_address' => $request->ip(),
            'uri' => str_replace($request->root(), '', $request->fullUrl()) ?: '/',
            'method' => $request->method(),
            'controller_action' => optional($request->route())->getActionName(),
            'headers' => $request->headers->all(),
            'payload' => $this->input($request),
            'session' => $this->sessionVariables($request),
            'response_status' => $response->getStatusCode(),
            'duration' => $startTime ? floor((microtime(true) - $startTime) * 1000) : null,
            'memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 1),
        ];

        dd($data);

        // ...

        return $response;
    }

    private function input(Request $request): array
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

    private function sessionVariables(Request $request): array
    {
        return $request->hasSession() ? $request->session()->all() : [];
    }
}

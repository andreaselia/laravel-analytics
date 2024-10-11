<?php

namespace AndreasElia\Analytics\Http\Middleware;

use AndreasElia\Analytics\Agent;
use AndreasElia\Analytics\Contracts\SessionProvider;
use AndreasElia\Analytics\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class Analytics
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! config('analytics.enabled')) {
            return $response;
        }

        if (in_array($request->method(), config('analytics.ignoreMethods', []))) {
            return $response;
        }

        if (in_array($request->ip(), config('analytics.ignoredIPs', []))) {
            return $response;
        }

        foreach (config('analytics.exclude', []) as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return $response;
            }
        }

        $agent = new Agent();
        if ($userAgent = $request->headers->get('user-agent')) {
            $agent->setUserAgent($userAgent);
        }
        $agent->setHttpHeaders($request->headers);

        if (config('analytics.ignoreRobots', false) && $agent->isRobot()) {
            return $response;
        }

        $uri = str_replace($request->root(), '', $request->url()) ?: '/';

        foreach (config('analytics.mask', []) as $mask) {
            $mask = trim($mask, '/');

            if ($request->fullUrlIs($mask) || $request->is($mask)) {
                $uri = '/'.str_replace('*', '∗︎', $mask);
                break;
            }
        }

        $attributes = [
            'session' => $this->getSessionProvider()->get($request),
            'uri' => $uri,
            'source' => $request->headers->get('referer'),
            'country' => $agent->languages()[0] ?? 'en-en',
            'browser' => $agent->browser() ?? null,
            'device' => $agent->deviceType(),
            'host' => $request->getHost(),
            ...array_map(
                fn ($item) => substr($item, 0, 255),
                $request->only([
                    'utm_source',
                    'utm_medium',
                    'utm_campaign',
                    'utm_term',
                    'utm_content',
                ])
            ),
        ];

        $this->getPageViewModel()::create(Arr::except($attributes, config('analytics.ignoredColumns', [])));

        return $response;
    }

    protected function input(Request $request): array
    {
        $files = $request->files->all();

        array_walk_recursive($files, function (&$file): void {
            $file = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->isFile() ? ($file->getSize() / 1000).'KB' : '0',
            ];
        });

        return array_replace_recursive($request->input(), $files);
    }

    private function getSessionProvider(): SessionProvider
    {
        return App::make(config('analytics.session.provider'));
    }
    private function getPageViewModel(): SessionProvider
    {
        return config('analytics.model');
    }
}

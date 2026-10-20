<?php

namespace AndreasElia\Analytics\Http\Middleware;

use AndreasElia\Analytics\Agent;
use AndreasElia\Analytics\Contracts\SessionProvider;
use AndreasElia\Analytics\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Throwable;

class Analytics
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $this->track($request);
        } catch (Throwable $e) {
            report($e);
        }

        return $response;
    }

    protected function track(Request $request): void
    {
        if (! config('analytics.enabled')) {
            return;
        }

        if (in_array($request->method(), config('analytics.ignoreMethods', []))) {
            return;
        }

        if (in_array($request->ip(), config('analytics.ignoredIPs', []))) {
            return;
        }

        foreach (config('analytics.exclude', []) as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return;
            }
        }

        $agent = new Agent();
        if ($userAgent = $request->headers->get('user-agent')) {
            $agent->setUserAgent($userAgent);
        }
        $agent->setHttpHeaders($request->server->all());

        if (config('analytics.ignoreRobots', false) && $agent->isRobot()) {
            return;
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
            'browser' => $agent->browser() ?: null,
            'device' => $agent->deviceType(),
            'host' => $request->getHost(),
            ...array_map(
                fn ($item) => substr($item, 0, 255),
                array_filter($request->only([
                    'utm_source',
                    'utm_medium',
                    'utm_campaign',
                    'utm_term',
                    'utm_content',
                ]), 'is_string')
            ),
        ];

        PageView::create(Arr::except($attributes, config('analytics.ignoredColumns', [])));
    }

    private function getSessionProvider(): SessionProvider
    {
        return App::make(config('analytics.session.provider'));
    }
}

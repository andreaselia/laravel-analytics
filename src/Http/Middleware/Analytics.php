<?php

namespace AndreasElia\Analytics\Http\Middleware;

use AndreasElia\Analytics\Contracts\SessionProvider;
use AndreasElia\Analytics\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Agent;

class Analytics
{
    public function handle(Request $request, Closure $next)
    {
        $uri = str_replace($request->root(), '', $request->url()) ?: '/';

        $response = $next($request);

        foreach (config('analytics.mask', []) as $mask) {
            $mask = trim($mask, '/');

            if ($request->fullUrlIs($mask) || $request->is($mask)) {
                $uri = '/'.str_replace('*', '∗︎', $mask);
                break;
            }
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
        $agent->setUserAgent($request->headers->get('user-agent'));
        $agent->setHttpHeaders($request->headers);

        PageView::create([
            'session' => $this->getSessionProvider()->get($request),
            'uri'     => $uri,
            'source'  => $request->headers->get('referer'),
            'country' => $agent->languages()[0] ?? 'en-en',
            'browser' => $agent->browser() ?? null,
            'device'  => $agent->deviceType(),
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

    private function getSessionProvider(): SessionProvider
    {
        return App::make(config('analytics.session.provider'));
    }
}

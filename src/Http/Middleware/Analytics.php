<?php

namespace AndreasElia\Analytics\Http\Middleware;

use AndreasElia\Analytics\Contracts\SessionIdProvider;
use Closure;
use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use AndreasElia\Analytics\Models\PageView;

class Analytics
{
    public function handle(Request $request, Closure $next)
    {
        $uri = str_replace($request->root(), '', $request->url()) ?: '/';

        $response = $next($request);

        if (in_array($uri, config('analytics.exclude', []))) {
            return $response;
        }

        $agent = new Agent();
        $agent->setUserAgent($request->headers->get('user-agent'));
        $agent->setHttpHeaders($request->headers);

        PageView::create([
            'session' => $this->getSessionIdProvider()->get($request),
            'uri' => $uri,
            'source' => $request->headers->get('referer'),
            'country' => $agent->languages()[0] ?? 'en-en',
            'browser' => $agent->browser() ?? null,
            'device' => $agent->deviceType(),
        ]);

        return $response;
    }

    protected function input(Request $request): array
    {
        $files = $request->files->all();

        array_walk_recursive($files, function (&$file) {
            $file = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->isFile() ? ($file->getSize() / 1000) . 'KB' : '0',
            ];
        });

        return array_replace_recursive($request->input(), $files);
    }

    private function getSessionIdProvider(): SessionIdProvider
    {
        return App::make(config('analytics.session_id_provider'));
    }
}

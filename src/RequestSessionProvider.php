<?php

namespace AndreasElia\Analytics;

use AndreasElia\Analytics\Contracts\SessionProvider;
use Illuminate\Http\Request;

class RequestSessionProvider implements SessionProvider
{
    public function get(Request $request): ?string
    {
        if (! $request->hasSession()) {
            return null;
        }

        return hash('sha256', $request->session()->getId());
    }
}

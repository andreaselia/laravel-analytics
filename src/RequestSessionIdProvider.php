<?php

namespace AndreasElia\Analytics;

use AndreasElia\Analytics\Contracts\SessionIdProvider;
use Illuminate\Http\Request;

class RequestSessionIdProvider implements SessionIdProvider
{
    public function get(Request $request): string
    {
        return $request->session()->getId();
    }
}

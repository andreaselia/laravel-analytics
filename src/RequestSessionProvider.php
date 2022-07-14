<?php

namespace AndreasElia\Analytics;

use AndreasElia\Analytics\Contracts\SessionProvider;
use Illuminate\Http\Request;

class RequestSessionProvider implements SessionProvider
{
    public function get(Request $request): string
    {
        return $request->session()->getId();
    }
}

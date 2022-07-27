<?php

namespace AndreasElia\Analytics\Contracts;

use Illuminate\Http\Request;

interface SessionProvider
{
    public function get(Request $request): string;
}

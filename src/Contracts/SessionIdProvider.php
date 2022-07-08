<?php

namespace AndreasElia\Analytics\Contracts;

use Illuminate\Http\Request;

interface SessionIdProvider
{
    public function get(Request $request): string;
}

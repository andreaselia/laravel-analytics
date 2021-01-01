<?php

use Laravel\Analytics\Http\Middleware\Track;

return [

    'prefix' => 'analytics',

    'middleware' => [
        'web',
        Track::class,
    ],

];

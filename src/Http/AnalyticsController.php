<?php

namespace Laravel\Analytics\Http\Controllers;

use Illuminate\Http\Response;
use Laravel\Analytics\Analytics;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index(): Response
    {
        return view('analytics::dashboard', [
            'pages' => Analytics::pages(),
        ]);
    }
}

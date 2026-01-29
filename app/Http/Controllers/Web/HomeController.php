<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\InternalApi;

class HomeController extends Controller
{
    public function index()
    {
        $response = InternalApi::request('GET', '/api/recipes');
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];

        return view('home', [
            'popular' => array_slice($recipes, 0, 3),
            'newest' => array_slice($recipes, 0, 2),
        ]);
    }
}

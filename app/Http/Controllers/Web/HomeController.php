<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InternalApi;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $response = InternalApi::request('GET', '/api/recipes');
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];
        $recipes = array_map(function ($recipe) {
            if (!empty($recipe['foto'])) {
                $recipe['foto_url'] = url('storage/'.$recipe['foto']);
            }
            return $recipe;
        }, $recipes);

        $popular = $recipes;
        usort($popular, function ($a, $b) {
            $aScore = (int) ($a['comments_count'] ?? 0) + (int) ($a['views_count'] ?? 0);
            $bScore = (int) ($b['comments_count'] ?? 0) + (int) ($b['views_count'] ?? 0);
            return $bScore <=> $aScore;
        });

        return view('home', [
            'popular' => array_slice($popular, 0, 20),
            'newest' => array_slice($recipes, 0, 10),
        ]);
    }
}

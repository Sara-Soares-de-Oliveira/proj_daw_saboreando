<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InternalApi;

class SearchController extends Controller
{
    public function results(Request $request)
    {
        $term = (string) $request->query('q', '');
        $response = InternalApi::request('GET', '/api/recipes');
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];
        $recipes = array_map(function ($recipe) {
            if (!empty($recipe['foto'])) {
                $recipe['foto_url'] = url('storage/'.$recipe['foto']);
            }
            return $recipe;
        }, $recipes);

        if ($term !== '') {
            $recipes = array_values(array_filter($recipes, function ($recipe) use ($term) {
                return stripos($recipe['titulo'] ?? '', $term) !== false;
            }));
        }

        return view('search.results', [
            'term' => $term,
            'recipes' => $recipes,
        ]);
    }
}

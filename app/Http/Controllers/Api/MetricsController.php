<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Recipe;
use App\Models\RecipeView;
use App\Models\Report;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function explorador(Request $request)
    {
        $user = $request->user();
        $recipeIds = Recipe::where('user_id', $user->id)->pluck('id');

        $data = [
            'recipes_total' => $recipeIds->count(),
            'recipes_pendentes' => Recipe::where('user_id', $user->id)->where('estado', 'pendente')->count(),
            'recipes_aprovadas' => Recipe::where('user_id', $user->id)->where('estado', 'aprovado')->count(),
            'recipes_rejeitadas' => Recipe::where('user_id', $user->id)->where('estado', 'rejeitado')->count(),
            'comments_made' => Comment::where('user_id', $user->id)->count(),
            'comments_received' => Comment::whereIn('recipe_id', $recipeIds)->count(),
            'views_total' => RecipeView::whereIn('recipe_id', $recipeIds)->count(),
            'top_recipes' => Recipe::where('user_id', $user->id)
                ->withCount(['comments', 'views'])
                ->orderByDesc('views_count')
                ->orderByDesc('comments_count')
                ->limit(5)
                ->get(['id', 'titulo', 'estado']),
        ];

        return response()->json($data);
    }

    public function moderador()
    {
        $data = [
            'recipes_total' => Recipe::count(),
            'recipes_pendentes' => Recipe::where('estado', 'pendente')->count(),
            'recipes_aprovadas' => Recipe::where('estado', 'aprovado')->count(),
            'recipes_rejeitadas' => Recipe::where('estado', 'rejeitado')->count(),
            'comments_total' => Comment::count(),
            'comments_removidos' => Comment::where('estado', 'removido')->count(),
            'reports_total' => Report::count(),
            'top_reported_comments' => Comment::withCount('reports')
                ->orderByDesc('reports_count')
                ->limit(5)
                ->get(['id', 'recipe_id', 'user_id', 'conteudo', 'estado']),
        ];

        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeView;
use Illuminate\Http\Request;

class RecipeViewController extends Controller
{
    public function start(Request $request, Recipe $recipe)
    {
        if ($recipe->estado !== 'aprovado') {
            return response()->json(['message' => 'Receita não disponível.'], 404);
        }

        $view = RecipeView::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'view_start' => now(),
        ]);

        return response()->json([
            'view_id' => $view->id,
        ], 201);
    }

    public function end(Request $request, Recipe $recipe)
    {
        $viewId = $request->input('view_id');
        if ($viewId) {
            $view = RecipeView::where('id', $viewId)
                ->where('user_id', $request->user()->id)
                ->whereNull('view_end')
                ->first();
        } else {
            $view = RecipeView::where('user_id', $request->user()->id)
                ->where('recipe_id', $recipe->id)
                ->whereNull('view_end')
                ->latest('view_start')
                ->first();
        }

        if (!$view) {
            return response()->json(['message' => 'View aberta não encontrada.'], 404);
        }

        $view->view_end = now();
        $seconds = $view->view_end->diffInSeconds($view->view_start, true);
        $view->duration_seconds = max(0, $seconds);
        $view->save();

        return response()->json([
            'view_id' => $view->id,
            'duration_seconds' => $view->duration_seconds,
        ]);
    }
}

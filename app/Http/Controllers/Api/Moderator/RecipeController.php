<?php

namespace App\Http\Controllers\Api\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado', 'pendente');

        if (!in_array($estado, ['pendente', 'aprovado', 'rejeitado'], true)) {
            return response()->json(['message' => 'Estado inválido.'], 422);
        }

        $recipes = Recipe::where('estado', $estado)
            ->withCount(['comments', 'views'])
            ->latest()
            ->get();

        return RecipeResource::collection($recipes);
    }

    public function approve(Request $request, Recipe $recipe)
    {
        $recipe->update(['estado' => 'aprovado']);

        UserActivity::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'action_type' => 'recipe_approved',
        ]);

        return new RecipeResource($recipe);
    }

    public function reject(Request $request, Recipe $recipe)
    {
        $recipe->update(['estado' => 'rejeitado']);

        UserActivity::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'action_type' => 'recipe_rejected',
        ]);

        return new RecipeResource($recipe);
    }
}

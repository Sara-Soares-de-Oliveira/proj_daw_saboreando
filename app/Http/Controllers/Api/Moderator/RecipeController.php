<?php

namespace App\Http\Controllers\Api\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
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

    public function approve(Recipe $recipe)
    {
        $recipe->update(['estado' => 'aprovado']);

        return new RecipeResource($recipe);
    }

    public function reject(Request $request, Recipe $recipe)
    {
        $recipe->update(['estado' => 'rejeitado']);

        return new RecipeResource($recipe);
    }
}

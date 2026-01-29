<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;

class PublicRecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::where('estado', 'aprovado')
            ->withCount(['comments', 'views'])
            ->latest()
            ->get();

        return RecipeResource::collection($recipes);
    }

    public function show(Recipe $recipe)
    {
        if ($recipe->estado !== 'aprovado') {
            return response()->json(['message' => 'Receita não disponível.'], 404);
        }

        $recipe->loadCount(['comments', 'views']);

        return new RecipeResource($recipe);
    }
}

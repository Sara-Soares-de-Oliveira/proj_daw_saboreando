<?php

namespace App\Http\Controllers\Api\Explorer;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $recipes = Recipe::where('user_id', $request->user()->id)
            ->withCount(['comments', 'views'])
            ->latest()
            ->get();

        return RecipeResource::collection($recipes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'ingredientes' => ['required', 'string'],
            'modo_preparo' => ['required', 'string'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto' => ['nullable', 'string', 'max:255'],
        ]);

        $recipe = Recipe::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'estado' => 'pendente',
        ]);

        return (new RecipeResource($recipe))
            ->response()
            ->setStatusCode(201);
    }
}

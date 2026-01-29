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

    public function update(Request $request, Recipe $recipe)
    {
        if ($recipe->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Sem permissão.'], 403);
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'ingredientes' => ['required', 'string'],
            'modo_preparo' => ['required', 'string'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto' => ['nullable', 'string', 'max:255'],
        ]);

        $recipe->update($validated);

        return new RecipeResource($recipe);
    }

    public function destroy(Request $request, Recipe $recipe)
    {
        if ($recipe->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Sem permissão.'], 403);
        }

        $recipe->delete();

        return response()->json(['message' => 'Receita removida.'], 204);
    }
}

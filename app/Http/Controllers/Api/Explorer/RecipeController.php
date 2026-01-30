<?php

namespace App\Http\Controllers\Api\Explorer;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Models\UserActivity;
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
            'ingredientes' => ['required'],
            'ingredientes.*' => ['nullable', 'string', 'max:255'],
            'modo_preparo' => ['required'],
            'modo_preparo.*' => ['nullable', 'string', 'max:255'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto' => ['nullable', 'string', 'max:255'],
        ]);

        $ingredientes = $validated['ingredientes'];
        if (is_array($ingredientes)) {
            $ingredientes = implode("\n", array_filter(array_map('trim', $ingredientes)));
        } else {
            $ingredientes = trim((string) $ingredientes);
        }
        $modoPreparo = $validated['modo_preparo'];
        if (is_array($modoPreparo)) {
            $modoPreparo = implode("\n", array_filter(array_map('trim', $modoPreparo)));
        } else {
            $modoPreparo = trim((string) $modoPreparo);
        }
        if ($ingredientes === '' || $modoPreparo === '') {
            return response()->json(['message' => 'Ingredientes e modo de preparo sao obrigatorios.'], 422);
        }

        $recipe = Recipe::create([
            ...$validated,
            'ingredientes' => $ingredientes,
            'modo_preparo' => $modoPreparo,
            'user_id' => $request->user()->id,
            'estado' => 'pendente',
        ]);

        UserActivity::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $recipe->id,
            'action_type' => 'recipe_created',
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
            'ingredientes' => ['required'],
            'ingredientes.*' => ['nullable', 'string', 'max:255'],
            'modo_preparo' => ['required'],
            'modo_preparo.*' => ['nullable', 'string', 'max:255'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto' => ['nullable', 'string', 'max:255'],
        ]);

        $ingredientes = $validated['ingredientes'];
        if (is_array($ingredientes)) {
            $ingredientes = implode("\n", array_filter(array_map('trim', $ingredientes)));
        } else {
            $ingredientes = trim((string) $ingredientes);
        }
        $modoPreparo = $validated['modo_preparo'];
        if (is_array($modoPreparo)) {
            $modoPreparo = implode("\n", array_filter(array_map('trim', $modoPreparo)));
        } else {
            $modoPreparo = trim((string) $modoPreparo);
        }
        if ($ingredientes === '' || $modoPreparo === '') {
            return response()->json(['message' => 'Ingredientes e modo de preparo sao obrigatorios.'], 422);
        }

        $recipe->update([
            ...$validated,
            'ingredientes' => $ingredientes,
            'modo_preparo' => $modoPreparo,
        ]);

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

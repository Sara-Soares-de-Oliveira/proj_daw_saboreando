<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Recipe;
use App\Services\InternalApi;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('GET', '/api/explorador/recipes', [], $token);
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];
        $recipes = array_map(function ($recipe) {
            if (!empty($recipe['foto'])) {
                $recipe['foto_url'] = url('storage/'.$recipe['foto']);
            }
            return $recipe;
        }, $recipes);
        $metricsResponse = InternalApi::request('GET', '/api/metrics/explorador', ['period' => 'week'], $token);
        $metrics = $metricsResponse['ok'] ? ($metricsResponse['json'] ?? []) : [];

        return view('recipes.index', [
            'recipes' => $recipes,
            'user' => $request->session()->get('user'),
            'metrics' => $metrics,
        ]);
    }

    public function create()
    {
        return view('recipes.create');
    }

    public function edit(Request $request, int $recipe)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('GET', '/api/explorador/recipes', [], $token);
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];

        $current = null;
        foreach ($recipes as $item) {
            if ((int) ($item['id'] ?? 0) === $recipe) {
                $current = $item;
                break;
            }
        }

        if (!$current) {
            return redirect('/minhas-receitas');
        }

        return view('recipes.edit', ['recipe' => $current]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'ingredientes' => ['required', 'array'],
            'ingredientes.*' => ['nullable', 'string', 'max:255'],
            'modo_preparo' => ['required', 'array'],
            'modo_preparo.*' => ['nullable', 'string', 'max:255'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $ingredientes = $validated['ingredientes'];
        if (is_array($ingredientes)) {
            $ingredientes = implode("\n", array_filter(array_map('trim', $ingredientes)));
        }
        $modoPreparo = $validated['modo_preparo'];
        if (is_array($modoPreparo)) {
            $modoPreparo = implode("\n", array_filter(array_map('trim', $modoPreparo)));
        }
        if ($ingredientes === '') {
            return back()->withErrors(['ingredientes' => 'Adicione pelo menos um ingrediente.'])->withInput();
        }
        if ($modoPreparo === '') {
            return back()->withErrors(['modo_preparo' => 'Adicione pelo menos um passo de preparo.'])->withInput();
        }

        $fotoPath = null;
        if ($request->hasFile('foto_file')) {
            $fotoPath = $request->file('foto_file')->store('recipes', 'public');
        }

        $token = $request->session()->get('api_token');
        $payload = [
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'ingredientes' => $ingredientes,
            'modo_preparo' => $modoPreparo,
            'dificuldade' => $validated['dificuldade'],
            'foto' => $fotoPath,
        ];
        $response = InternalApi::request('POST', '/api/explorador/recipes', $payload, $token);

        if (!$response['ok']) {
            $message = $response['json']['message'] ?? 'Não foi possível criar a receita.';
            if (!empty($response['json']['errors']) && is_array($response['json']['errors'])) {
                $first = collect($response['json']['errors'])->flatten()->first();
                if ($first) {
                    $message = $first;
                }
            }
            return back()->withErrors(['titulo' => $message])->withInput();
        }

        if ($fotoPath) {
            $recipeId = $response['json']['data']['id'] ?? null;
            if ($recipeId) {
                Recipe::where('id', $recipeId)->update(['foto' => $fotoPath]);
            }
        }

        return redirect('/minhas-receitas')->with('success', 'Receita enviada para validação.');
    }

    public function update(Request $request, int $recipe)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'ingredientes' => ['required', 'array'],
            'ingredientes.*' => ['nullable', 'string', 'max:255'],
            'modo_preparo' => ['required', 'array'],
            'modo_preparo.*' => ['nullable', 'string', 'max:255'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
            'current_foto' => ['nullable', 'string', 'max:255'],
        ]);

        $ingredientes = $validated['ingredientes'];
        if (is_array($ingredientes)) {
            $ingredientes = implode("\n", array_filter(array_map('trim', $ingredientes)));
        }
        $modoPreparo = $validated['modo_preparo'];
        if (is_array($modoPreparo)) {
            $modoPreparo = implode("\n", array_filter(array_map('trim', $modoPreparo)));
        }
        if ($ingredientes === '') {
            return back()->withErrors(['ingredientes' => 'Adicione pelo menos um ingrediente.'])->withInput();
        }
        if ($modoPreparo === '') {
            return back()->withErrors(['modo_preparo' => 'Adicione pelo menos um passo de preparo.'])->withInput();
        }

        $fotoPath = $validated['current_foto'] ?? null;
        if ($request->hasFile('foto_file')) {
            $fotoPath = $request->file('foto_file')->store('recipes', 'public');
        }

        $token = $request->session()->get('api_token');
        $payload = [
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'ingredientes' => $ingredientes,
            'modo_preparo' => $modoPreparo,
            'dificuldade' => $validated['dificuldade'],
            'foto' => $fotoPath,
        ];
        $response = InternalApi::request('PUT', "/api/explorador/recipes/{$recipe}", $payload, $token);

        if (!$response['ok']) {
            return back()->withErrors(['titulo' => 'Não foi possível atualizar a receita.'])->withInput();
        }

        if ($fotoPath) {
            Recipe::where('id', $recipe)->update(['foto' => $fotoPath]);
        }
        Recipe::where('id', $recipe)->update(['estado' => 'pendente']);

        return redirect('/minhas-receitas')->with('success', 'Receita atualizada.');
    }

    public function destroy(Request $request, int $recipe)
    {
        $token = $request->session()->get('api_token');
        InternalApi::request('DELETE', "/api/explorador/recipes/{$recipe}", [], $token);

        return redirect('/minhas-receitas')->with('success', 'Receita removida.');
    }

    public function show(Request $request, int $recipe)
    {
        $recipeResponse = InternalApi::request('GET', "/api/recipes/{$recipe}");
        $commentsResponse = InternalApi::request('GET', "/api/recipes/{$recipe}/comments");

        $recipeData = $recipeResponse['ok'] ? ($recipeResponse['json']['data'] ?? null) : null;
        if ($recipeData && !empty($recipeData['foto'])) {
            $recipeData['foto_url'] = url('storage/'.$recipeData['foto']);
        }

        $token = $request->session()->get('api_token');
        if ($token && $recipeData) {
            $prevViewId = $request->session()->get('open_view_id');
            $prevRecipeId = $request->session()->get('open_view_recipe_id');

            if ($prevViewId && $prevRecipeId) {
                InternalApi::request('PATCH', "/api/recipes/{$prevRecipeId}/view-end", [
                    'view_id' => $prevViewId,
                ], $token);
            }

            $startResponse = InternalApi::request('POST', "/api/recipes/{$recipe}/view-start", [], $token);
            $viewId = $startResponse['json']['view_id'] ?? null;
            if ($startResponse['ok'] && $viewId) {
                $request->session()->put('open_view_id', $viewId);
                $request->session()->put('open_view_recipe_id', $recipe);
            }
        }

        return view('recipes.show', [
            'recipe' => $recipeData,
            'comments' => $commentsResponse['ok'] ? ($commentsResponse['json']['data'] ?? []) : [],
            'canComment' => ($request->session()->get('user.role') === 'explorador'),
        ]);
    }
}

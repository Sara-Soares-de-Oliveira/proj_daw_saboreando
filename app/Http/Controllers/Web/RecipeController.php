<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\InternalApi;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->session()->get('api_token');
        $response = InternalApi::request('GET', '/api/explorador/recipes', [], $token);
        $recipes = $response['ok'] ? ($response['json']['data'] ?? []) : [];

        return view('recipes.index', [
            'recipes' => $recipes,
            'user' => $request->session()->get('user'),
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
            'ingredientes' => ['required', 'string'],
            'modo_preparo' => ['required', 'string'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_file')) {
            $fotoPath = $request->file('foto_file')->store('recipes', 'public');
        }

        $token = $request->session()->get('api_token');
        $payload = [
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'ingredientes' => $validated['ingredientes'],
            'modo_preparo' => $validated['modo_preparo'],
            'dificuldade' => $validated['dificuldade'],
            'foto' => $fotoPath,
        ];
        $response = InternalApi::request('POST', '/api/explorador/recipes', $payload, $token);

        if (!$response['ok']) {
            return back()->withErrors(['titulo' => 'Não foi possível criar a receita.'])->withInput();
        }

        return redirect('/minhas-receitas')->with('success', 'Receita enviada para validação.');
    }

    public function update(Request $request, int $recipe)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'ingredientes' => ['required', 'string'],
            'modo_preparo' => ['required', 'string'],
            'dificuldade' => ['required', 'in:facil,medio,dificil'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
            'current_foto' => ['nullable', 'string', 'max:255'],
        ]);

        $fotoPath = $validated['current_foto'] ?? null;
        if ($request->hasFile('foto_file')) {
            $fotoPath = $request->file('foto_file')->store('recipes', 'public');
        }

        $token = $request->session()->get('api_token');
        $payload = [
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'ingredientes' => $validated['ingredientes'],
            'modo_preparo' => $validated['modo_preparo'],
            'dificuldade' => $validated['dificuldade'],
            'foto' => $fotoPath,
        ];
        $response = InternalApi::request('PUT', "/api/explorador/recipes/{$recipe}", $payload, $token);

        if (!$response['ok']) {
            return back()->withErrors(['titulo' => 'Não foi possível atualizar a receita.'])->withInput();
        }

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
            $recipeData['foto_url'] = Storage::url($recipeData['foto']);
        }

        return view('recipes.show', [
            'recipe' => $recipeData,
            'comments' => $commentsResponse['ok'] ? ($commentsResponse['json']['data'] ?? []) : [],
            'canComment' => ($request->session()->get('user.role') === 'explorador'),
        ]);
    }
}

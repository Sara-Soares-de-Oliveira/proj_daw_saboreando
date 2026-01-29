<?php

namespace App\Http\Controllers\Api\Explorer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Recipe $recipe)
    {
        if ($recipe->estado !== 'aprovado') {
            return response()->json(['message' => 'Receita não disponível.'], 403);
        }

        $validated = $request->validate([
            'conteudo' => ['required', 'string', 'max:2000'],
        ]);

        $comment = Comment::create([
            'recipe_id' => $recipe->id,
            'user_id' => $request->user()->id,
            'conteudo' => $validated['conteudo'],
            'estado' => 'ativo',
        ]);

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(201);
    }
}

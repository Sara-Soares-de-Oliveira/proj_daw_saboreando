<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Recipe;

class PublicCommentController extends Controller
{
    public function index(Recipe $recipe)
    {
        if ($recipe->estado !== 'aprovado') {
            return response()->json(['message' => 'Receita não disponível.'], 404);
        }

        $comments = Comment::where('recipe_id', $recipe->id)
            ->where('estado', 'ativo')
            ->with('user')
            ->latest()
            ->get();

        return CommentResource::collection($comments);
    }
}

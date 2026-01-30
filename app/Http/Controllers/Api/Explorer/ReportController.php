<?php

namespace App\Http\Controllers\Api\Explorer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Comment;
use App\Models\Report;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, Comment $comment)
    {
        if ($comment->estado !== 'ativo') {
            return response()->json(['message' => 'Comentário não disponível.'], 403);
        }

        $validated = $request->validate([
            'motivo' => ['required', 'string', 'max:500'],
        ]);

        $report = Report::create([
            'comment_id' => $comment->id,
            'user_id' => $request->user()->id,
            'motivo' => $validated['motivo'],
        ]);

        UserActivity::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $comment->recipe_id,
            'action_type' => 'report_created',
        ]);

        return (new ReportResource($report))
            ->response()
            ->setStatusCode(201);
    }
}

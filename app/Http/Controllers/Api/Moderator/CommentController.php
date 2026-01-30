<?php

namespace App\Http\Controllers\Api\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Report;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function remove(Request $request, Comment $comment)
    {
        $comment->update(['estado' => 'removido']);
        Report::where('comment_id', $comment->id)->delete();

        UserActivity::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $comment->recipe_id,
            'action_type' => 'comment_removed',
        ]);

        return new CommentResource($comment);
    }

    public function keep(Request $request, Comment $comment)
    {
        $comment->update(['estado' => 'ativo']);
        Report::where('comment_id', $comment->id)->delete();

        UserActivity::create([
            'user_id' => $request->user()->id,
            'recipe_id' => $comment->recipe_id,
            'action_type' => 'comment_kept',
        ]);

        return new CommentResource($comment);
    }
}

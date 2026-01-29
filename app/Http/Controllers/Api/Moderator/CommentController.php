<?php

namespace App\Http\Controllers\Api\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Report;

class CommentController extends Controller
{
    public function remove(Comment $comment)
    {
        $comment->update(['estado' => 'removido']);
        Report::where('comment_id', $comment->id)->delete();

        return new CommentResource($comment);
    }

    public function keep(Comment $comment)
    {
        $comment->update(['estado' => 'ativo']);
        Report::where('comment_id', $comment->id)->delete();

        return new CommentResource($comment);
    }
}

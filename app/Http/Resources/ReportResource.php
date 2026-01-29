<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment_id' => $this->comment_id,
            'user_id' => $this->user_id,
            'motivo' => $this->motivo,
            'comment' => new CommentResource($this->whenLoaded('comment')),
            'reporter' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }
}

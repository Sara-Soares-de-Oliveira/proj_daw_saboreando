<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ingredientes' => $this->ingredientes,
            'modo_preparo' => $this->modo_preparo,
            'dificuldade' => $this->dificuldade,
            'foto' => $this->foto,
            'estado' => $this->estado,
            'comments_count' => $this->whenCounted('comments'),
            'views_count' => $this->whenCounted('views'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'view_start',
        'view_end',
        'duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'view_start' => 'datetime',
            'view_end' => 'datetime',
            'duration_seconds' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}

<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titulo' => fake()->sentence(4),
            'descricao' => fake()->paragraph(),
            'ingredientes' => fake()->paragraph(),
            'modo_preparo' => fake()->paragraph(),
            'dificuldade' => fake()->randomElement(['facil', 'medio', 'dificil']),
            'foto' => null,
            'estado' => fake()->randomElement(['pendente', 'aprovado', 'rejeitado']),
        ];
    }
}

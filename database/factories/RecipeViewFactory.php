<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeView;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeView>
 */
class RecipeViewFactory extends Factory
{
    protected $model = RecipeView::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-7 days', 'now');
        $duration = fake()->numberBetween(5, 1800);
        $end = (clone $start)->modify('+' . $duration . ' seconds');

        return [
            'user_id' => User::factory(),
            'recipe_id' => Recipe::factory(),
            'view_start' => $start,
            'view_end' => $end,
            'duration_seconds' => $duration,
        ];
    }
}

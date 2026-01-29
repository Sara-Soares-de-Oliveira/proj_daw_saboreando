<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserActivity>
 */
class UserActivityFactory extends Factory
{
    protected $model = UserActivity::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipe_id' => fake()->boolean(60) ? Recipe::factory() : null,
            'action_type' => fake()->randomElement([
                'pesquisar',
                'ver_receita',
                'comentar',
                'criar_receita',
                'denunciar',
            ]),
        ];
    }
}

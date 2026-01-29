<?php

namespace Database\Factories;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoginSession>
 */
class LoginSessionFactory extends Factory
{
    protected $model = LoginSession::class;

    public function definition(): array
    {
        $login = fake()->dateTimeBetween('-7 days', 'now');
        $duration = fake()->numberBetween(60, 14400);
        $logout = (clone $login)->modify('+' . $duration . ' seconds');

        return [
            'user_id' => User::factory(),
            'login_at' => $login,
            'logout_at' => $logout,
            'duration_seconds' => $duration,
        ];
    }
}

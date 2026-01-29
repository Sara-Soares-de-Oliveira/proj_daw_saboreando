<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\LoginSession;
use App\Models\Recipe;
use App\Models\RecipeView;
use App\Models\Report;
use App\Models\UserActivity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $moderador = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'moderador',
        ]);

        $explorador = User::factory()->create([
            'name' => 'Explorador User',
            'email' => 'explorador@example.com',
            'role' => 'explorador',
        ]);

        Recipe::factory()->count(10)->create([
            'user_id' => $moderador->id,
            'estado' => 'aprovado',
        ]);

        Comment::factory()->count(20)->create();
        Report::factory()->count(5)->create();
        UserActivity::factory()->count(30)->create([
            'user_id' => $explorador->id,
        ]);
        RecipeView::factory()->count(15)->create([
            'user_id' => $explorador->id,
        ]);
        LoginSession::factory()->count(5)->create([
            'user_id' => $explorador->id,
        ]);
    }
}

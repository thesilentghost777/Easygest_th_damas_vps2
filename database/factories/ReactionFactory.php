<?php

namespace Database\Factories;

use App\Models\Reaction;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReactionFactory extends Factory
{
    protected $model = Reaction::class;

    public function definition(): array
    {
        return [
            'announcement_id' => Announcement::factory(), // Crée automatiquement une annonce
            'user_id' => User::factory(),                 // Crée automatiquement un utilisateur
            'comment' => $this->faker->sentence,
        ];
    }
}

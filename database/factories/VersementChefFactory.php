<?php

namespace Database\Factories;

use App\Models\VersementChef;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VersementChefFactory extends Factory
{
    protected $model = VersementChef::class;

    public function definition(): array
    {
        return [
            'verseur' => User::factory(),
            'libelle' => $this->faker->sentence(4),
            'montant' => $this->faker->numberBetween(5000, 500000),
            'status' => $this->faker->boolean(60),
            'date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Evenement;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvenementFactory extends Factory
{
    protected $model = Evenement::class;

    public function definition(): array
    {
        return [
            'libelle' => $this->faker->paragraph(),
            'date' => $this->faker->dateTimeBetween('-1 year', '+1 month')->format('Y-m-d'),
        ];
    }
}

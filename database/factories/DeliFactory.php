<?php

namespace Database\Factories;

use App\Models\Deli;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliFactory extends Factory
{
    protected $model = Deli::class;

    public function definition(): array
    {
        return [
            "nom" => $this->faker->word(),
            "description" => $this->faker->paragraph(),
            "montant" => $this->faker->randomFloat(2, 1000, 100000),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\SoldeCP;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SoldeCP>
 */
class SoldeCPFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SoldeCP::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'montant' => fake()->randomFloat(2, 0, 100000),
            'derniere_mise_a_jour' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
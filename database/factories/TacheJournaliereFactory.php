<?php

namespace Database\Factories;

use App\Models\TacheJournaliere;
use App\Models\User;
use App\Models\Tache;
use App\Models\Investissement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TacheJournaliere>
 */
class TacheJournaliereFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TacheJournaliere::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statut = fake()->randomElement(['en_attente', 'completée']);
        
        return [
            'user_id' => User::factory(),
            'tache_id' => Tache::factory(),
            'investissement_id' => fake()->optional()->passthrough(Investissement::factory()),
            'date' => fake()->dateTimeBetween('-1 month', '+1 week')->format('Y-m-d'),
            'statut' => $statut,
            'date_realisation' => $statut === 'completée' ? fake()->dateTime() : null,
            'remuneration' => fake()->randomFloat(2, 500, 5000),
        ];
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'completée',
            'date_realisation' => fake()->dateTimeBetween($attributes['date'], 'now'),
        ]);
    }

    /**
     * Indicate that the task is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_attente',
            'date_realisation' => null,
        ]);
    }
}
<?php

namespace Database\Factories;

use App\Models\Planning;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanningFactory extends Factory
{
    protected $model = Planning::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(["tache", "repos"]);
        
        return [
            "libelle" => $this->faker->sentence(3),
            "employe" => User::factory(),
            "type" => $type,
            "date" => $this->faker->date(),
            "heure_debut" => $type === "tache" ? $this->faker->time() : null,
            "heure_fin" => $type === "tache" ? $this->faker->time() : null,
        ];
    }

    public function tache(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "tache",
            "heure_debut" => $this->faker->time(),
            "heure_fin" => $this->faker->time(),
        ]);
    }

    public function repos(): static
    {
        return $this->state(fn (array $attributes) => [
            "type" => "repos",
            "heure_debut" => null,
            "heure_fin" => null,
        ]);
    }
}

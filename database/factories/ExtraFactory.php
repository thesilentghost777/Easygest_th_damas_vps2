<?php

namespace Database\Factories;

use App\Models\Extra;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtraFactory extends Factory
{
    protected $model = Extra::class;

    public function definition(): array
    {
        return [
            "secteur" => $this->faker->randomElement(["vente", "production", "administration", "logistique", "securite"]),
            "heure_arriver_adequat" => $this->faker->time("H:i:s", "09:00:00"),
            "heure_depart_adequat" => $this->faker->time("H:i:s", "18:00:00"),
            "salaire_adequat" => $this->faker->randomFloat(2, 50000, 500000),
            "interdit" => $this->faker->optional()->paragraph(),
            "regles" => $this->faker->optional()->paragraph(),
            "age_adequat" => $this->faker->numberBetween(18, 65),
        ];
    }
}

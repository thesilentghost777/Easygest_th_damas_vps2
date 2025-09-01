<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->randomElement([
                "sac", "salaire", "as", "materiel", "reparation", 
                "matiere_premiere", "transport", "supermarche", "vente", "autre", "versement","production","glace"
            ]),
        ];
    }
}

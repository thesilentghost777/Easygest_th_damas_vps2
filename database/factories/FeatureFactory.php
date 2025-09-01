<?php

namespace Database\Factories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    public function definition(): array
    {
        $categories = ['all_employees', 'producers', 'sellers', 'cashiers', 'production_manager', 'structure'];
        
        return [
            'code' => $this->faker->unique()->slug(2),
            'name' => $this->faker->words(2, true),
            'category' => $this->faker->randomElement($categories),
            'description' => $this->faker->paragraph(),
            'active' => $this->faker->boolean(85),
        ];
    }
}

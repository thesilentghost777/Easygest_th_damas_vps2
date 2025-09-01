<?php

namespace Database\Factories;

use App\Models\ProductGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductGroupFactory extends Factory
{
    protected $model = ProductGroup::class;

    public function definition(): array
    {
        return [
            "name" => $this->faker->words(2, true),
            "description" => $this->faker->optional()->paragraph(),
            "user_id" => User::factory(),
        ];
    }
}

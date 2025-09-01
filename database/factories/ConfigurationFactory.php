<?php

namespace Database\Factories;

use App\Models\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigurationFactory extends Factory
{
    protected $model = Configuration::class;

    public function definition(): array
    {
        return [
            "first_config" => $this->faker->boolean(),
            "flag1" => $this->faker->boolean(),
            "flag2" => $this->faker->boolean(),
            "flag3" => $this->faker->boolean(),
            "flag4" => $this->faker->boolean(),
        ];
    }
}

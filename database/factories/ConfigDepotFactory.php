<?php

namespace Database\Factories;

use App\Models\ConfigDepot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConfigDepot>
 */
class ConfigDepotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConfigDepot::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero_depot_mtn' => fake()->numerify('######'),
            'numero_depot_orange' => fake()->numerify('######'),
            'lien_video_youtube' => fake()->url(),
            'lien_video_tiktok' => fake()->url(),
            'code_admin' => fake()->regexify('[A-Z0-9]{8}'),
        ];
    }
}
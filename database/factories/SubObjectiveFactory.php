<?php

namespace Database\Factories;

use App\Models\SubObjective;
use App\Models\Objective;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubObjective>
 */
class SubObjectiveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubObjective::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $targetAmount = $this->faker->randomFloat(2, 1000, 50000);
        $currentAmount = $this->faker->randomFloat(2, 0, $targetAmount);
        $progressPercentage = $targetAmount > 0 ? round(($currentAmount / $targetAmount) * 100, 2) : 0;

        return [
            'objective_id' => Objective::factory(),
            'product_id' => $this->faker->optional(0.7)->randomNumber(5), // 70% de chance d'avoir un product_id
            'title' => $this->faker->sentence(3),
            'target_amount' => $targetAmount,
            'current_amount' => $currentAmount,
            'progress_percentage' => min($progressPercentage, 100), // Limite à 100%
        ];
    }

    /**
     * Créer un sous-objectif sans produit associé (générique)
     */
    public function generic(): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => null,
            'title' => 'Sous-objectif générique - ' . $this->faker->words(2, true),
        ]);
    }

    /**
     * Créer un sous-objectif avec un produit spécifique
     */
    public function withProduct(int $productId): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $productId,
            'title' => 'Objectif produit - ' . $this->faker->words(2, true),
        ]);
    }

    /**
     * Créer un sous-objectif complété (100%)
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $targetAmount = $this->faker->randomFloat(2, 1000, 50000);
            return [
                'target_amount' => $targetAmount,
                'current_amount' => $targetAmount,
                'progress_percentage' => 100.00,
                'title' => 'Objectif complété - ' . $this->faker->words(2, true),
            ];
        });
    }

    /**
     * Créer un sous-objectif en cours (progression partielle)
     */
    public function inProgress(): static
    {
        return $this->state(function (array $attributes) {
            $targetAmount = $this->faker->randomFloat(2, 5000, 50000);
            $progressPercentage = $this->faker->randomFloat(2, 10, 85);
            $currentAmount = ($targetAmount * $progressPercentage) / 100;
            
            return [
                'target_amount' => $targetAmount,
                'current_amount' => round($currentAmount, 2),
                'progress_percentage' => $progressPercentage,
                'title' => 'Objectif en cours - ' . $this->faker->words(2, true),
            ];
        });
    }

    /**
     * Créer un sous-objectif juste commencé (faible progression)
     */
    public function justStarted(): static
    {
        return $this->state(function (array $attributes) {
            $targetAmount = $this->faker->randomFloat(2, 10000, 100000);
            $progressPercentage = $this->faker->randomFloat(2, 0, 15);
            $currentAmount = ($targetAmount * $progressPercentage) / 100;
            
            return [
                'target_amount' => $targetAmount,
                'current_amount' => round($currentAmount, 2),
                'progress_percentage' => $progressPercentage,
                'title' => 'Nouvel objectif - ' . $this->faker->words(2, true),
            ];
        });
    }

    /**
     * Créer un sous-objectif avec un objectif parent spécifique
     */
    public function forObjective(int $objectiveId): static
    {
        return $this->state(fn (array $attributes) => [
            'objective_id' => $objectiveId,
        ]);
    }

    /**
     * Créer un sous-objectif avec un montant cible spécifique
     */
    public function withTargetAmount(float $amount): static
    {
        return $this->state(function (array $attributes) use ($amount) {
            $currentAmount = $this->faker->randomFloat(2, 0, $amount);
            $progressPercentage = $amount > 0 ? round(($currentAmount / $amount) * 100, 2) : 0;
            
            return [
                'target_amount' => $amount,
                'current_amount' => $currentAmount,
                'progress_percentage' => min($progressPercentage, 100),
            ];
        });
    }
}
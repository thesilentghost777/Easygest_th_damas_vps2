<?php

namespace Database\Factories;

use App\Models\Objective;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Objective>
 */
class ObjectiveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Objective::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sectors = ['alimentation', 'boulangerie-patisserie', 'glace', 'global'];
        $periodTypes = ['daily', 'weekly', 'monthly', 'yearly'];
        $goalTypes = ['revenue', 'profit'];
        
        $startDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $endDate = Carbon::parse($startDate)->addMonths($this->faker->numberBetween(1, 12));

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'target_amount' => $this->faker->randomFloat(2, 10000, 1000000),
            'period_type' => $this->faker->randomElement($periodTypes),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sector' => $this->faker->randomElement($sectors),
            'goal_type' => $this->faker->randomElement($goalTypes),
            'expense_categories' => $this->faker->randomElements([1, 2, 3, 4, 5, 6, 7, 8], $this->faker->numberBetween(0, 3)),
            'use_standard_sources' => $this->faker->boolean(70),
            'custom_users' => $this->faker->randomElements([1, 2, 3, 4, 5], $this->faker->numberBetween(0, 2)),
            'custom_categories' => $this->faker->randomElements([1, 2, 3, 4, 5], $this->faker->numberBetween(0, 2)),
            'is_active' => $this->faker->boolean(80),
            'is_achieved' => $this->faker->boolean(20),
            'is_confirmed' => $this->faker->boolean(60),
        ];
    }

    /**
     * Indicate that the objective is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the objective is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the objective is achieved.
     */
    public function achieved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_achieved' => true,
            'is_confirmed' => true,
        ]);
    }

    /**
     * Indicate that the objective is not achieved.
     */
    public function notAchieved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_achieved' => false,
        ]);
    }

    /**
     * Indicate that the objective is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_confirmed' => true,
        ]);
    }

    /**
     * Indicate that the objective is not confirmed.
     */
    public function notConfirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_confirmed' => false,
        ]);
    }

    /**
     * Create an objective for alimentation sector.
     */
    public function alimentation(): static
    {
        return $this->state(fn (array $attributes) => [
            'sector' => 'alimentation',
            'use_standard_sources' => true,
        ]);
    }

    /**
     * Create an objective for boulangerie-patisserie sector.
     */
    public function boulangerie(): static
    {
        return $this->state(fn (array $attributes) => [
            'sector' => 'boulangerie-patisserie',
            'use_standard_sources' => true,
        ]);
    }

    /**
     * Create an objective for glace sector.
     */
    public function glace(): static
    {
        return $this->state(fn (array $attributes) => [
            'sector' => 'glace',
            'use_standard_sources' => true,
        ]);
    }

    /**
     * Create an objective for global sector.
     */
    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'sector' => 'global',
            'use_standard_sources' => true,
        ]);
    }

    /**
     * Create a revenue-based objective.
     */
    public function revenue(): static
    {
        return $this->state(fn (array $attributes) => [
            'goal_type' => 'revenue',
        ]);
    }

    /**
     * Create a profit-based objective.
     */
    public function profit(): static
    {
        return $this->state(fn (array $attributes) => [
            'goal_type' => 'profit',
        ]);
    }

    /**
     * Create a monthly objective.
     */
    public function monthly(): static
    {
        return $this->state(fn (array $attributes) => [
            'period_type' => 'monthly',
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->endOfMonth(),
        ]);
    }

    /**
     * Create a yearly objective.
     */
    public function yearly(): static
    {
        return $this->state(fn (array $attributes) => [
            'period_type' => 'yearly',
            'start_date' => Carbon::now()->startOfYear(),
            'end_date' => Carbon::now()->endOfYear(),
        ]);
    }

    /**
     * Create a weekly objective.
     */
    public function weekly(): static
    {
        return $this->state(fn (array $attributes) => [
            'period_type' => 'weekly',
            'start_date' => Carbon::now()->startOfWeek(),
            'end_date' => Carbon::now()->endOfWeek(),
        ]);
    }

    /**
     * Create a daily objective.
     */
    public function daily(): static
    {
        return $this->state(fn (array $attributes) => [
            'period_type' => 'daily',
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today(),
        ]);
    }

    /**
     * Create an objective with high target amount.
     */
    public function highTarget(): static
    {
        return $this->state(fn (array $attributes) => [
            'target_amount' => $this->faker->randomFloat(2, 500000, 2000000),
        ]);
    }

    /**
     * Create an objective with low target amount.
     */
    public function lowTarget(): static
    {
        return $this->state(fn (array $attributes) => [
            'target_amount' => $this->faker->randomFloat(2, 10000, 100000),
        ]);
    }

    /**
     * Create an objective with custom sources (not using standard sources).
     */
    public function customSources(): static
    {
        return $this->state(fn (array $attributes) => [
            'use_standard_sources' => false,
            'custom_users' => $this->faker->randomElements([1, 2, 3, 4, 5], $this->faker->numberBetween(1, 3)),
            'custom_categories' => $this->faker->randomElements([1, 2, 3, 4, 5], $this->faker->numberBetween(1, 3)),
        ]);
    }

    /**
     * Create an objective with no custom data.
     */
    public function standardSources(): static
    {
        return $this->state(fn (array $attributes) => [
            'use_standard_sources' => true,
            'custom_users' => null,
            'custom_categories' => null,
            'expense_categories' => null,
        ]);
    }
}
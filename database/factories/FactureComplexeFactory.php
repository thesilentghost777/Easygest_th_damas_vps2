<?php

namespace Database\Factories;

use App\Models\FactureComplexe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FactureComplexe>
 */
class FactureComplexeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FactureComplexe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateCreation = $this->faker->dateTimeBetween('-6 months', 'now');
        $dateValidation = $this->faker->optional(0.7)->dateTimeBetween($dateCreation, 'now');
        
        return [
            'reference' => $this->generateReference(),
            'producteur_id' => User::factory(),
            'id_lot' => 'LOT' . $this->faker->unique()->numberBetween(1000, 9999),
            'montant_total' => $this->faker->randomFloat(2, 100, 10000),
            'statut' => $this->faker->randomElement(['en_attente', 'validee', 'payee', 'annulee']),
            'date_creation' => $dateCreation,
            'date_validation' => $dateValidation,
            'notes' => $this->faker->optional(0.6)->sentence(),
        ];
    }

    /**
     * Générer une référence unique pour la factory
     */
    private function generateReference(): string
    {
        $prefix = 'FC-';
        $dateCode = date('Ymd', strtotime($this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d')));
        $sequence = str_pad($this->faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
        
        return $prefix . $dateCode . '-' . $sequence;
    }

    /**
     * Facture en attente
     */
    public function enAttente(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_attente',
            'date_validation' => null,
        ]);
    }

    /**
     * Facture validée
     */
    public function validee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'validee',
            'date_validation' => $this->faker->dateTimeBetween($attributes['date_creation'], 'now'),
        ]);
    }

    /**
     * Facture payée
     */
    public function payee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'payee',
            'date_validation' => $this->faker->dateTimeBetween($attributes['date_creation'], 'now'),
        ]);
    }

    /**
     * Facture annulée
     */
    public function annulee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'annulee',
            'date_validation' => null,
        ]);
    }

    /**
     * Facture avec montant élevé
     */
    public function montantEleve(): static
    {
        return $this->state(fn (array $attributes) => [
            'montant_total' => $this->faker->randomFloat(2, 5000, 50000),
        ]);
    }

    /**
     * Facture avec montant faible
     */
    public function montantFaible(): static
    {
        return $this->state(fn (array $attributes) => [
            'montant_total' => $this->faker->randomFloat(2, 10, 500),
        ]);
    }

    /**
     * Facture récente (derniers 30 jours)
     */
    public function recente(): static
    {
        $dateCreation = $this->faker->dateTimeBetween('-30 days', 'now');
        
        return $this->state(fn (array $attributes) => [
            'date_creation' => $dateCreation,
            'date_validation' => $this->faker->optional(0.8)->dateTimeBetween($dateCreation, 'now'),
        ]);
    }

    /**
     * Facture ancienne (plus de 6 mois)
     */
    public function ancienne(): static
    {
        $dateCreation = $this->faker->dateTimeBetween('-2 years', '-6 months');
        
        return $this->state(fn (array $attributes) => [
            'date_creation' => $dateCreation,
            'date_validation' => $this->faker->optional(0.9)->dateTimeBetween($dateCreation, '-6 months'),
        ]);
    }

    /**
     * Facture avec notes détaillées
     */
    public function avecNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $this->faker->paragraph(3),
        ]);
    }

    /**
     * Facture pour un producteur spécifique
     */
    public function pourProducteur(User $producteur): static
    {
        return $this->state(fn (array $attributes) => [
            'producteur_id' => $producteur->id,
        ]);
    }

    /**
     * Facture avec référence personnalisée
     */
    public function avecReference(string $reference): static
    {
        return $this->state(fn (array $attributes) => [
            'reference' => $reference,
        ]);
    }
}
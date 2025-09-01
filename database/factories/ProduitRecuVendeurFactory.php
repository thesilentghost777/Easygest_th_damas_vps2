<?php

namespace Database\Factories;

use App\Models\ProduitRecuVendeur;
use App\Models\ProduitRecu1;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitRecuVendeurFactory extends Factory
{
    protected $model = ProduitRecuVendeur::class;

    public function definition(): array
    {
        $quantiteRecue = $this->faker->numberBetween(10, 200);
        $quantiteConfirmee = $this->faker->numberBetween(0, $quantiteRecue);

        return [
            'produit_recu_id' => ProduitRecu1::factory(),
            'vendeur_id' => User::factory(),
            'quantite_recue' => $quantiteRecue,
            'quantite_confirmee' => $quantiteConfirmee,
            'status' => $this->faker->randomElement(['en_attente', 'confirmé', 'rejeté']),
            'remarques' => $this->faker->optional()->sentence(),
        ];
    }

    public function enAttente(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'en_attente',
            'quantite_confirmee' => 0,
        ]);
    }

    public function confirme(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmé',
            'quantite_confirmee' => $attributes['quantite_recue'] ?? $this->faker->numberBetween(10, 200),
        ]);
    }

    public function rejete(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejeté',
            'quantite_confirmee' => 0,
            'remarques' => 'Produit rejeté - ' . $this->faker->sentence(),
        ]);
    }
}

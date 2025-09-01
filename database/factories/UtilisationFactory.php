<?php
namespace Database\Factories;

use App\Models\Utilisation;
use App\Models\Matiere;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UtilisationFactory extends Factory
{
    protected $model = Utilisation::class;

    public function definition(): array
    {
        // Générer un ID de lot qui peut être partagé entre plusieurs utilisations
        $id_lot = 'LOT-' . $this->faker->dateTimeBetween('-1 year', 'now')->format('Ymd') . '-' . $this->faker->randomNumber(4, true);
        
        // Récupérer une matière existante pour obtenir son unité
        $matiere = Matiere::inRandomOrder()->first();
        $unite_matiere = $matiere ? $matiere->unite_minimale : 'kg';
        $um = $unite_matiere->toString();
        // Générer une quantité de matière cohérente avec l'unité
        $quantite_matiere = $this->genererQuantiteSelonUnite($um);

        return [
            'id_lot' => $id_lot,
            'produit' => Produit_fixes::inRandomOrder()->first()?->code_produit ?? 1,
            'matierep' => $matiere?->id ?? Matiere::factory()->create()->id,
            'producteur' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'quantite_produit' => $this->faker->randomFloat(2, 1, 100),
            'quantite_matiere' => $quantite_matiere,
            'unite_matiere' => $unite_matiere,
        ];
    }

    /**
     * Générer une quantité appropriée selon l'unité
     */
    private function genererQuantiteSelonUnite(string $unite): float
    {
        switch ($unite) {
            case 'kg':
                return $this->faker->randomFloat(3, 0.1, 50); // 0.1 à 50 kg
            case 'g':
                return $this->faker->randomFloat(3, 1, 5000); // 1 à 5000 g
            case 'L':
                return $this->faker->randomFloat(3, 0.1, 20); // 0.1 à 20 L
            case 'mL':
                return $this->faker->randomFloat(3, 10, 2000); // 10 à 2000 mL
            case 'pièce':
                return $this->faker->numberBetween(1, 100); // 1 à 100 pièces
            default:
                return $this->faker->randomFloat(3, 0.1, 100);
        }
    }

    /**
     * Créer plusieurs utilisations pour le même lot de production
     */
    public function pourMemeLot(string $id_lot): static
    {
        return $this->state(function (array $attributes) use ($id_lot) {
            return [
                'id_lot' => $id_lot,
            ];
        });
    }

    /**
     * Créer un lot de production complet avec plusieurs matières
     */
    public function lotComplet(int $nombre_matieres = null): \Illuminate\Database\Eloquent\Collection
    {
        $nombre_matieres = $nombre_matieres ?? $this->faker->numberBetween(2, 5);
        $id_lot = 'LOT-' . $this->faker->dateTimeBetween('-1 year', 'now')->format('Ymd') . '-' . $this->faker->randomNumber(4, true);
        
        return collect(range(1, $nombre_matieres))->map(function () use ($id_lot) {
            return $this->pourMemeLot($id_lot)->create();
        });
    }
}
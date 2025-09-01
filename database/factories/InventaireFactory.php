<?php

namespace Database\Factories;

use App\Models\Inventaire;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventaireFactory extends Factory
{
    protected $model = Inventaire::class;

    public function definition(): array
    {
        $quantiteTheorique = $this->faker->numberBetween(0, 1000);
        $quantitePhysique = $this->faker->numberBetween(0, $quantiteTheorique + 50);
        $prixUnitaire = $this->faker->randomFloat(2, 100, 10000);
        $valeurManquant = abs($quantiteTheorique - $quantitePhysique) * $prixUnitaire;
        
        return [
            "date_inventaire" => $this->faker->date(),
            "produit_id" => Produit::factory(),
            "quantite_theorique" => $quantiteTheorique,
            "quantite_physique" => $quantitePhysique,
            "valeur_manquant" => $valeurManquant,
            "user_id" => User::factory(),
            "commentaire" => $this->faker->optional()->paragraph(),
        ];
    }
}

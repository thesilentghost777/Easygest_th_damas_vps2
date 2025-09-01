<?php
// MatiereFactory corrigé
namespace Database\Factories;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatiereFactory extends Factory
{
    protected $model = Matiere::class;

    public function definition(): array
    {
        // Définir des groupes d'unités cohérentes
        $groupes_unites = [
            'poids' => ['kg', 'g'],
            'volume' => ['l', 'ml'],
            'piece' => ['unite', 'unite'] // Même unité pour les deux
        ];

        // Choisir un groupe d'unités
        $groupe = $this->faker->randomElement(['poids', 'volume', 'piece']);
        $unites_groupe = $groupes_unites[$groupe];
        
        // Assigner les unités du même groupe
        $unite_minimale = $unites_groupe[1]; // Unité plus petite
        $unite_classique = $unites_groupe[0]; // Unité plus grande

        // Définir des ratios de conversion cohérents
        $ratios_conversion = [
            'kg_g' => 1000,     // 1 kg = 1000 g
            'L_mL' => 1000,     // 1 L = 1000 mL
            'piece_piece' => 1   // 1 pièce = 1 pièce
        ];

        // Déterminer le ratio de conversion selon le groupe
        if ($groupe === 'poids') {
            $quantite_par_unite = $ratios_conversion['kg_g'];
        } elseif ($groupe === 'volume') {
            $quantite_par_unite = $ratios_conversion['L_mL'];
        } else {
            $quantite_par_unite = $ratios_conversion['piece_piece'];
        }

        // Générer des valeurs cohérentes selon le type d'unité
        if ($groupe === 'piece') {
            $quantite = $this->faker->numberBetween(1, 1000); // Nombre entier pour les pièces
            $prix_unitaire = $this->faker->randomFloat(2, 1, 100); // Prix par pièce
        } else {
            $quantite = $this->faker->randomFloat(2, 0.1, 500); // Quantité en unité classique
            $prix_unitaire = $this->faker->randomFloat(2, 0.5, 50); // Prix par unité classique
        }

        return [
            'nom' => $this->faker->word() . ' ' . $this->faker->word(),
            'unite_minimale' => $unite_minimale,
            'unite_classique' => $unite_classique,
            'quantite_par_unite' => $quantite_par_unite,
            'quantite' => $quantite,
            'prix_unitaire' => $prix_unitaire,
            'prix_par_unite_minimale' => $prix_unitaire / $quantite_par_unite,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Produit_fixes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFixesFactory extends Factory
{
    protected $model = Produit_fixes::class;

    public function definition(): array
    {
        $produits = [
            // Boulangerie
            ['nom' => 'Baguette', 'prix' => 150, 'categorie' => 'Boulangerie'],
            ['nom' => 'Pain complet', 'prix' => 300, 'categorie' => 'Boulangerie'],
            ['nom' => 'Pain de mie', 'prix' => 500, 'categorie' => 'Boulangerie'],
            ['nom' => 'Petits pains au lait', 'prix' => 100, 'categorie' => 'Boulangerie'],
            ['nom' => 'Pain sandwich', 'prix' => 400, 'categorie' => 'Boulangerie'],

            // Viennoiserie
            ['nom' => 'Croissant', 'prix' => 300, 'categorie' => 'Viennoiserie'],
            ['nom' => 'Pain au chocolat', 'prix' => 350, 'categorie' => 'Viennoiserie'],
            ['nom' => 'Chausson aux pommes', 'prix' => 400, 'categorie' => 'Viennoiserie'],
            ['nom' => 'Beignet haricot', 'prix' => 100, 'categorie' => 'Viennoiserie'],

            // Pâtisserie
            ['nom' => 'Tarte aux fruits', 'prix' => 1000, 'categorie' => 'Pâtisserie'],
            ['nom' => 'Mille-feuille', 'prix' => 1200, 'categorie' => 'Pâtisserie'],
            ['nom' => 'Éclair au chocolat', 'prix' => 800, 'categorie' => 'Pâtisserie'],
            ['nom' => 'Saint-Honoré', 'prix' => 1500, 'categorie' => 'Pâtisserie'],
            ['nom' => 'Forêt Noire', 'prix' => 1800, 'categorie' => 'Pâtisserie'],

            // Snacking / Street Food
            ['nom' => 'Croquette viande', 'prix' => 300, 'categorie' => 'Snacking'],
            ['nom' => 'Pizza individuelle', 'prix' => 1500, 'categorie' => 'Snacking'],
            ['nom' => 'Sandwich jambon', 'prix' => 1200, 'categorie' => 'Snacking'],

            // Gâteaux événementiels
            ['nom' => 'Gâteau anniversaire 1kg', 'prix' => 7000, 'categorie' => 'Gâteau événementiel'],
            ['nom' => 'Gâteau mariage 3 étages', 'prix' => 30000, 'categorie' => 'Gâteau événementiel'],
        ];

        $produit = $this->faker->randomElement($produits);

        return [
            'nom' => $produit['nom'],
            'prix' => $produit['prix'],
            'categorie' => $produit['categorie'],
        ];
    }
}

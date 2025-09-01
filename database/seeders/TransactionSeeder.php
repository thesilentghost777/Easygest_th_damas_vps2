<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Récupérer toutes les catégories existantes
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée. Veuillez d\'abord exécuter les migrations.');
            return;
        }

        // Définir la période : 6 mois de 2025 (janvier à juin)
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 6, 30);
        
        // Générer 100 transactions
        for ($i = 0; $i < 100; $i++) {
            $type = fake()->randomElement(['income', 'outcome']);
            
            // Date aléatoire dans les 6 premiers mois de 2025
            $date = Carbon::create(
                2025,
                fake()->numberBetween(1, 6), // Mois entre janvier et juin
                fake()->numberBetween(1, 28), // Jour (28 pour éviter les problèmes de fin de mois)
                fake()->numberBetween(8, 18), // Heure entre 8h et 18h
                fake()->numberBetween(0, 59)  // Minutes
            );

            // Montants réalistes selon le type de transaction
            if ($type === 'income') {
                $amount = fake()->randomFloat(2, 1000, 15000); // Revenus entre 1000 et 15000
            } else {
                $amount = fake()->randomFloat(2, 100, 8000); // Dépenses entre 100 et 8000
            }

            // Descriptions contextuelles selon la catégorie
            $category = $categories->random();
            $description = $this->generateDescription($category->name, $type);

            Transaction::create([
                'type' => $type,
                'category_id' => $category->id,
                'amount' => $amount,
                'date' => $date,
                'description' => $description,
            ]);
        }

        $this->command->info('100 transactions générées avec succès pour les 6 premiers mois de 2025.');
    }

    /**
     * Générer une description contextuelle selon la catégorie
     */
    private function generateDescription($categoryName, $type)
    {
        $descriptions = [
            'Versement' => [
                'income' => ['Versement client', 'Paiement facture', 'Règlement commande', 'Encaissement vente'],
                'outcome' => ['Versement fournisseur', 'Acompte commande', 'Remboursement client']
            ],
            'matiere_premiere' => [
                'income' => ['Retour matière première', 'Remboursement fournisseur'],
                'outcome' => ['Achat matières premières', 'Stock matières', 'Approvisionnement production']
            ],
            'commande' => [
                'income' => ['Encaissement commande', 'Paiement commande client', 'Facture commande'],
                'outcome' => ['Commande fournisseur', 'Achat équipement', 'Commande matériaux']
            ],
            'reparation_materiel' => [
                'income' => ['Remboursement assurance réparation'],
                'outcome' => ['Réparation équipement', 'Maintenance matériel', 'Service après-vente']
            ],
            'fiscalite' => [
                'income' => ['Remboursement TVA', 'Crédit d\'impôt'],
                'outcome' => ['Paiement TVA', 'Impôts sur sociétés', 'Taxes diverses']
            ],
            'salaires_charges_sociales' => [
                'income' => ['Remboursement URSSAF'],
                'outcome' => ['Salaires employés', 'Charges sociales', 'Cotisations URSSAF']
            ],
            'loyer_charges_locatives' => [
                'income' => ['Remboursement charges'],
                'outcome' => ['Loyer bureau', 'Charges copropriété', 'Entretien locaux']
            ],
            'eau_electricite_gaz_telecoms' => [
                'income' => ['Remboursement consommation'],
                'outcome' => ['Facture électricité', 'Abonnement télécom', 'Consommation eau', 'Facture gaz']
            ],
            'marketing_communication' => [
                'income' => ['Remboursement publicité'],
                'outcome' => ['Campagne publicitaire', 'Communication digitale', 'Marketing direct']
            ],
            'transport_livraison' => [
                'income' => ['Facturation livraison'],
                'outcome' => ['Frais de transport', 'Livraison commande', 'Déplacement professionnel']
            ],
            'fournitures_entretien' => [
                'income' => ['Retour fournitures'],
                'outcome' => ['Fournitures bureau', 'Produits d\'entretien', 'Matériel nettoyage']
            ],
            'frais_bancaires_services' => [
                'income' => ['Remboursement frais'],
                'outcome' => ['Frais bancaires', 'Commission carte', 'Services financiers']
            ],
            'assurances' => [
                'income' => ['Indemnité assurance'],
                'outcome' => ['Prime assurance', 'Cotisation assurance', 'Assurance responsabilité']
            ],
            'motivation_stagiaire' => [
                'income' => ['Aide formation'],
                'outcome' => ['Gratification stagiaire', 'Formation employés', 'Séminaire motivation']
            ],
            'evenementiel' => [
                'income' => ['Participation événement'],
                'outcome' => ['Organisation événement', 'Salon professionnel', 'Conférence']
            ]
        ];

        $categoryDescriptions = $descriptions[$categoryName] ?? [
            'income' => ['Recette diverse', 'Encaissement'],
            'outcome' => ['Dépense diverse', 'Paiement']
        ];

        $typeDescriptions = $categoryDescriptions[$type] ?? ['Transaction'];
        
        return fake()->randomElement($typeDescriptions) . ' - ' . fake()->sentence(3);
    }
}
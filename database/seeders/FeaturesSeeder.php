<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fonctionnalités pour tous les employés
        $allEmployeesFeatures = [
            ['code' => 'temp_missing_items', 'name' => 'Manquant temporaire', 'category' => 'all_employees'],
            ['code' => 'prime', 'name' => 'prime', 'category' => 'all_employees'],
            ['code' => 'loans', 'name' => 'Effectuer un prêt', 'category' => 'all_employees'],
            ['code' => 'daily_rations', 'name' => 'Ration journalière', 'category' => 'all_employees'],
            ['code' => 'salary_advances', 'name' => 'Gérer les avances salaire', 'category' => 'all_employees'],
            ['code' => 'payslips_salary', 'name' => 'Fiche de paie et salaire', 'category' => 'all_employees'],
            ['code' => 'messages_suggestions', 'name' => 'Messages et suggestions', 'category' => 'all_employees'],
        ];

        // Fonctionnalités pour les producteurs
        $producersFeatures = [
            ['code' => 'reserve_raw_materials', 'name' => 'Réserver les matières premières', 'category' => 'producers'],
            ['code' => 'complex_invoices', 'name' => 'Factures du complexe', 'category' => 'producers'],
            ['code' => 'materials_recovery', 'name' => 'Récupération des matières', 'category' => 'producers'],
            ['code' => 'personal_production_stats', 'name' => 'Statistiques personnelles de production', 'category' => 'producers'],
            ['code' => 'production_details', 'name' => 'Détails des productions', 'category' => 'producers'],
            ['code' => 'producers_ranking', 'name' => 'Classement des producteurs', 'category' => 'producers'],
        ];

        // Fonctionnalités pour les vendeuses
        $sellersFeatures = [
            ['code' => 'payments', 'name' => 'Versement', 'category' => 'sellers'],
            ['code' => 'sales_statistics', 'name' => 'Statistiques des ventes', 'category' => 'sellers'],
            ['code' => 'sales_details', 'name' => 'Détails des ventes', 'category' => 'sellers'],
        ];

        // Fonctionnalités pour les caissières
        $cashiersFeatures = [
            ['code' => 'cashier_payments', 'name' => 'Versement', 'category' => 'cashiers'],
            ['code' => 'cashier_transactions', 'name' => 'Gérer les transactions de la caisse', 'category' => 'cashiers'],
        ];

        // Fonctionnalités pour le chef de production
        $productionManagerFeatures = [
            ['code' => 'validate_payment', 'name' => 'Valider un versement', 'category' => 'production_manager'],
            ['code' => 'view_production_stats', 'name' => 'Voir statistique production', 'category' => 'production_manager'],
            ['code' => 'view_sales_stats', 'name' => 'Voir statistique vente', 'category' => 'production_manager'],
            ['code' => 'view_product_stats', 'name' => 'Voir statistique produit', 'category' => 'production_manager'],
            ['code' => 'view_producer_stats', 'name' => 'Voir statistique producteur', 'category' => 'production_manager'],
            ['code' => 'access_employee_account', 'name' => 'Accès au compte employé', 'category' => 'production_manager'],
            ['code' => 'access_producer_mode', 'name' => 'Access au mode producteur', 'category' => 'production_manager'],
            ['code' => 'view_other_stats', 'name' => 'Voir autre statistique', 'category' => 'production_manager'],
        ];

        // Fonctionnalités pour la structure
        $structureFeatures = [
            ['code' => 'manage_bags', 'name' => 'Gérer les sacs', 'category' => 'structure'],
            ['code' => 'sherlock_advisor', 'name' => 'Sherlock conseiller', 'category' => 'structure'],
            ['code' => 'sherlock_copilot', 'name' => 'Sherlock copilot', 'category' => 'structure'],
            ['code' => 'sherlock_recipe', 'name' => 'Sherlock recette', 'category' => 'structure'],
        ];

        // Combiner toutes les fonctionnalités
        $allFeatures = array_merge(
            $allEmployeesFeatures,
            $producersFeatures,
            $sellersFeatures,
            $cashiersFeatures,
            $productionManagerFeatures,
            $structureFeatures
        );

        // Insérer toutes les fonctionnalités
        foreach ($allFeatures as $feature) {
            Feature::updateOrCreate(
                ['code' => $feature['code']],
                [
                    'name' => $feature['name'],
                    'category' => $feature['category'],
                    'active' => true, // Par défaut toutes les fonctionnalités sont actives
                ]
            );
        }
    }
}

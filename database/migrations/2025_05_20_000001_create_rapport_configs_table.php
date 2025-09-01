<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rapport_configs', function (Blueprint $table) {
            $table->id();
            $table->json('production_categories')->nullable(); // Catégories de dépenses pour le secteur production
            $table->json('alimentation_categories')->nullable(); // Catégories de dépenses pour le secteur alimentation
            $table->json('production_users')->nullable(); // Utilisateurs dont les versements constituent les gains pour production
            $table->json('alimentation_users')->nullable(); // Utilisateurs dont les versements constituent les gains pour alimentation
            $table->json('social_climat')->nullable(); // Informations sur le climat social
            $table->json('major_problems')->nullable(); // Problèmes majeurs rencontrés
            $table->json('recommendations')->nullable(); // Recommandations des employés ou clients
            $table->decimal('tax_rate', 5, 2)->default(0); // Taux d'imposition
            $table->decimal('vat_rate', 5, 2)->default(18); // Taux de TVA (18% par défaut)
            
            // Modules d'analyse à activer pour Sherlock
            $table->boolean('analyze_product_performance')->default(true); // Analyser les performances des produits
            $table->boolean('analyze_waste')->default(true); // Analyser le gaspillage
            $table->boolean('analyze_sales_discrepancies')->default(true); // Analyser les écarts de vente
            $table->boolean('analyze_employee_performance')->default(true); // Analyser les performances des employés
            $table->boolean('analyze_theft_detection')->default(true); // Analyser les détections de vol
            $table->boolean('analyze_material_usage')->default(true); // Analyser l'utilisation des matières premières
            $table->boolean('analyze_spoilage')->default(true); // Analyser les avaries
            $table->boolean('analyze_objectives')->default(true); // Analyser les objectifs
            $table->boolean('analyze_hr_data')->default(true); // Analyser les données RH
            $table->boolean('analyze_orders')->default(true); // Analyser les commandes
            $table->boolean('analyze_market_trends')->default(true); // Analyser les tendances du marché
            $table->boolean('analyze_event_impact')->default(true); // Analyser l'impact des événements
            $table->boolean('analyze_ice_cream_sector')->default(true); // Analyser le secteur des glaces
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_configs');
    }
};

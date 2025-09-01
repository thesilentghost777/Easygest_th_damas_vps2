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
        Schema::create('objectives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur (DG ou CP)
            $table->string('title'); // Titre de l'objectif
            $table->text('description')->nullable(); // Description optionnelle
            $table->decimal('target_amount', 15, 2); // Montant cible
            $table->enum('period_type', ['daily', 'weekly', 'monthly', 'yearly']); // Type de période
            $table->date('start_date'); // Date de début
            $table->date('end_date'); // Date de fin
            $table->enum('sector', ['alimentation', 'boulangerie-patisserie', 'glace', 'global']); // Secteur concerné
            $table->enum('goal_type', ['revenue', 'profit']); // Type d'objectif (CA ou bénéfice)
            $table->json('expense_categories')->nullable(); // Catégories de dépenses associées (plusieurs possibles)
            $table->boolean('use_standard_sources')->default(true); // Utiliser les sources standard ou personnalisées
            $table->json('custom_users')->nullable(); // IDs des utilisateurs personnalisés pour sources
            $table->json('custom_categories')->nullable(); // IDs des catégories de transactions entrantes personnalisées
            $table->boolean('is_active')->default(true); // Actif ou non
            $table->boolean('is_achieved')->default(false); // Atteint ou non
            $table->boolean('is_confirmed')->default(false); // Confirmé ou non
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::create('objective_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('objective_id');
            $table->date('date');
            $table->decimal('current_amount', 15, 2); // Montant actuel
            $table->decimal('expenses', 15, 2)->default(0); // Dépenses
            $table->decimal('profit', 15, 2)->default(0); // Bénéfices
            $table->decimal('progress_percentage', 5, 2); // Pourcentage de progression
            $table->json('transactions')->nullable(); // Liste des transactions/versements liés (IDs)
            $table->timestamps();
            
            $table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
        });
        
        // Table pour les sous-objectifs par produit
        Schema::create('sub_objectives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('objective_id'); // Objectif parent
            $table->unsignedBigInteger('product_id')->nullable(); // ID du produit (Produit_fixes)
            $table->string('title'); // Titre du sous-objectif
            $table->decimal('target_amount', 15, 2); // Montant cible du sous-objectif
            $table->decimal('current_amount', 15, 2)->default(0); // Montant actuel
            $table->decimal('progress_percentage', 5, 2)->default(0); // Pourcentage de progression
            $table->timestamps();
            
            $table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
            // Pas de contrainte sur product_id car pourrait être NULL pour des sous-objectifs génériques
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_objectives');
        Schema::dropIfExists('objective_progress');
        Schema::dropIfExists('objectives');
    }
};

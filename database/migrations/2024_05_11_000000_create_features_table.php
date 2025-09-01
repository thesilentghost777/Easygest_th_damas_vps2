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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Code unique pour identifier la fonctionnalité
            $table->string('name'); // Nom descriptif de la fonctionnalité
            $table->string('category'); // Catégorie: all_employees, producers, sellers, cashiers, production_manager, structure
            $table->text('description')->nullable(); // Description détaillée
            $table->boolean('active')->default(true); // Statut d'activation (true par défaut)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};

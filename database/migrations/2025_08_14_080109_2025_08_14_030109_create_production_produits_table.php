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
        Schema::create('production_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_sac_id')->constrained('productions_sacs')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('Produit_fixes', 'code_produit')->onDelete('cascade');
            $table->integer('quantite');
            $table->decimal('valeur_unitaire', 10, 2);
            $table->decimal('valeur_totale', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_produits');
    }
};
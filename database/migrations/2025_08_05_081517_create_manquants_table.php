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
        Schema::create('manquants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->date('date_calcul');
            $table->decimal('manquant_producteur_pointeur', 10, 2)->default(0); // Différence production - pointage
            $table->decimal('manquant_pointeur_vendeur', 10, 2)->default(0);    // Différence pointage - réception vendeur
            $table->decimal('manquant_vendeur_invendu', 10, 2)->default(0);     // Différence invendu veille - reste récupéré
            $table->decimal('montant_producteur', 10, 2)->default(0);           // 0
            $table->decimal('montant_pointeur', 10, 2)->default(0);             // 100% manquant prod-pointeur
            $table->decimal('montant_vendeur', 10, 2)->default(0);              // Manquant invendu en FCFA + 100% pointeur-vendeur
            $table->json('details_producteurs')->nullable();                    // Liste des producteurs concernés
            $table->json('details_pointeurs')->nullable();                      // Liste des pointeurs concernés
            $table->json('details_vendeurs')->nullable();                       // Liste des vendeurs concernés
            $table->timestamps();

            $table->foreign('produit_id')->references('code_produit')->on('Produit_fixes');
            $table->unique(['produit_id', 'date_calcul']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

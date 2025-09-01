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
        Schema::create('flux_journaliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->date('date_flux');
            $table->decimal('total_production', 10, 2)->default(0);
            $table->decimal('total_pointage', 10, 2)->default(0);
            $table->decimal('total_reception_vendeur', 10, 2)->default(0);
            $table->json('detail_productions')->nullable();  // Détails des productions
            $table->json('detail_pointages')->nullable();    // Détails des pointages
            $table->json('detail_receptions')->nullable();   // Détails des réceptions vendeurs
            $table->timestamps();

            $table->foreign('produit_id')->references('code_produit')->on('Produit_fixes');
            $table->unique(['produit_id', 'date_flux']);
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

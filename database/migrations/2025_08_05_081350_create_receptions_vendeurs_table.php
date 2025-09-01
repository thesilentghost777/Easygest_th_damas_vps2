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
        Schema::create('receptions_vendeurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendeur_id');
            $table->unsignedBigInteger('produit_id');
            $table->decimal('quantite_entree_matin', 10, 2)->nullable()->default(0);
            $table->decimal('quantite_entree_journee', 10, 2)->nullable()->default(0);
            $table->decimal('quantite_invendue', 10, 2)->nullable()->default(0);
            $table->decimal('quantite_reste_hier', 10, 2)->nullable()->default(0);
            $table->date('date_reception');
            $table->timestamps();

            $table->foreign('vendeur_id')->references('id')->on('users');
            $table->foreign('produit_id')->references('code_produit')->on('Produit_fixes');
            $table->index(['date_reception', 'produit_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptions_vendeurs');
    }
};

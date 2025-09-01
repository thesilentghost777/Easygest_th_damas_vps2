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
        Schema::create('receptions_pointeurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pointeur_id');
            $table->unsignedBigInteger('produit_id');
            $table->decimal('quantite_recue', 10, 2);
            $table->date('date_reception');
            $table->timestamps();

            $table->foreign('pointeur_id')->references('id')->on('users');
            $table->foreign('produit_id')->references('code_produit')->on('Produit_fixes');
            $table->index(['date_reception', 'produit_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptions_pointeurs');
    }
};

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
        Schema::create('produits_recu_vendeur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_recu_id');
            $table->unsignedBigInteger('vendeur_id');
            $table->integer('quantite_recue');
            $table->integer('quantite_confirmee')->nullable();
            $table->enum('status', ['en_attente', 'confirmé', 'rejeté'])->default('en_attente');
            $table->text('remarques')->nullable();
            $table->timestamps();
            
            $table->foreign('produit_recu_id')->references('id')->on('produits_recu_1')->onDelete('cascade');
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits_recu_vendeur');
    }
};
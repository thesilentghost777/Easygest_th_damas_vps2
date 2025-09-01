<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_avaries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('avaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID du pointeur
            $table->unsignedBigInteger('produit_id'); // ID du produit avarié
            $table->integer('quantite'); // Quantité avariée
            $table->decimal('montant_total', 10, 2); // Montant total de l'avarie
            $table->text('description')->nullable(); // Description de l'avarie
            $table->date('date_avarie'); // Date de l'avarie
            $table->timestamps();
            
            // Clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('produit_id')->references('code_produit')->on('Produit_fixes')->onDelete('cascade');
            
            // Index pour optimiser les requêtes
            $table->index(['user_id', 'date_avarie']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('avaries');
    }
};
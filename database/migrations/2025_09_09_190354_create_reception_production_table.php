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
        Schema::create('reception_production', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code_produit');
            $table->integer('quantite');
            $table->date('date_reception');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('code_produit')->references('code_produit')->on('Produit_fixes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reception_production');
    }
};
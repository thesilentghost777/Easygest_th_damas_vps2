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
        Schema::create('productions_sacs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sac_id')->constrained('sacs')->onDelete('cascade');
            $table->foreignId('producteur_id')->constrained('users')->onDelete('cascade');
            $table->decimal('valeur_totale_fcfa', 10, 2)->default(0);
            $table->boolean('valide')->default(false);
            $table->text('observations')->nullable();
            $table->date('date_production');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions_sacs');
    }
};
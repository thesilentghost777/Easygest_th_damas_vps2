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
        Schema::create('sac_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sac_id')->constrained('sacs')->onDelete('cascade');
            $table->decimal('valeur_moyenne_fcfa', 10, 2);
            $table->text('notes')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sac_configurations');
    }
};
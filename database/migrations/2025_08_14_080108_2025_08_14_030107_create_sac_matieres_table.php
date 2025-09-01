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
        Schema::create('sac_matieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sac_id')->constrained('sacs')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('Matiere')->onDelete('cascade');
            $table->decimal('quantite_utilisee', 10, 3);
            $table->timestamps();
            
            $table->unique(['sac_id', 'matiere_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sac_matieres');
    }
};
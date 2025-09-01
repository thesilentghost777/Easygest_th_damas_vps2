<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignation_retours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignation_id')->constrained('assignations_matiere');
            $table->foreignId('producteur_id')->constrained('users');
            $table->foreignId('matiere_id')->constrained('Matiere');
            $table->decimal('quantite_retournee', 10, 3);
            $table->string('unite_retour');
            $table->decimal('quantite_stock_incrementee', 10, 3)->comment('Quantité ajoutée au stock en unités');
            $table->text('motif_retour')->nullable();
            $table->enum('statut', ['en_attente', 'validee', 'refusee'])->default('en_attente');
            $table->foreignId('validee_par')->nullable()->constrained('users');
            $table->timestamp('date_validation')->nullable();
            $table->text('commentaire_validation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignation_retours');
    }
};

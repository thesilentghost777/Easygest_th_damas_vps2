<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('Versement_chef', function (Blueprint $table) {
            $table->unsignedBigInteger('code_vc')->autoIncrement();
            $table->unsignedBigInteger('verseur');
            $table->string('libelle', 255);
            $table->integer('montant');
            $table->boolean('status')->default(0); // 0: En attente, 1: Validé
            $table->date('date');
            $table->foreign('verseur')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('Versement_chef');
    }
};

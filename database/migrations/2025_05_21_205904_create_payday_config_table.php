<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payday_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_day')->comment('Jour du mois pour les salaires')->default(25);
            $table->integer('advance_day')->comment('Jour du mois pour les avances sur salaires')->default(15);
            $table->timestamps();
        });
        
        // Insérer une configuration par défaut
        DB::table('payday_configs')->insert([
            'salary_day' => 7,
            'advance_day' => 20,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payday_configs');
    }
};

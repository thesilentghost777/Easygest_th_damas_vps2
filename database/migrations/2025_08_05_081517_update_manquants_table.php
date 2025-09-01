<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateManquantsTable extends Migration
{
    public function up()
    {
        Schema::table('manquants', function (Blueprint $table) {
            $table->decimal('incoherence_producteur_pointeur', 10, 2)->default(0)->after('manquant_producteur_pointeur');
            $table->decimal('incoherence_pointeur_vendeur', 10, 2)->default(0)->after('manquant_pointeur_vendeur');
        });
    }

    public function down()
    {
        Schema::table('manquants', function (Blueprint $table) {
            $table->dropColumn(['incoherence_producteur_pointeur', 'incoherence_pointeur_vendeur']);
        });
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('Matiere', function (Blueprint $table) {
            $table->decimal('quantite_seuil', 10, 2)->default(0)->after('quantite');
            $table->boolean('notification_active')->default(true)->after('quantite_seuil');
        });
    }

    public function down()
    {
        Schema::table('Matiere', function (Blueprint $table) {
            $table->dropColumn('quantite_seuil');
            $table->dropColumn('notification_active');
        });
    }
};
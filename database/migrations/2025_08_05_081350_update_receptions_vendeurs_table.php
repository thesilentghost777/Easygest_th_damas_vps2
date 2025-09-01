<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReceptionsVendeursTable extends Migration
{
    public function up()
    {
        Schema::table('receptions_vendeurs', function (Blueprint $table) {
            $table->decimal('quantite_avarie', 10, 2)->nullable()->default(0)->after('quantite_reste_hier');
        });
    }

    public function down()
    {
        Schema::table('receptions_vendeurs', function (Blueprint $table) {
            $table->dropColumn('quantite_avarie');
        });
    }
}
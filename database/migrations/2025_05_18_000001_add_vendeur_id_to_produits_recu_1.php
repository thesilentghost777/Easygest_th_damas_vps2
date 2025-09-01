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
        Schema::table('produits_recu_1', function (Blueprint $table) {
            $table->unsignedBigInteger('vendeur_id')->nullable()->after('pointeur_id');
            $table->foreign('vendeur_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits_recu_1', function (Blueprint $table) {
            $table->dropForeign(['vendeur_id']);
            $table->dropColumn('vendeur_id');
        });
    }
};

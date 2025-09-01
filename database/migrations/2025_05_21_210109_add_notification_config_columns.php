<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('processed')->default(false)->after('read_at');
            $table->timestamp('renew_at')->nullable()->after('processed');
            $table->integer('renew_days')->nullable()->after('renew_at');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('processed');
            $table->dropColumn('renew_at');
            $table->dropColumn('renew_days');
        });
    }
};

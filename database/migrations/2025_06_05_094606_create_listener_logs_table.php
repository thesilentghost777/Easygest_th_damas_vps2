<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('listener_logs', function (Blueprint $table) {
            $table->id();
            $table->string('listener_name');
            $table->enum('status', ['success', 'failed', 'skipped']);
            $table->text('message');
            $table->json('details')->nullable();
            $table->datetime('executed_at');
            $table->float('execution_time')->nullable(); // en secondes
            $table->timestamps();
            
            $table->index(['listener_name', 'executed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('listener_logs');
    }
};

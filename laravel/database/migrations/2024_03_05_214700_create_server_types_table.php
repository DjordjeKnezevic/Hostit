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
        Schema::create('server_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('cpu_cores');
            $table->integer('ram');
            $table->integer('storage');
            $table->integer('network_speed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_types');
    }
};

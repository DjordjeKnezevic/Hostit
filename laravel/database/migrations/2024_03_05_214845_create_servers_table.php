<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('server_type_id');
            $table->unsignedBigInteger('location_id');
            $table->timestamps();

            $table->foreign('server_type_id')->references('id')->on('server_types')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unique(['location_id', 'server_type_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('servers');
    }
}

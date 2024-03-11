<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionResourcesTable extends Migration
{
    public function up()
    {
        Schema::create('region_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->integer('total_cpu_cores');
            $table->integer('remaining_cpu_cores');
            $table->integer('total_ram');
            $table->integer('remaining_ram');
            $table->integer('total_storage');
            $table->integer('remaining_storage');
            $table->integer('total_bandwidth');
            $table->integer('remaining_bandwidth');
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('region_resources');
    }
}

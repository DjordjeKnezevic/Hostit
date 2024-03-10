<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricingTable extends Migration
{
    public function up()
    {
        Schema::create('pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->string('service_type');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->enum('period', ['hourly', 'monthly', 'yearly']);
            $table->timestamp('valid_from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();

            $table->index('service_id');
            $table->index('service_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing');
    }
}

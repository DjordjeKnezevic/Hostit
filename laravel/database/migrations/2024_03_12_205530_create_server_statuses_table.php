<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('server_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id')->unique();
            $table->string('status');
            $table->unsignedBigInteger('uptime')->default(0);
            $table->unsignedBigInteger('downtime')->default(0);
            $table->timestamp('last_started_at')->nullable();
            $table->timestamp('last_stopped_at')->nullable();
            $table->timestamp('last_crashed_at')->nullable();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('server_statuses');
    }
};

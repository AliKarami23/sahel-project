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
        Schema::create('sans', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('start');
            $table->integer('end');
            $table->string('date');
            $table->integer('capacity_man')->default(0);
            $table->integer('capacity_woman')->default(0);
            $table->integer('capacity_remains_man')->default(0);
            $table->integer('capacity_remains_woman')->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sans');
    }
};

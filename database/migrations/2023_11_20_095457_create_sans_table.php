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
            $table->integer('Start');
            $table->integer('End');
            $table->string('Date');
            $table->integer('Capacity_Man')->default(0);
            $table->integer('Capacity_Woman')->default(0);
            $table->integer('Capacity_remains_Man')->default(0);
            $table->integer('Capacity_remains_Woman')->default(0);
            $table->string('Status')->default('active');
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

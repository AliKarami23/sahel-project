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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('price');
            $table->string('discount')->nullable();
            $table->integer('discount_amount')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('age_limit')->nullable();
            $table->integer('age_limit_value')->nullable();
            $table->integer('total_start');
            $table->integer('total_end');
            $table->integer('break_time');
            $table->longText('rules');
            $table->string('description');
            $table->integer('discounted_price');
            $table->integer('video_id');
            $table->string('image_id');
            $table->integer('image_main_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

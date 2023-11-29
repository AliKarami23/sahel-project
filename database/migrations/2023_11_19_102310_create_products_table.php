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
            $table->string('Title');
            $table->integer('Price');
            $table->string('Discount')->nullable();
            $table->integer('Discount_Amount')->nullable();
            $table->string('Discount_Type')->nullable();
            $table->string('Age_Limit')->nullable();
            $table->integer('Age_Limit_Value')->nullable();
            $table->integer('Total_Start');
            $table->integer('Total_End');
            $table->integer('Break_Time');
            $table->longText('Rules');
            $table->string('Description');
            $table->integer('Discounted_price');
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

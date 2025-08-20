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
        Schema::create('flash_deal_types', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('flash_deal_id'); // Foreign key to flash_deals table
            $table->unsignedBigInteger('product_id'); // Foreign key to products table
            $table->double('discount_amount');
            $table->enum('discount_type', ['amount', 'percentage'])->default('amount');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('flash_deal_id')->references('id')->on('flash_deals')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->index(['flash_deal_id', 'product_id', 'discount_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_deal_types');
    }
};

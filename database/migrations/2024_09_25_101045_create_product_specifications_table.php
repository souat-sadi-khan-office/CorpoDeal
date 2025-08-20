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
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('key_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('attribute_id');
            $table->boolean('key_feature')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('key_id')->references('id')->on('specification_keys')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('specification_key_types')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('specification_key_type_attributes')->onDelete('cascade');

            // Adding an index with a shorter name
            $table->index(['product_id', 'key_id', 'type_id', 'attribute_id'], 'idx_product_specifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};

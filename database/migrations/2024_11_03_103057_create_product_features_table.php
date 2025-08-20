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
        Schema::create('product_features', function (Blueprint $table) {
            $table->id();

            // Defining foreign keys
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('feature_id');

            // Adding indexes
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
                
            $table->foreign('feature_id')
                ->references('id')->on('laptop_finder_features')
                ->onDelete('cascade');

            $table->timestamps();

            // Adding indexes separately
            $table->index('product_id');
            $table->index('feature_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_features');
    }
};

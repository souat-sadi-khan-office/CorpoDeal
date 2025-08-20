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
        Schema::create('product_purposes', function (Blueprint $table) {
            $table->id();

            // Defining foreign keys
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('purpose_id');

            // Adding foreign key constraints without index() method
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
                
            $table->foreign('purpose_id')
                ->references('id')->on('laptop_finder_purposes')
                ->onDelete('cascade');

            $table->timestamps();

            // Adding indexes separately
            $table->index('product_id');
            $table->index('purpose_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_purposes');
    }
};

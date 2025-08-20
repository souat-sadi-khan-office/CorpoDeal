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
        Schema::create('product_portabilities', function (Blueprint $table) {
            $table->id();
            // Defining foreign keys
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('portable_id');

            // Adding indexes
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
                
            $table->foreign('portable_id')
                ->references('id')->on('laptop_finder_portabilities')
                ->onDelete('cascade');

            $table->timestamps();

            // Adding indexes separately
            $table->index('product_id');
            $table->index('portable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_portabilities');
    }
};

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
        Schema::create('product_budgets', function (Blueprint $table) {
            $table->id();

            // Defining columns
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('budget_id');

            // Adding foreign key constraints without a custom name
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
                
            $table->foreign('budget_id')
                ->references('id')->on('laptop_finder_budgets')
                ->onDelete('cascade');

            // Adding indexes
            $table->index('product_id');
            $table->index('budget_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_budgets');
    }
};

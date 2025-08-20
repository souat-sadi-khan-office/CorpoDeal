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
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->enum('discount_type', ['flat', 'percent'])->default('flat');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('threshold', 10, 2)->default(0);
            $table->enum('with_product_tax', ['yes', 'no'])->default('no');
            $table->enum('applicable_to', ['single_product', 'multi_product', 'full_order'])->default('full_order');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('usage_limit')->default(100);
            $table->integer('usage_count')->default(0);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
    }
};

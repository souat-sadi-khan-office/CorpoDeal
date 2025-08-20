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

            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('brand_type_id')->nullable();

            $table->string('name');
            $table->string('slug');
            $table->string('thumb_image')->nullable();
            $table->double('unit_price')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('in_stock')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('low_stock')->default(1);
            $table->boolean('is_discounted')->default(0);
            $table->enum('discount_type', ['amount', 'percentage'])->nullable()->default('amount');
            $table->double('discount')->nullable();
            $table->timestamp('discount_start_date')->nullable();
            $table->timestamp('discount_end_date')->nullable();
            $table->boolean('is_returnable')->default(0);
            $table->integer('return_deadline')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('brand_type_id')->references('id')->on('brand_types')->onDelete('cascade');

            $table->index(['category_id', 'brand_id', 'brand_type_id', 'slug', 'status']);
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

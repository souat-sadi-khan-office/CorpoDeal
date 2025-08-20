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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('video_provider')->nullable();
            $table->string('video_link')->nullable();
            $table->longText('description')->nullable();
            $table->integer('current_stock')->nullable();
            $table->integer('low_stock_quantity')->nullable();
            $table->boolean('cash_on_delivery')->default(1);
            $table->integer('est_shipping_days')->nullable();
            $table->integer('number_of_sale')->default(0);
            $table->double('average_rating')->nullable();
            $table->integer('number_of_rating')->default(0);
            $table->double('average_purchase_price')->nullable();
            $table->string('site_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_article_tags')->nullable();
            $table->text('meta_script_tags')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};

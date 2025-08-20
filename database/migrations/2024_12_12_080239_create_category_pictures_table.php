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
        Schema::create('category_pictures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('picture');
            $table->enum('position', ['after_breadcrumb_section', 'after_title_and_description', 'on_left_sidebar_start', 'on_left_sidebar_footer', 'after_left_sidebar_price_range', 'after_left_sidebar_stock', 'after_left_sidebar_brand', 'after_left_sidebar_rating', 'after_left_sidebar_specification_key', 'on_right_sidebar_top', 'on_right_sidebar_bottom', 'on_right_sidebar_after_filter'])->default('after_breadcrumb_section');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_pictures');
    }
};

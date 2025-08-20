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
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('bannerSection')->default(true);
            $table->boolean('sliderSection')->default(true);
            $table->boolean('midBanner')->default(true);
            $table->boolean('dealOfTheDay')->default(true);
            $table->boolean('trending')->default(true);
            $table->boolean('brands')->default(true);
            $table->boolean('popularANDfeatured')->default(true);
            $table->boolean('newslatter')->default(true);
            $table->unsignedBigInteger('last_updated_by');

            $table->foreign('last_updated_by')->references('id')->on('admins')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};

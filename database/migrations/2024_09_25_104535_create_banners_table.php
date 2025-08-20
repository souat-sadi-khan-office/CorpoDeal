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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->enum('banner_type', ['main'])->default('main');
            $table->string('name');
            $table->string('slug');
            $table->string('image');
            $table->enum('source_type', ['none'])->default('none');
            $table->integer('source_id')->nullable();
            $table->string('link')->nullable();
            $table->string('alt_tag');
            $table->unsignedBigInteger('created_by');
            $table->boolean('status')->default(0);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');

            // index
            $table->index(['banner_type', 'slug', 'source_type', 'source_id', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};

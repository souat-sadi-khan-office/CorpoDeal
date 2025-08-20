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
        Schema::create('flash_deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable(); // Foreign key to admins table
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamp('starting_time');
            $table->enum('deadline_type', ['minute', 'hour', 'day', 'week', 'month'])->default('day');
            $table->integer('deadline_time');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);
            $table->string('site_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable(); // Fixed typo here
            $table->text('meta_article_tag')->nullable();
            $table->text('meta_script_tag')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');

            // index
            $table->index(['slug', 'deadline_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_deals');
    }
};

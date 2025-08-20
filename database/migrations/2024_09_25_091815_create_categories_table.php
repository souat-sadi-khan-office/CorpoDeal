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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->string('icon');
            $table->string('header');
            $table->text('short_description');
            $table->longText('description');
            $table->string('site_title');
            $table->string('meta_title');
            $table->text('meta_keyword');
            $table->text('meta_description');
            $table->text('meta_article_tag')->nullable();
            $table->text('meta_script_tag')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('parent_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');

            $table->index(['parent_id', 'slug', 'is_featured', 'status']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

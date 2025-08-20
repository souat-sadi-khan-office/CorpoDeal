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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable(); 
            $table->text('meta_article_tag')->nullable();
            $table->text('meta_script_tag')->nullable(); 
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('created_by')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');

            $table->index(['is_featured', 'status', 'slug', 'created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};

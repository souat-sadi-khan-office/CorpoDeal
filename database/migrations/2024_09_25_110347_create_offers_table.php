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
        Schema::create('offers', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing BIGINT UNSIGNED column
            $table->unsignedBigInteger('created_by'); // Nullable foreign key
            $table->string('name');
            $table->string('slug');
            $table->string('condition');
            $table->longText('description');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('type', ['category', 'product', 'brand', 'normal'])->default('normal');
            $table->text('details'); // Array of IDs; consider using JSON for structured data
            $table->boolean('status')->default(0);
            $table->string('site_title')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_article_tag')->nullable();
            $table->text('meta_script_tag')->nullable();

            // Foreign Key Constraints
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');

            // index
            $table->index(['slug', 'type', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

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
        Schema::create('specification_key_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('specification_key_id');
            $table->string('name');
            $table->boolean('status')->default(1);
            $table->integer('position')->nullable();
            $table->boolean('show_on_filter')->default(0);
            $table->string('filter_name')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('specification_key_id')->references('id')->on('specification_keys')->onDelete('cascade');

            $table->index(['specification_key_id', 'status']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specification_key_types');
    }
};

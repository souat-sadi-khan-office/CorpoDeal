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
        Schema::create('notices', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('admin_id'); // Foreign key to admins table
            $table->text('message'); // Notice message
            $table->boolean('status')->default(1); // Status, default is active
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            $table->index(['admin_id', 'status']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};

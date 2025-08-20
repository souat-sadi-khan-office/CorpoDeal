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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('subject');
            $table->text('details');
            $table->string('file_one')->nullable(); // Optional attachment 1
            $table->string('file_two')->nullable(); // Optional attachment 2
            $table->string('file_three')->nullable(); // Optional attachment 3
            $table->string('file_four')->nullable(); // Optional attachment 4
            $table->string('file_five')->nullable(); // Optional attachment 5
            $table->boolean('is_replied')->default(false);
            $table->boolean('is_viewed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};

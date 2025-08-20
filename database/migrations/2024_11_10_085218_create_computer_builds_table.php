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
        Schema::create('computer_builds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('processor_id')->nullable();
            $table->unsignedBigInteger('mother_board_id')->nullable();
            $table->unsignedBigInteger('cpu_cooler_id')->nullable();
            $table->unsignedBigInteger('graphics_card_id')->nullable();
            $table->unsignedBigInteger('psu_id')->nullable();
            $table->unsignedBigInteger('casing_id')->nullable();
            $table->unsignedBigInteger('monitor_id')->nullable();
            $table->unsignedBigInteger('keyboard_id')->nullable();
            $table->unsignedBigInteger('mouse_id')->nullable();
            $table->unsignedBigInteger('anti_virus_id')->nullable();
            $table->unsignedBigInteger('head_phone_id')->nullable();
            $table->unsignedBigInteger('ups_id')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('processor_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('mother_board_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('cpu_cooler_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('graphics_card_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('psu_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('casing_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('monitor_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('keyboard_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('mouse_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('anti_virus_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('head_phone_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('ups_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computer_builds');
    }
};

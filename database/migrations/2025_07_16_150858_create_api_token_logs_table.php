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
        Schema::create('api_token_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_token_id')->constrained()->onDelete('cascade');
            $table->string('method')->nullable();
            $table->string('url')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->json('request_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_token_logs');
    }
};

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('trx_id')->nullable();
            $table->double('amount');
            $table->string('currency');
            $table->string('payer_id')->nullable();
            $table->string('gateway_name');
            $table->string('email')->nullable();
            $table->string('status');
            $table->string('payment_unique_id')->nullable();
            $table->string('payment_order_id')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Composite index
            $table->index(['user_id', 'payer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

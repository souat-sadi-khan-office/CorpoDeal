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
        Schema::create('refund_transactions', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('payment_receiver_id'); 
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('refund_id'); 
            $table->unsignedBigInteger('order_id'); 
            $table->string('payment_method'); 
            $table->boolean('payment_status')->default(false); 
            $table->unsignedBigInteger('trx_id')->nullable(); 
            $table->double('amount'); 
            $table->string('currency');
            $table->string('to_payer_id')->nullable(); 
            $table->string('refund_unique_id')->nullable(); 
            $table->string('refund_order_id')->nullable(); 
            $table->timestamps(); 

            // Foreign key constraints
            $table->foreign('payment_receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('refund_id')->references('id')->on('refund_requests')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            // Composite index
            $table->index(['admin_id', 'refund_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_transactions');
    }
};

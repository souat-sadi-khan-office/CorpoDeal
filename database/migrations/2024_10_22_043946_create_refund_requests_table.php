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
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('order_id'); 
            $table->unsignedBigInteger('payment_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->boolean('is_refunded')->default(false); 
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approver_id')->nullable(); 
            $table->text('approver_message')->nullable(); 
            $table->double('amount')->default(0); 
            $table->longText('reason')->nullable();
            $table->timestamp('possible_return_date')->nullable(); 
            $table->timestamps(); 

            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('admins')->onDelete('set null');

            // Composite index
            $table->index(['order_id', 'payment_id', 'user_id', 'approver_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};

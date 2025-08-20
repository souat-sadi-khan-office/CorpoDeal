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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); 
            $table->string('unique_id')->unique(); 
            $table->unsignedBigInteger('payment_id')->nullable(); 
            $table->unsignedBigInteger('user_id'); 
            $table->double('order_amount')->default(0); 
            $table->double('tax_amount')->default(0); 
            $table->double('discount_amount')->default(0); 
            $table->double('final_amount')->default(0); 
            $table->unsignedBigInteger('currency_id');
            $table->enum('payment_status', ['Paid', 'Not_Paid'])->default('Not_Paid');
            $table->enum('status', ['pending', 'packaging', 'shipping', 'out_of_delivery', 'delivered', 'returned', 'failed'])->default('pending');
            $table->boolean('is_delivered')->default(false); 
            $table->boolean('is_cod')->default(false); 
            $table->boolean('is_refund_requested')->default(false); 
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        
            // Composite index
            $table->index(['payment_id', 'user_id', 'is_cod','payment_status']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

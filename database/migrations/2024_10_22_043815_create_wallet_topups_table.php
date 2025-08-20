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
        Schema::create('wallet_topups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id'); 
            $table->unsignedBigInteger('admin_id')->nullable(); 
            $table->string('gateway_name')->nullable();
            $table->double('amount')->default(0);
            $table->boolean('is_admin_paid')->default(false);
            $table->timestamps();
            
            // Define foreign key constraints
            $table->foreign('wallet_id')->references('id')->on('user_wallets')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_topups');
    }
};

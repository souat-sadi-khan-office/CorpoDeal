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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', [
                'new_order',
                'pending',
                'packaging',
                'shipping',
                'out_of_delivery',
                'delivered',
                'returned',
                'failed',
                'partially_returned',
                'cancelled'
            ])->default('new_order')->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->enum('status', [
                'new_order',
                'pending',
                'packaging',
                'shipping',
                'out_of_delivery',
                'delivered',
                'returned',
                'failed',
                'partially_returned'
            ])->default('pending')->after('payment_status');
        });
    }
};
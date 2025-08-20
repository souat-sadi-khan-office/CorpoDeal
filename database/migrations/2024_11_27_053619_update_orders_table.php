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
            // Modify the 'status' column to include the new enum value
            $table->enum('status', [
                'pending',
                'packaging',
                'shipping',
                'out_of_delivery',
                'delivered',
                'returned',
                'failed',
                'partially_returned' // New enum value
            ])->default('pending')->change();

            // Add new fields
            $table->double('exchange_rate')->default(1)->after('final_amount')->comment('order currency Exchange rate from USD');
            $table->unsignedBigInteger('pricing_tier_id')->nullable()->comment('when Multi Tier Discount Applied')->after('exchange_rate');
            $table->enum('refund_type', ['full', 'partial'])->nullable()->after('is_refund_requested')->comment('Refund Policy');
            $table->foreign('pricing_tier_id')->references('id')->on('pricing_tiers')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert the changes
            $table->dropForeign(['pricing_tier_id']);
            $table->enum('status', [
                'pending',
                'packaging',
                'shipping',
                'out_of_delivery',
                'delivered',
                'returned',
                'failed'
            ])->default('pending')->change();

            $table->dropColumn(['exchange_rate','pricing_tier_id', 'refund_type']);
        });
    }
};

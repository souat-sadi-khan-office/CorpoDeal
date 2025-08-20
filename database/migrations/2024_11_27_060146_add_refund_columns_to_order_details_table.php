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
        Schema::table('order_details', function (Blueprint $table) {
            // Add the new columns
            $table->longText('refunded_product_ids')->nullable()->after('email');
            $table->longText('refunded_details')->nullable()->after('refunded_product_ids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Drop the columns if rolled back
            $table->dropColumn(['refunded_product_ids', 'refunded_details']);
        });
    }
};

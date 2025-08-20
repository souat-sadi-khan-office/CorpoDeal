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
        Schema::table('brand_types', function (Blueprint $table) {
            $table->string('icon')->default('<i class="fi-rr-list"></i>');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brand_types', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};

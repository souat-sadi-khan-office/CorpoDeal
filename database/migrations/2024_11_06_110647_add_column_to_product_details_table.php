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
        Schema::table('product_details', function (Blueprint $table) {
            $table->boolean('pc_builder_item')->default(0);
            $table->enum('component_type', ['core', 'peri'])->nullable();
            $table->enum('peri_component_type', ['monitor', 'casing_fan', 'keyboard', 'mouse', 'anti-virus', 'headphone', 'ups'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            //
        });
    }
};

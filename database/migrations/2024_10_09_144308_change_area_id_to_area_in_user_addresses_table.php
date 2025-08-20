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
        Schema::table('user_addresses', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['area_id']);
            
            // Then drop the 'area_id' column
            $table->dropColumn('area_id');
            
            // Add the new 'area' column as a string
            $table->string('area')->after('city_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            //
        });
    }
};

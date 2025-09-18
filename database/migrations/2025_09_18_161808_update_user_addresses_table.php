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
            // drop foreign properly
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->nullable()->after('country_id');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('set null');

            $table->string('city')->nullable()->after('city_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->after('country_id');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade');
        });
    }
};

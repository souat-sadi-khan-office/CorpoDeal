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
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('alt_tag');
            $table->string('name')->nullable();
            $table->string('alt_tag')->nullable();
            $table->integer('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('alt_tag');
            $table->string('name');
            $table->string('alt_tag');
            $table->dropColumn('position');
        });
    }
};

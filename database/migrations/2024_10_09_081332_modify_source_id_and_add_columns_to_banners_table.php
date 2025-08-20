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
               $table->integer('source_id')->nullable()->change();
   
               // Add new nullable columns after the name column
               $table->string('header_title')->nullable()->after('name');
               $table->string('old_offer')->nullable()->after('header_title');
               $table->string('new_offer')->nullable()->after('old_offer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
              $table->dropColumn(['header_title', 'old_offer', 'new_offer']);
              $table->unsignedBigInteger('source_id')->change();
        });
    }
};

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
        Schema::create('pc_builder_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->enum('comp_type', ['processor', 'cpu_cooler', 'motherboard', 'ram', 'storage', 'psu', 'graphics-card', 'casing'])->default(null);
            $table->enum('cpu_brand', ['intel', 'amd'])->default(null);
            $table->string('cpu_generation')->nullable();
            $table->string('socket_type')->nullable();
            $table->integer('default_tdp')->default(0);
            $table->enum('mb_supported_cpu_brand', ['intel', 'amd'])->nullable();
            $table->string('mb_cpu_generation')->nullable();
            $table->string('mb_supported_socket')->nullable();
            $table->enum('mb_form_factor', ['atx', 'matx', 'mitx', 'other'])->nullable();
            $table->string('mb_supported_memory_type')->nullable();
            $table->integer('mb_default_tdp')->default(0);
            $table->integer('mb_number_of_ram')->default(0);
            $table->boolean('mb_xmp_support')->default(0);
            $table->boolean('mb_m2_storage_support')->default(0);
            $table->string('mb_number_of_m2_support')->nullable();
            $table->boolean('mb_sata_storage_support')->default(0);
            $table->string('mb_number_of_sta_support')->nullable();
            $table->boolean('mb_lic_support')->nullable();
            $table->string('mb_pcie_slot')->nullable();
            $table->enum('ram_memory_type', ['DDR 3', 'DDR 4', 'DDR 5'])->nullable();
            $table->string('ram_speed')->nullable();
            $table->boolean('ram_xmp_support')->default(0);
            $table->integer('ram_default_tdp')->default(0);
            $table->enum('storage_type', ['hdd', 'ssd'])->nullable();
            $table->boolean('storage_m2_support')->default(0);
            $table->boolean('storage_sata_support')->default(0);
            $table->integer('storage_default_tdp')->default(0);
            $table->string('gc_supported_pcie_slot')->nullable();
            $table->enum('gc_supported_form_factor', ['atx', 'matx', 'mitx', 'other'])->nullable();
            $table->integer('gc_default_tdp')->default();
            $table->string('cc_supported_socket')->nullable();
            $table->integer('cc_default_tdp')->default(0);
            $table->enum('casing_form_factor', ['atx', 'matx', 'mitx', 'other'])->nullable();
            $table->boolean('casing_psu_installed')->default(0);
            $table->boolean('casing_fan_installed')->default(0);
            $table->integer('casing_number_of_fan_front')->default(0);
            $table->integer('casing_number_of_fan_top')->default(0);
            $table->integer('casing_number_of_fan_back')->default(0);
            
            // Adding indexes
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
            
            $table->index('product_id');
            $table->index('comp_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_builder_items');
    }
};

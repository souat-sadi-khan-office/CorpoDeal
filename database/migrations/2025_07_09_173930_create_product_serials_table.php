<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_serials', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->unsignedBigInteger('supplier_id')->nullable();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->unsignedBigInteger('stock_purchase_id')->nullable();

            $table->foreign('stock_purchase_id')->references('id')->on('stock_purchases')->onDelete('set null');
            $table->timestamps();
        });

        $permissions = [
            'product.serial-add',
            'product.serial-view',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'admin',
                ]);
            }
        }

        $role = Role::find(1);

        if ($role) {
            foreach ($permissions as $permission) {
                $perm = Permission::findByName($permission, 'admin');
                if ($perm && !$role->hasPermissionTo($perm)) {
                    $role->givePermissionTo($perm);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_serials');
    }
};

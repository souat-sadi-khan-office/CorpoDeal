<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_email')->unique();
            $table->string('contact_phone');
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
        $permissions = [
            'supplier.create',
            'supplier.view',
            'supplier.edit',
            'supplier.assign',
            'product-sale-report.view',
            'order-report.view',
            'stock-purchase-report.view',
            'transaction-report.view',
            'payments.view',
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
        Schema::dropIfExists('suppliers');
    }
};

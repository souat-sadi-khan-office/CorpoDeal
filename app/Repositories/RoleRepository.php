<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\Interface\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAllRoles()
    {
        return Role::all();
    }

    public function findRoleById($id)
    {
        return Role::findOrFail($id);
    }

    public function getAllPermission()
    {
        return Permission::all();
    }

    public function createRole(Request $request)
    {
        $role = Role::create(['name' => $request->name, 'guard_name' => 'admin']);
        $role->givePermissionTo($request->permissions);

        return 1;
    }

    public function updateRole($id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update($data);

		$role->syncPermissions($data['permissions']);

        return $role;
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }
}

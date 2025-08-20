<?php

namespace App\Repositories\Interface;

use Illuminate\Http\Request;

interface RoleRepositoryInterface
{
    public function getAllRoles();
    public function findRoleById($id);
    public function getAllPermission();
    public function createRole(Request $request);
    public function updateRole($id, array $data);
    public function deleteRole($id);
}

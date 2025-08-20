<?php

namespace App\Repositories\Interface;

use Illuminate\Http\Request;


interface AdminRepositoryInterface
{
    public function getAllAdmins();
    public function getAdminById($id);
    public function createAdmin(array $data);
    public function updateAdmin($id, array $data);
    public function deleteAdmin($id);
    public function createOrder($request);
}

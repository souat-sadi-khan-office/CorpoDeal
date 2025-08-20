<?php

namespace App\Repositories\Interface;

interface CategoryBannerRepositoryInterface
{
    public function all();
    public function dataTable();
    public function find($id);
    public function store($data);
    public function update($id, $data);
    public function destroy($id);
    public function updateStatus($request, $id);
}
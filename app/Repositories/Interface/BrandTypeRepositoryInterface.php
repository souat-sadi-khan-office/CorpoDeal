<?php

namespace App\Repositories\Interface;

interface BrandTypeRepositoryInterface
{
    public function getAllBrandTypes();
    public function dataTable();
    public function getAllBrandTypesByBrandId($brandId);
    public function findBrandTypeById($id);
    public function createBrandType($data);
    public function updateBrandType($id, $data);
    public function deleteBrandType($id);
    public function updateStatus($request, $id);
    public function updateFeatured($request, $id);
}

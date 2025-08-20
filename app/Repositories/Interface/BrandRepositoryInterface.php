<?php

namespace App\Repositories\Interface;

interface BrandRepositoryInterface
{
    public function getAllBrands();
    public function dataTable();
    public function findBrandById($id);
    public function createBrand($data);
    public function updateBrand($id, $data);
    public function deleteBrand($id);
    public function updateStatus($request, $id);
    public function updateFeatured($request, $id);
    public function getBrandBySlug($slug);
}

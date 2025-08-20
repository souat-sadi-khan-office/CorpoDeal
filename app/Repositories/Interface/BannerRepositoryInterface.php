<?php

namespace App\Repositories\Interface;

interface BannerRepositoryInterface
{
    public function getAllBanners();
    public function dataTable();
    public function findBannerById($id);
    public function createBanner($data);
    public function updateBanner($id, $data);
    public function deleteBanner($id);
    public function updateStatus($request, $id);
    public function getSourceOptions($source);
}
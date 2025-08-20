<?php

namespace App\Repositories\Interface;

interface FlashDealRepositoryInterface
{
    public function getAllDeals();
    public function dataTable();
    public function findDealById($id);
    public function createDeal($data);
    public function updateDeal($id, $data);
    public function deleteDeal($id);
    public function updateStatus($request, $id);
}

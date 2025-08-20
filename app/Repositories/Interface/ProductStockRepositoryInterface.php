<?php

namespace App\Repositories\Interface;

use Illuminate\Http\Request;

interface ProductStockRepositoryInterface
{
    public function getAllStock();
    public function dataTable();
    public function findStockById($id);
    public function createStock(array $data);
    public function deleteStock($id);
    public function checkQuantity(array $data);
    public function updateStatus(Request $request, $id);
}
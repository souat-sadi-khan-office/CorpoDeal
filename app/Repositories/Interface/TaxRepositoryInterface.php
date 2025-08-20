<?php

namespace App\Repositories\Interface;

interface TaxRepositoryInterface
{
    public function dataTable();
    public function getAllActiveTaxes();
    public function getAllTax();
    public function findTaxById($id);
    public function createTax(array $data);
    public function updateTax($id, array $data);
    public function deleteTax($id);
    public function updateStatus($request, $id);
}

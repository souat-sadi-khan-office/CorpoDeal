<?php

namespace App\Repositories\Interface;

interface CurrencyRepositoryInterface
{
    public function dataTable();
    public function getAllCurrencies();
    public function getAllActiveCurrencies();
    public function findCurrencyById($id);
    public function createCurrency(array $data);
    public function updateCurrency($id, array $data);
    public function deleteCurrency($id);
    public function updateStatus($request, $id);
}
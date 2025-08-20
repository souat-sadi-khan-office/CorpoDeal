<?php

namespace App\Repositories\Interface;

interface PricingTierRepositoryInterface
{
    public function dataTable();
    public function getAllActive();
    public function all();
    public function find($id);
    public function store(array $data);
    public function update($id, array $data);
    public function destroy($id);
    public function updateStatus($request, $id);
}

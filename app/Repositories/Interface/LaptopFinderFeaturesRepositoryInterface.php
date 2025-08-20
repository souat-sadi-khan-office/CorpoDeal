<?php

namespace App\Repositories\Interface;

interface LaptopFinderFeaturesRepositoryInterface
{
    public function all();
    public function dataTable();
    public function find($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function updateStatus($request, $id);
}

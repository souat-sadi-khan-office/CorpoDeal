<?php

namespace App\Repositories\Interface;

interface CarrierRepositoryInterface
{
    public function all();
    public function activeAll();
    public function dataTable();
    public function find($id);
    public function store($request);
    public function update($id, array $data);
    public function destroy($id);
    public function updateStatus($request, $id);
}
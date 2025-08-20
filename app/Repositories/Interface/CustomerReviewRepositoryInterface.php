<?php

namespace App\Repositories\Interface;

interface CustomerReviewRepositoryInterface
{
    public function all();
    public function dataTable();
    public function dataTableWithAjaxSearch($product_id);
    public function find($id);
    public function store($request);
    public function update($id, $request);
    public function destroy($id);
    public function updateStatus($request, $id);
}

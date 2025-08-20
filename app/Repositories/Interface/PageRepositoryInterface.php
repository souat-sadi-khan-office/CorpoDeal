<?php

namespace App\Repositories\Interface;

interface PageRepositoryInterface
{
    public function getAllPages();
    public function dataTable();
    public function findPageById($id);
    public function createPage($data);
    public function updatePage($id, $data);
    public function deletePage($id);
    public function updateStatus($request, $id);
}
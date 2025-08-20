<?php

namespace App\Repositories\Interface;

interface PhoneBookRepositoryInterface
{
    public function getAll();
    public function getAllByUser();
    public function findModelById($id);
    public function createModel(array $data);
    public function updateModel($id, array $data);
    public function deleteModel($id);
}
<?php

namespace App\Repositories\Interface;

interface CategoryRepositoryInterface
{
    public function getAllCategories();
    public function index();
    public function index2();
    public function getCategoryBySlug($slug);
    public function getCategoryById($id);
    public function store($data);
    public function edit($id);
    public function delete($id);
    public function update($request, $id);
    public function categoriesDropDown($request);
    public function checkSlugExists(string $slug): bool;
    public function updateisFeatured($request, $id);
    public function updatestatus($request, $id);
    public function view($request);
    public function getParentCategoryIds($categoryId);
}
<?php

namespace App\Repositories\Interface;

interface ProductRepositoryInterface
{
    public function index($request, $category_id);
    public function search($search, $categories,$brands,$sort);
    public function storeProduct($request);
    public function updateProduct($request, $id);
    public function flashDealProduct($product);
    public function dataTable();
    public function dataTableWithAjaxSearch($category_id, $brand_id);
    public function updateStatus($request, $id);
    public function getProductById($id);
    public function getProductByIds($id);
    public function updateFeatured($request, $id);
    public function deleteProduct($id);
    public function quickview($slug);
    public function userQuotes();
    public function getProductStockPurchaseDetails($id);
    public function duplicateProduct($request, $id);
    public function specificationProduct($productId);
    public function specificationKeyFeaturedProduct($productId);
    public function specificationproducts();
    public function specificationproductsDatatable();
    public function specificationproductModal($id);
    public function specificationProductPage($id);
    public function keyfeature($id);
    public function specificationsAdd($request, $id);
    public function delete($id);

    public function isDiscountedProduct($product);
    public function discountPrice($product);
    public function compare();

    public function pc_builder_items($request);
}

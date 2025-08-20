<?php

namespace App\Repositories\Interface;

interface CustomerRepositoryInterface
{
    public function getAllCustomers();
    public function dataTable();
    public function findCustomerById($id);
    public function createCustomer($data);
    public function updateCustomer($id, $data);
    public function deleteCustomer($id);
    public function updateStatus($request, $id);

    public function storeAddress($request);
    public function findCustomerAddressById($id);
    public function updateAddress($request, $id);
    public function destroyAddress($id);

    public function storePhone($request);
    public function findCustomerPhoneById($id);
    public function updatePhone($request, $id);
    public function destroyPhone($id);

    public function destroyCustomerWishList($id);
    public function destroyRating($id);

    public function updateQuestionStatus($request, $id);
}

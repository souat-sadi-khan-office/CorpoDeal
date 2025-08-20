<?php

namespace App\Repositories\Interface;

interface CouponRepositoryInterface
{
    public function all();
    public function dataTable();
    public function find($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function updateStatus($request, $id);

    public function checkCoupon($data);
    public function buyCoupon($data);
    public function findByCoupon($couponCode);
    public function userCoupon($couponId);

    public function assignCoupon($request);
}
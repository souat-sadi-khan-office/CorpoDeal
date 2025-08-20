<?php

namespace App\Repositories\Interface;

interface UserRepositoryInterface
{
    public function index($request);
    public function getCustomerDetails();
    public function updateProfile($request);
    public function updatePassword($request);
    public function getUserPhoneList();
    public function getUserWishList();
    public function removeWishList($id);
    public function informations($country_id);
}
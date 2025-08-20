<?php

namespace App\Repositories\Interface;

interface CartRepositoryInterface
{
    public function addToCart($request);
    public function subToCart($request);
    public function getCartItems($request);
    public function removeCartItems($request);
    public function cart($request);

    public function buyNow($request);

    public function getCartItemsByCartId($id);
    public function destroyCart($id);

    public function clearCart();
}
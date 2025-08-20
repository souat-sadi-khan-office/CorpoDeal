<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\CartRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{

    private $cartRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
    ) {
        $this->cartRepository = $cartRepository;
    }
    public function addToCart(Request $request)
    {
        return $this->cartRepository->addToCart($request);
    }

    public function subToCart(Request $request)
    {
        return $this->cartRepository->subToCart($request);
    }

    public function getCartItems(Request $request)
    {
        return $this->cartRepository->getCartItems($request);
    }

    public function removeCartItems(Request $request)
    {
        return $this->cartRepository->removeCartItems($request);
    }

    public function buyProductNow(Request $request, $slug) 
    {
        $product = Product::where('slug', $slug)->first();
        if(!$product) {
            return redirect()->back();
        }
        
        $request->merge(['slug' => $slug]);
        $this->buyNow($request);
    }

    public function buyNow(Request $request)
    {
        return $this->cartRepository->buyNow($request);
    }

    public function cart(Request $request)
    {
        return $this->cartRepository->cart($request);
    }
}

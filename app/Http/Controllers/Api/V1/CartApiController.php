<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;

class CartApiController extends Controller
{
    // 🔄 Utility: Get Auth User ID or Guest Token
    protected function getCartOwner(Request $request)
    {
        if (auth('api')->check()) {
            return ['user_id' => auth('api')->user()->id, 'cart_token' => null];
        }

        $token = $request->header('X-Cart-Token') 
            ?? $request->cookie('cart_token') 
            ?? $request->query('cart_token');

        return $token ? ['user_id' => null, 'cart_token' => $token] : null;
    }

    // Create or Get Cart
    protected function getOrCreateCart($userId = null, $cartToken = null)
    {
        if ($userId) {
            return Cart::firstOrCreate(['user_id' => $userId]);
        }


        if ($cartToken) {
            return Cart::firstOrCreate(['guest_token' => $cartToken]);
        }

        return null;
    }

    // Add to Cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $owner = $this->getCartOwner($request);
        $cartToken = $owner['cart_token'] ?? null;
        $userId = $owner['user_id'] ?? null;

        if (!$owner) {
            $cartToken = Str::uuid()->toString();
        }

        $cart = $this->getOrCreateCart($userId, $cartToken);

        $item = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            CartDetail::create([
                'cart_id'    => $cart->id,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        return response()->json([
            'status'     => true,
            'message'    => 'Product added to cart',
            'cart_token' => $cartToken,
        ]);
    }

    // Subtract Quantity
    public function subtract(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $owner = $this->getCartOwner($request);
        if (!$owner) return response()->json(['status' => false, 'message' => 'Cart not found'], 404);

        $cart = $this->getOrCreateCart($owner['user_id'], $owner['cart_token']);
        $item = CartDetail::where('cart_id', $cart->id)->where('product_id', $request->product_id)->first();

        if (!$item) return response()->json(['status' => false, 'message' => 'Item not in cart'], 404);

        $item->quantity--;
        if ($item->quantity <= 0) $item->delete();
        else $item->save();

        return response()->json(['status' => true, 'message' => 'Item quantity updated']);
    }

    // ❌ Remove Item
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $owner = $this->getCartOwner($request);
        if (!$owner) return response()->json(['status' => false, 'message' => 'Cart not found'], 404);

        $cart = $this->getOrCreateCart($owner['user_id'], $owner['cart_token']);
        CartDetail::where('cart_id', $cart->id)->where('product_id', $request->product_id)->delete();

        return response()->json(['status' => true, 'message' => 'Item removed from cart']);
    }

    // ⚡ Buy Now
    public function buyNow(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        // Normally you’d redirect to checkout or create order
        return response()->json(['status' => true, 'message' => 'Redirecting to checkout']);
    }

    // 📦 List Cart Items
    public function items(Request $request)
    {
        $owner = $this->getCartOwner($request);
        if (!$owner) return response()->json(['status' => false, 'message' => 'Cart not found'], 404);

        $cart = $this->getOrCreateCart($owner['user_id'], $owner['cart_token']);
        $items = CartDetail::with('product')->where('cart_id', $cart->id)->get();

        return response()->json(['status' => true, 'items' => $items]);
    }

    // Cart Summary
    public function index(Request $request)
    {
        $owner = $this->getCartOwner($request);
        return $owner;
        if (!$owner) return response()->json(['status' => false, 'message' => 'Cart not found'], 404);

        $cart = $this->getOrCreateCart($owner['user_id'], $owner['cart_token']);
        $items = CartDetail::with('product')->where('cart_id', $cart->id)->get();

        $total = $items->sum(fn ($item) => $item->product->unit_price * $item->quantity);

        return response()->json([
            'status' => true,
            'total'  => $total,
            'items'  => $items,
        ]);
    }
}
<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\Interface\CartRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;


class CartRepository implements CartRepositoryInterface
{

    private $product;
    public function __construct(
        ProductRepositoryInterface $product,
    ) {
        $this->product = $product;
    }
    public function addToCart($request)
    {
        $slug = $request->slug;
        $quantity = $request->quantity ? $request->quantity : 1;
        $item_sub_total_price = 0;

        if ($quantity > 100) {
            return response()->json(['status' => false, 'message' => 'This product is not available in the desired quantity or not in stock']);
        }

        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => "Product not Found"
            ]);
        }

        // Check if the user is logged in, if so use user_id, otherwise use session
        $user_id = Auth::guard('customer')->user()->id ?? null;
        $cart_id = Session::get('cart_id');  // Retrieve the cart ID from session

        if (!$cart_id) {
            // If no cart ID in session, create a new cart and store its ID in session
            $cart = Cart::create([
                'user_id' => $user_id,
                'total_quantity' => 0,
                'currency_id' => Session::get('currency_id') ?? 1,
            ]);

            // Store the cart ID in session
            Session::put('cart_id', $cart->id);
        } else {
            // Retrieve the cart by cart_id
            $cart = Cart::find($cart_id);
            if(!$cart) {
                $cart = Cart::create([
                    'user_id' => $user_id,
                    'total_quantity' => 0,
                    'currency_id' => Session::get('currency_id') ?? 1,
                ]);
    
                // Store the cart ID in session
                Session::put('cart_id', $cart->id);
            }
        }

        // Check if the product already exists in the cart
        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        // If the product exists in the cart, update quantity
        if ($cartDetail) {

            if (($cartDetail->quantity + $quantity) > 100) {
                return response()->json(['status' => false, 'message' => 'This product is not available in the desired quantity or not in stock ']);
            }

            $stockResponse = getProductStock($product->id, ($cartDetail->quantity + $quantity));
            if (!$stockResponse['status']) {
                return response()->json($stockResponse);
            }

            $quantity += $cartDetail->quantity;
            if (($stockResponse['stock']) < $quantity) {
                return response()->json(['status' => true, 'message' => 'This product is not available in the desired quantity or not in stock ']);
            }

            $cartDetail->quantity = $quantity;
            $cartDetail->save();

            $price = $this->product->discountPrice($cartDetail->product);
            $item_sub_total_price = $cartDetail->quantity * $price;
        } else {

            if ($quantity > 100) {
                return response()->json(['status' => false, 'message' => 'This product is not available in the desired quantity or not in stock ']);
            }

            $stockResponse = getProductStock($product->id, $quantity);
            if (!$stockResponse['status']) {
                return response()->json($stockResponse);
            }

            if (($stockResponse['stock']) < $quantity) {
                return response()->json(['status' => true, 'message' => 'This product is not available in the desired quantity or not in stock ']);
            }

            // Otherwise, create a new cart detail
            $cartDetail = new CartDetail([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
            $cartDetail->save();

            $price = $this->product->discountPrice($cartDetail->product);
            $item_sub_total_price = $cartDetail->quantity * $price;
        }

        // Update cart total price and quantity
        $cart->total_quantity = $cart->total_quantity + ($request->quantity ? $request->quantity : 1);
        $cart->user_id = isset($cart->user_id) ? $cart->user_id : $user_id;
        $cart->save();

        $total_price = 0;
        $items = CartDetail::where('cart_id', $cart->id)->get();
        foreach ($items as $item) {
            $price = $this->product->discountPrice($item->product);
            $total_price += ($price * $item->quantity);
        }
        $counter = count($items);
        $total_price = format_price(convert_price($total_price));
        $item_sub_total_price = format_price(convert_price($item_sub_total_price));

        return response()->json([
            'status' => true,
            'counter' => $cart->total_quantity,
            'message' => 'Added to your cart successfully',
            'thumb_image' => asset($product->thumb_image),
            'name' => $product->name,
            'total_price' => $total_price,
            'cart_sub_total_amount' => $total_price,
            'cart_total_amount' => $total_price,
            'total_quantity' => $cart->total_quantity,
            'item_sub_total' => $item_sub_total_price,
            'id' => $cartDetail->id
        ]);
    }

    public function buyNow($request)
    {
        $this->clearCart();

        $cart = $this->addToCart($request);
        if($cart) {
            if($request->is_ajax) {
                return response()->json(['status' => true, 'message' => 'Product is added to the cart', 'goto' => route('order.checkout')]);
            } else {
                echo '<script>window.location.href="'. route('order.checkout') .'"</script>';
                return 1;
            }
        }
    }

    public function clearCart()
    {
        $userId = Auth::guard('customer')->user()->id;

        $cartIds = Cart::where('user_id', $userId)->pluck('id')->toArray();
        if (!empty($cartIds)) {
            CartDetail::whereIn('cart_id', $cartIds)->delete();
        
            Cart::whereIn('id', $cartIds)->delete();
        }
        
        return 1;
    }

    public function subToCart($request)
    {
        $slug = $request->slug;
        $quantity = $request->quantity ? $request->quantity : 1;
        $item_sub_total_price = 0;
        $load = false;

        if ($quantity < 1) {
            return response()->json(['status' => false, 'message' => 'You crossed minimum purchase quantity']);
        }

        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => "Product not Found"
            ]);
        }

        // Retrieve cart ID from session
        $cart_id = Session::get('cart_id');

        // Check if the user is logged in, if so use user_id, otherwise use session-based cart ID
        $user_id = Auth::guard('customer')->user()->id ?? null;

        if (!$cart_id) {
            // If no cart ID in session, create a new cart and store its ID in session
            $cart = Cart::create([
                'user_id' => $user_id,
                'total_quantity' => 0,
                'currency_id' => Session::get('currency_id') ?? 1,
            ]);

            // Store the cart ID in session
            Session::put('cart_id', $cart->id);
        } else {
            // Retrieve the cart by cart_id from session
            $cart = Cart::find($cart_id);
        }

        // Check if the product already exists in the cart
        $cartDetail = CartDetail::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        // If the product exists in the cart, update quantity
        if ($cartDetail) {
            if (($cartDetail->quantity - $quantity) < 1) {
                return response()->json(['status' => false, 'message' => 'You crossed minimum purchase quantity']);
            }

            $cartDetail->quantity -= $quantity;
            $cartDetail->save();

            $price = $this->product->discountPrice($cartDetail->product);
            $item_sub_total_price = $cartDetail->quantity * $price;

            if ($cartDetail->quantity == 0) {
                $cartDetail->delete();
                $load = true;
            }
        }

        // Update cart total price and quantity
        $cart->total_quantity -= ($request->quantity ? $request->quantity : 1);
        $cart->user_id = isset($cart->user_id) ? $cart->user_id : $user_id;
        $cart->save();

        $total_price = 0;
        $items = CartDetail::where('cart_id', $cart->id)->get();
        foreach ($items as $item) {
            $price = $this->product->discountPrice($item->product);
            $total_price += ($price * $item->quantity);
        }

        $total_price = format_price(convert_price($total_price));
        $item_sub_total_price = format_price(convert_price($item_sub_total_price));

        return response()->json([
            'status' => true,
            'message' => 'Quantity adjusted successfully',
            'thumb_image' => asset($product->thumb_image),
            'name' => $product->name,
            'total_price' => $total_price,
            'cart_sub_total_amount' => $total_price,
            'cart_total_amount' => $total_price,
            'total_quantity' => $cart->total_quantity,
            'item_sub_total' => $item_sub_total_price,
            'id' => $cartDetail->id,
            'load' => $load
        ]);
    }

    public function getCartItems($request)
    {
        $items = [];
        $counter = 0;
        $total_price = 0;
        $models = [];

        // Retrieve cart ID from session
        $cart_id = Session::get('cart_id');
        $currency_id = Session::get('currency_id') ?? 1;

        // Check if the user is logged in, if so use user_id, otherwise use session-based cart ID
        $user_id = Auth::guard('customer')->user()->id ?? null;

        if (!$cart_id) {
            // If no cart ID in session, create a new cart and store its ID in session
            $cart = Cart::create([
                'user_id' => $user_id,
                'total_quantity' => 0,
                'currency_id' => $currency_id,
            ]);

            // Store the cart ID in session
            Session::put('cart_id', $cart->id);
        } else {
            // Retrieve the cart by cart_id from session
            $cart = Cart::find($cart_id);

            if(!$cart) {
                $cart = Cart::create([
                    'user_id' => $user_id,
                    'total_quantity' => 0,
                    'currency_id' => $currency_id,
                ]);
    
                // Store the cart ID in session
                Session::put('cart_id', $cart->id);
            }
        }

        // Get cart items
        $items = CartDetail::where('cart_id', $cart->id)->get();

        $cart_updated = false;
        foreach ($items as $item) {
            $stockResponse = getProductStock($item->product_id);
            if (!$stockResponse['status']) {
                // Remove item from cart if stock is unavailable
                $cart_updated = true;
                $itemQuantity = $item->quantity;
                $item->delete();
                $cart->total_quantity -= $itemQuantity;
                $cart->save();
            } else {
                $price = $this->product->discountPrice($item->product);
                $total_price += ($price * $item->quantity);

                // Prepare cart item data
                $models[] = [
                    'id' => $item->id,
                    'slug' => $item->product->slug,
                    'thumb_image' => asset($item->product->thumb_image),
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $price
                ];
            }
        }

        // Return cart details
        $counter = count($models);

        // Format total price
        $total_price = format_price(convert_price($total_price));

        // Render appropriate view based on the request
        if ($request->has('show') && $request->show == 'main-cart-area') {
            $html = view('frontend.components.main_cart_listing', compact('models', 'cart_updated'))->render();
        } else {
            $html = view('frontend.components.cart_listing', compact('models', 'cart_updated'))->render();
        }

        // Return the response
        return response()->json(['content' => $html, 'total_price' => $total_price, 'counter' => $cart->total_quantity]);
    }

    public function removeCartItems($request)
    {
        $id = $request->id;

        if (!$id) {
            return response()->json(['status' => false, 'message' => 'Cart not found. ']);
        }

        $item = CartDetail::find($id);
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Cart sad item not found. ']);
        }
        $cartId = $item->cart_id;

        $cart = Cart::find($cartId);
        if (!$cart) {
            return response()->json(['status' => false, 'message' => 'Cart item not found. ']);
        }

        $removedQuantity = $item->quantity;
        $item->delete();

        $cart->total_quantity -= $removedQuantity;
        $cart->save();

        $items = CartDetail::where('cart_id', $cartId)->get();

        $total_quantity = 0;
        $total_price = 0;
        $models = [];
        
        foreach ($items as $item) {
            $quantity = $item->quantity;
            $price = $this->product->discountPrice($item->product);
        
            $total_quantity += $quantity;
            $total_price += ($price * $quantity);
        
            $models[] = [
                'id' => $item->id,
                'slug' => $item->product->slug,
                'thumb_image' => asset($item->product->thumb_image),
                'name' => $item->product->name,
                'quantity' => $quantity,
                'price' => $price
            ];
        }

        $counter = count($models);
        $total_price = format_price(convert_price($total_price));
        if ($request->has('show') && $request->show == 'main-cart-area') {
            $html = view('frontend.components.main_cart_listing', compact('models'))->render();
        } else {
            $html = view('frontend.components.cart_listing', compact('models'))->render();
        }
        return response()->json(['status' => true, 'message' => 'Item removed from your cart', 'content' => $html, 'total_price' => $total_price, 'counter' => $cart->total_quantity]);
    }

    public function cart($request)
    {
        $items = [];
        $counter = 0;
        $total_price = 0;

        // Retrieve cart ID from session
        $cart_id = Session::get('cart_id');
        if (!$cart_id) {
            return response()->json(['status' => false, 'message' => 'Cart not found']);
        }

        // Retrieve the cart based on session-stored cart_id
        $cart = Cart::find($cart_id);

        if (!$cart) {
            return response()->json(['status' => false, 'message' => 'Cart not found']);
        }

        // Get cart details
        $items = CartDetail::where('cart_id', $cart->id)->get();

        $cart_updated = false;
        $models = [];
        foreach ($items as $item) {
            $stockResponse = getProductStock($item->product_id);
            if (!$stockResponse['status']) {
                // If product is out of stock, remove it from the cart
                $cart_updated = true;
                $itemQuantity = $item->quantity;
                $item->delete();
                $cart->total_quantity -= $itemQuantity;
                $cart->save();
            } else {
                // Calculate price for available products
                $price = $this->product->discountPrice($item->product);
                $total_price += ($price * $item->quantity);

                // Prepare cart item data
                $models[] = [
                    'id' => $item->id,
                    'slug' => $item->product->slug,
                    'thumb_image' => asset($item->product->thumb_image),
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $price
                ];
            }
        }

        // Update counter
        $counter = count($models);

        // Return view with cart items
        return view('frontend.cart', compact('models', 'cart_updated', 'counter', 'total_price'));
    }


    public function getCartItemsByCartId($id)
    {
        return CartDetail::where('cart_id', $id)->get();
    }

    public function destroyCart($id)
    {
        $cart = Cart::find($id);
        if($cart) {
            CartDetail::where('cart_id', $cart->id)->delete();
            $cart->delete();
        }

        return 1;
    }
}

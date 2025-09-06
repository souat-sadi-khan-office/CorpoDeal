<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutPlaceOrderRequest;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\UserBroughtCoupon;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class CheckoutApiController extends Controller
{
    public function cartDetails(Request $request)
    {
        $user = auth('api')->user();

        $cart = Cart::with('details.product.ratings', 'details.product.image', 'details.product.specifications')->where('user_id', $user->id)->first();

        if (!$cart || $cart->details->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty',
            ], 404);
        }

        $items = [];
        $subTotal = 0;

        foreach ($cart->details as $item) {
            $product = $item->product;
            $mappedProduct = $this->mapper($product);

            $quantity = $item->quantity;
            $price = $mappedProduct['discounted_price'] ?? $mappedProduct['unit_price'];
            $subtotal = $price * $quantity;

            $subTotal += $subtotal;

            $items[] = [
                'product' => $mappedProduct,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        $shippingCharge = $this->calculateShippingCharge($user); // your shipping logic here
        $taxAmount = $this->calculateTax($subTotal);
        $discountAmount = session('coupon_discount', 0);

        $total = $subTotal + $shippingCharge + $taxAmount - $discountAmount;

        return response()->json([
            'status' => true,
            'cart' => [
                'items' => $items,
                'sub_total' => $subTotal,
                'shipping_charge' => $shippingCharge,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
            ],
        ]);
    }

    private function mapper($product)
    {
        // Determine if the product is discounted
        $isDiscounted = $product->discount_type && $product->discount > 0;
        $discountedPrice = $product->unit_price; // Default to unit price

        if ($isDiscounted) {
            $discountAmount = $product->discount_type == 'amount'
                ? $product->discount
                : ($product->unit_price * ($product->discount / 100));

            $discountedPrice = $product->unit_price - $discountAmount;
        }

        // Get average rating and hover image
        // Get average rating and convert to percentage
        $averageRating = $product->ratings->isNotEmpty() ? $product->ratings->first()->averageRating : null;
        $averageRatingPercentage = $averageRating !== null ? ($averageRating / 5) * 100 : null;
        $hoverImage = $product->image->isNotEmpty() ? $product->image->first()->image : null;

        $stockStatus = 'out_of_stock';
        $inCity = false;
        $stockResponse = getProductStock($product->id, 1);
        if ($stockResponse['status']) {
            $stockStatus = 'in_stock';
        }

        if( isset($stockResponse['in_city']) && $stockResponse['in_city'] == true) {
            $inCity = true;
        }

        if ($product->specifications) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'stage' => $product->stage,
                'stock_status' => $stockStatus,
                'available_in_city' => $inCity,
                'thumb_image' => $product->thumb_image,
                'hover_image' => $hoverImage,
                'unit_price' => $product->unit_price,
                'discounted_price' => $discountedPrice, // Include discounted price
                'discount' => $product->discount,
                'discount_type' => $isDiscounted ? $product->discount_type : null,
                'averageRating' => $averageRatingPercentage,
                'ratingCount' => $product->ratings_count,
                'stock_types' => $product->stock_types,
            ];
        } else {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'available_in_city' => $inCity,
                'thumb_image' => $product->thumb_image,
                'hover_image' => $hoverImage,
                'unit_price' => $product->unit_price,
                'discounted_price' => $discountedPrice, // Include discounted price
                'discount' => $product->discount,
                'discount_type' => $isDiscounted ? $product->discount_type : null,
                'averageRating' => $averageRatingPercentage,
                'ratingCount' => $product->ratings_count,
                'stock_types' => $product->stock_types,
            ];
        }
    }

    // 2. Apply Coupon
    public function applyCoupon(Request $request)
    {
        $user = auth('api')->user();
        $data = $request->all();

        // 1. Validate Request
        $validator = Validator::make($data, [
            'coupon_code' => 'required|string|min:5|max:50|exists:coupons,coupon_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ], 422);
        }

        // 2. Fetch Coupon
        $coupon = Coupon::where('coupon_code', $data['coupon_code'])->first();
        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found.'
            ], 404);
        }

        $today = date('Y-m-d');

        // 3. Check Expiry
        if ($coupon->deadline && $coupon->deadline < $today) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon expired on ' . get_system_date($coupon->deadline)
            ], 400);
        }

        $userId = $user->id;

        // 4. New User Only Coupon Check
        if ($coupon->is_new_user_only) {
            $hasPreviousOrder = Order::where('user_id', $userId)->exists();
            if ($hasPreviousOrder) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon is only valid for new users.'
                ], 403);
            }
        }

        // 5. Mobile App Only Coupon Check (optional)
        $isAppRequest = $request->header('X-App-Request') === 'mobile';
        if ($coupon->is_mobile_app_only && !$isAppRequest) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon is only valid on the mobile app.'
            ], 403);
        }

        // 6. Coupon Usage Check
        if ($coupon->is_sellable == 0) {
            // Free coupon: Check if already used
            if ($this->userCoupon($coupon->id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon has already been used.'
                ], 403);
            }
        } else {
            // Paid coupon: Check ownership and usage
            $userCoupon = UserBroughtCoupon::where('user_id', $userId)
                ->where('coupon_id', $coupon->id)
                ->first();

            if (!$userCoupon) {
                return response()->json([
                    'status' => false,
                    'message' => "You don't own this coupon."
                ], 403);
            }

            if ($userCoupon->status == 1) {
                return response()->json([
                    'status' => false,
                    'message' => "You have already used this coupon."
                ], 403);
            }
        }

        // 7. Get Cart and Cart Items
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart || $cart->details->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Your cart is empty.'
            ], 404);
        }

        $items = $cart->details;
        $totalPrice = 0;
        $taxAmount = 0;
        $productRepository = app(\App\Repositories\Interface\ProductRepositoryInterface::class);
        $cartUpdated = false;

        foreach ($items as $item) {
            $stockResponse = getProductStock($item->product_id);
            if (!$stockResponse['status']) {
                // Remove out-of-stock item
                $itemQuantity = $item->quantity;
                $item->delete();

                $cart->total_quantity -= $itemQuantity;
                $cart->save();
                $cartUpdated = true;
                continue;
            }

            // Calculate tax for product
            if ($item->product->taxes->isNotEmpty()) {
                foreach ($item->product->taxes as $tax) {
                    $taxAmount += ($tax->tax_type == 'percent')
                        ? (($item->product->unit_price * $tax->tax) / 100) * $item->quantity
                        : ($tax->tax * $item->quantity);
                }
            }

            // Get discounted price via repository method
            $price = $productRepository->discountPrice($item->product);
            $totalPrice += ($price * $item->quantity);
        }

        if ($cartUpdated) {
            return response()->json([
                'status' => false,
                'message' => 'Some products in your cart are out of stock and have been removed. Please try again.'
            ], 409);
        }

        // 8. Minimum Shipping Amount Check
        if ($coupon->minimum_shipping_amount > $totalPrice) {
            return response()->json([
                'status' => false,
                'message' => 'Minimum ' . format_price(convert_price($coupon->minimum_shipping_amount)) . ' is required to apply this coupon.'
            ], 400);
        }

        // 9. Calculate Discount
        $discountAmount = 0;
        if ($coupon->discount_type === 'percent') {
            $discountAmount = ($totalPrice * $coupon->discount_amount) / 100;
        } elseif ($coupon->discount_type === 'amount') {
            $discountAmount = $coupon->discount_amount;
        }

        // Cap discount to max allowed
        if ($coupon->maximum_discount_amount != 0 && $coupon->maximum_discount_amount < $discountAmount) {
            $discountAmount = $coupon->maximum_discount_amount;
        }

        // 10. Shipping Cost Calculation
        $shippingCharge = 0;
        if (get_settings('shipping_cost_type') === 'flat_rate') {
            $shippingCharge = get_settings('system_default_delivery_charge');
        }

        // 11. Final amount calculations
        $discountedPrice = $totalPrice - $discountAmount;
        $totalAmount = ($totalPrice + $taxAmount + $shippingCharge) - $discountAmount;

        // 12. Store coupon info in session or DB if needed
        session([
            'coupon_code' => $coupon->coupon_code,
            'coupon_discount' => $discountAmount,
        ]);

        // 13. Return success response
        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully.',
            'formatted_discount' => format_price(convert_price($discountAmount)),
            'total_amount' => format_price(convert_price($totalAmount)),
            'discounted_price' => $discountedPrice,
            'discount_amount' => convert_price($discountAmount),
            'coupon_code' => $coupon->coupon_code,
        ]);
    }

    public function userCoupon($couponId)
    {
        return UserCoupon::where('user_id', Auth::guard('api')->user()->id)->where('coupon_id', $couponId)->first();
    }

    // 3. Remove Coupon
    public function removeCoupon()
    {
        session()->forget(['coupon_code', 'coupon_discount']);

        return response()->json([
            'status' => true,
            'message' => 'Coupon removed successfully',
        ]);
    }

    private function productsdata($products, $countryId, $cityId)
    {
        return collect($products)->map(function ($product) use ($countryId, $cityId) {
            $item = Product::where('id', $product['id'])
                ->select('id', 'slug', 'name', 'unit_price', 'discount', 'discount_type', 'sku', 'stock_types')
                ->with(['stock' => function ($query) {
                    $query->select('id', 'stock', 'number_of_sale', 'in_stock', 'product_id')
                        ->where('in_stock', '>', 0);
                }])
                ->first();
            if ($item) {
                if ($item->stock_types === 'globally') {
                    $stockItem = $item->stock->first();
                } else {
                    $stockItem = $item->stock->filter(function ($stock) use ($countryId, $cityId) {
                        if ($countryId && $stock->country_id == $countryId) {
                            return true;
                        }
                        if ($cityId && $stock->city_id == $cityId) {
                            return true;
                        }
                        if (!$cityId) {
                            $country = Country::find($countryId);
                            if ($country && $stock->zone_id == $country->zone_id) {
                                return true;
                            }
                        }
                        return false;
                    })->first();
                }


                if ($stockItem) {
                    $tax = $item->taxes->map(function ($tax) use ($item) {
                        $value = $tax->tax;
                        if ($tax->tax_type === "percent") {
                            $value = ($item->unit_price * $tax->tax) / 100;
                        }
                        return $value;
                    })->sum();

                    return [
                        'id' => $item->id,
                        'slug' => $item->slug,
                        'name' => $item->name,
                        'unit_price' => $item->unit_price,
                        'discount' => $item->discount,
                        'discount_type' => $item->discount_type,
                        'sku' => $item->sku,
                        'stock' => $stockItem,
                        'order_qty' => $product['order_qty'],
                        'tax' => $tax * $product['order_qty'],
                    ];
                }
            }

            return null;
        })->filter();
    }

    private function adjustStock($productsData)
    {
        foreach ($productsData as $product) {
            $stockItem = $product['stock'];
            if ($stockItem) {
                $this->updateStock($stockItem, $product['order_qty']);
            }
        }
    }

    private function updateStock($stockItem, $quantity)
    {
        $newStock = $stockItem->stock - $quantity;

        $stockItem->update(['stock' => $newStock]);
    }

    private function generateAddress($request, string $type)
    {
        $address = $request["{$type}_address"];

        if (isset($request["{$type}_address2"])) {
            $address .= ', ' . $request["{$type}_address2"];
        }

        $address .= ', ' . $request["{$type}_area"];

        $cityName = City::find($request["{$type}_city"])->name ?? '';
        if ($cityName) {
            $address .= ', ' . $cityName;
        }

        $countryName = isset($request['country_name']) ? $request['country_name'] : '';
        if ($countryName) {
            $address .= ', ' . $countryName;
        }

        return $address;
    }

    // 4. Place Order
    public function placeOrder(CheckoutPlaceOrderRequest $request)
    {
        $user = auth('api')->user();

        $cart = Cart::with('details.product.ratings', 'details.product.image', 'details.product.stock')->where('user_id', $user->id)->first();
        if (!$cart || $cart->details->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Cart is empty'], 400);
        }

        // Prepare product IDs and product array for the store method
        $productsRequestArray = $cart->details->map(function ($item) {
            return [
                'id' => $item->product_id,
                'order_qty' => $item->quantity,
            ];
        })->toArray();

        // Mimic $request data to pass to store method
        $customRequest = $request->all();
        $customRequest['product'] = $productsRequestArray;
        $customRequest['country_id'] = $request->billing_country;
        $customRequest['shipping_city'] = $request->shipping_city ?? $request->billing_city;
        $customRequest['billing_city'] = $request->billing_city;
        $customRequest['customer_email'] = $request->customer_email;
        $customRequest['customer_phone'] = $request->customer_phone;

        try {
            DB::beginTransaction();

            // Step 1: Validate and process products, addresses, coupon, multi-tier discount, etc.
            $getProductsData = $this->productsdata($customRequest['product'], $customRequest['country_id'], $customRequest['shipping_city']);

            $productIds = collect($getProductsData)->pluck('id')->toArray();

            // Build detailed product info (similar to store method)
            $details['products'] = collect($getProductsData)->map(function ($item) use ($customRequest) {
                return [
                    'id' => $item['id'],
                    'stock_id' => $item['stock']->id,
                    'name' => $item['name'],
                    'qty' => $item['order_qty'],
                    'slug' => $item['slug'],
                    'total_price' => $item['unit_price'] * $item['order_qty'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'],
                    'discount_type' => $item['discount_type'],
                    'tax' => $item['tax'] ?? 0,
                ];
            })->toArray();

            $details['company_name'] = $customRequest['customer_company'] ?? null;
            $details['user_name'] = $customRequest['customer_name'];
            $details['shipping_charge'] = convert_price_to_usd($request->shipping_charge);
            if (isset($customRequest['saved'])) {
                $details['premium_user_order'] = true;
                $details['premium_user_discount_amount'] = $customRequest['saved'];
            }

            $billingAddress = $this->generateAddress($customRequest, 'billing');
            $shippingAddress = isset($customRequest['different_shipping_address'])
                ? $this->generateAddress($customRequest, 'shipping')
                : $billingAddress;

            // Step 2: Coupon Validation
            $couponId = $this->checkCoupon($customRequest);

            // Step 3: Multi-tier discount application
            // $tierInfo = $this->multiTierApplied($customRequest);
            $tierInfo = 0;

            // Calculate subtotal
            $subTotal = collect($getProductsData)->sum(function ($item) {
                return $item['unit_price'] * $item['order_qty'];
            });

            // Calculate discount (you might get from coupon, multi-tier etc)
            $discount = $customRequest['discount'] ?? 0; // or calculate coupon discount

            // Calculate tax (sum from all products or other tax logic)
            $totalTax = $customRequest['total_tax'] ?? 0; // or calculate

            // Shipping charge convert if needed
            $shippingCharge = convert_price_to_usd($request->shipping_charge ?? 0);

            // Set them explicitly in $customRequest so you can use below
            $customRequest['totalAmount'] = $subTotal - $discount + $totalTax + $shippingCharge;
            $customRequest['discount'] = $discount;
            $customRequest['total_tax'] = $totalTax;
            $customRequest['shipping_charge'] = $shippingCharge;


            // Step 4: Calculate amounts (similar to store)
            $orderAmount = round(
                convert_price_to_usd(
                    ($customRequest['totalAmount'] + $customRequest['discount'] + $tierInfo)
                    - ($customRequest['total_tax'] + $customRequest['shipping_charge'])
                ),
                2
            );

            // Step 5: Create the order
            $order = Order::create([
                'unique_id' => uniqid('#'),
                'payment_id' => null,
                'user_id' => $user->id,
                'order_amount' => $orderAmount,
                'tax_amount' => round(convert_price_to_usd($customRequest['total_tax']), 2),
                'discount_amount' => round(convert_price_to_usd($customRequest['discount']), 2),
                'final_amount' => round(convert_price_to_usd($customRequest['totalAmount']), 2),
                'exchange_rate' => 1,
                'currency_id' => 1,
                'payment_status' => 'Not_Paid',
                'status' => 'new_order',
                'is_delivered' => false,
                'is_cod' => $customRequest['payment_option'] === 'cash_on_delivery',
                'is_manual_pay' => $customRequest['payment_option'] === 'manual_pay',
                'is_negative_balance_order' => $customRequest['payment_option'] === 'negative_balance',
                'is_refund_requested' => false,
            ]);

            // Step 6: Create or update order status
            $this->createOrUpdateOrderStatus($order, 'pending');

            // Step 7: Create Order Detail entry
            OrderDetail::create([
                'order_id' => $order->id,
                'product_ids' => json_encode($productIds),
                'details' => json_encode($details),
                'notes' => $customRequest['notes'] ?? null,
                'shipping_method' => $customRequest['shipping_method'] ?? 'Default',
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'phone' => $customRequest['customer_phone'],
                'email' => $customRequest['customer_email'],
                'coupon_id' => $couponId,
                'tier_info' => $tierInfo != null ? json_encode($tierInfo) : null,
            ]);

            // Step 8: Adjust wallet if negative balance payment
            if ($customRequest['payment_option'] === 'negative_balance') {
                $this->adjustNegativeBalanceWallet(round($customRequest['totalAmount'], 2), $order);
            }
            
            if ($request->payment_option == 'sslcommerz' && $order?->id) {
                DB::commit();

                Session::put('user_id', $user->id);

                // Step 9: Clear Cart
                $cart->details()->delete();
                $cart->delete();

                $customRequest['billingAddress'] = $billingAddress;

                $gateway = $this->gatewayOrder($order, $customRequest);
                
                return $gateway;
            }

            // Step 9: Clear Cart
            $cart->details()->delete();
            $cart->delete();

            // Step 10: Forget coupon session
            session()->forget(['coupon_code', 'coupon_discount']);

            DB::commit();

            // broadcast(new OrderCreated($order))->toOthers();

            return response()->json([
                'status' => true,
                'message' => 'Order placed successfully',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Order placement failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function gatewayOrder($order, $customRequest)
    {
        $client = new Client();

        // Determine the API URL based on the sandbox setting
        // $url = env('SSLCOMMERZ_SANDBOX') ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php' : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
        $url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";

        // Prepare the data for the payment request
        $data = [
            'store_id' => env('SSLCOMMERZ_STORE_ID'),
            'store_passwd' => env('SSLCOMMERZ_STORE_PASSWORD'),
            'total_amount' => $order->final_amount,
            'currency' => 'BDT',
            'tran_id' => str_replace('#', '', $order?->unique_id),
            'success_url' => route('api.sslcommerz.success', ['user_id' => $order->user_id]),
            'fail_url' => route('api.sslcommerz.failure'),
            'cancel_url' => route('api.sslcommerz.cancel'),
            'product_name' => 'Order ' . $order?->unique_id, 
            'cus_name' => $order->user ? $order->user->name : 'N/A', 
            'cus_email' => $customRequest['customer_email'],
            'cus_add1' => $customRequest['billingAddress'],
            'cus_city' => $customRequest['billing_city'],
            'cus_phone' => $customRequest['customer_phone'],
            'shipping_method' => "NO",
            'product_category' => "Electronic",
            'product_profile' => "General",
            'cus_country' => 'Bangladesh',
        ];

        $data['token'] = csrf_token();

        try {
            // Make the payment request
            $response = $client->post($url, [
                'headers' => [
                    'X-CSRF-TOKEN' => csrf_token(),
                ],
                'form_params' => $data,
            ]);

            // Decode the response from Gateway
            $result = json_decode($response->getBody()->getContents(), true);
            
            // Check for GatewayPageURL and redirect
            if (isset($result['GatewayPageURL']) && isset($result['status']) && $result['status']=='SUCCESS') {
                return response()->json([
                    'status' => true, 
                    'gateway' => $result['GatewayPageURL'] . '?user_id=' . $order->user_id
                ]);
            } else {
                return response()->json([
                    'error' => 'Payment gateway URL not found.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function adjustNegativeBalanceWallet($balance, $order)
    {
        $user = Auth::guard('customer')->user();

        if (!$user) {
            return;
        }

        $wallet = $user->negetiveBalanceWallets()->where('currency_id', $order->currency_id)->first();

        if (!$wallet) {
            return;
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => round($balance, 2),
            'currency' => $order->currency->code,
            'gateway_name' => 'Negative Balance',
            'status' => 'COMPLETED',
            'payment_unique_id' => $order->unique_id,
        ]);

        $wallet->update([
            'current_balance' => $wallet->current_balance - $balance,
            'used_balance' => $wallet->used_balance + $balance,
        ]);

        $order->update([
            'payment_id' => $payment->id,
            'payment_status' => 'Paid',
        ]);
    }

    private function createOrUpdateOrderStatus($order, $status)
    {
        $orderStatus = OrderStatusHistory::where('order_id', $order->id)->first();

        if ($orderStatus) {
            switch ($status) {
                case 'new_order':
                    $orderStatus->pending_time = now();
                    break;
                case 'pending':
                    $orderStatus->pending_time = now();
                    break;

                case 'packaging':
                    $orderStatus->packaging_time = now();
                    break;

                case 'shipping':
                    $orderStatus->shipping_time = now();
                    break;

                case 'out_of_delivery':
                    $orderStatus->out_for_delivery_time = now();
                    break;

                case 'delivered':
                    $orderStatus->delivered_time = now();
                    break;

                case 'returned':
                    $orderStatus->returned_time = now();
                    break;

                case 'failed':
                    $orderStatus->failed_time = now();
                    break;

                default:
                    break;
            }

            $orderStatus->save();
        } else {
            $orderStatus = new OrderStatusHistory();
            $orderStatus->order_id = $order->id;

            switch ($status) {
                case 'new_order':
                    $orderStatus->pending_time = now();
                    break;
                case 'pending':
                    $orderStatus->pending_time = now();
                    break;

                case 'packaging':
                    $orderStatus->packaging_time = now();
                    break;

                case 'shipping':
                    $orderStatus->shipping_time = now();
                    break;

                case 'out_for_delivery':
                    $orderStatus->out_for_delivery_time = now();
                    break;

                case 'delivered':
                    $orderStatus->delivered_time = now();
                    break;

                case 'returned':
                    $orderStatus->returned_time = now();
                    break;

                case 'failed':
                    $orderStatus->failed_time = now();
                    break;

                default:
                    break;
            }

            $orderStatus->save();
        }
    }

    public function checkCoupon($request)
    {
        if (!isset($request['coupon_code'])) {
            return null;
        }

        $coupon_code = $request['coupon_code'];

        // check the coupon
        $coupon = Coupon::where('coupon_code', $coupon_code)->first();
        if (!$coupon) {
            return null;
        }

        // check the user is already used this free coupon
        if ($coupon->is_sellable == 0) {
            $userCoupon = $this->userCoupon($coupon->id);
            if ($userCoupon) {
                return null;
            }
        }

        // check start_date & end_date
        if ($coupon->start_date && ($coupon->start_date > date('Y-m-d'))) {
            return null;
        }

        // check minimum shipping amount
        if ($coupon->end_date && ($coupon->end_date < date('Y-m-d'))) {
            return null;
        }

        $userBoughtCoupon = null;
        if ($coupon->is_sellable == 1 && isset(auth('api')->user()->coupons)) {
            $userBoughtCoupon = auth('api')->user()->coupons->where('coupon_id', $coupon->id)->first();
            if (!$userBoughtCoupon) {
                return null;
            }

            if ($userBoughtCoupon->status == 1) {
                return null;
            }
        }

        $userCoupon = UserCoupon::create([
            'user_id' => auth('api')->user()->id,
            'coupon_id' => $coupon->id,
            'discount_amount' => $request['discount']
        ]);

        if ($userCoupon) {
            if ($userBoughtCoupon) {
                $userBoughtCoupon->status = 1;
                $userBoughtCoupon->save();
            }

            $this->calculatePriceWithDiscount($coupon, $request);
        }

        return $coupon->id;
    }

    private function multiTierApplied($request)
    {
        $currency_id = 1;
        $tier = getApplicablePricingTier($currency_id, null, $request->subtotal_main, $request->total_tax);

        if (!$tier) {
            return null;
        }

        $final_total = 0;
        $discount_value = 0;
        if ($tier->discount_type == 'flat') {
            $final_total = $discount_value = $request->subtotal_main - $tier->discount_amount;
        } else if ($tier->discount_type == 'percent') {
            $discount_value = ($request->subtotal_main * $tier->discount_amount) / 100;
            $final_total = $request->subtotal_main - $discount_value;
        } else {
            $final_total = $discount_value = $request->subtotal_main;
        }

        // Add tax and shipping charge to final total
        $final_total += $request->total_tax + $request->shipping_charge;

        $final_total -= $request->discount;
        if ($request->multi_tier_applier) {
            $request->merge(['totalAmount' => $final_total]);
        }
        return [
            'tier_name' => $request->multi_tier_applier ? $tier->name : null,
            'tier_discount_type' => $request->multi_tier_applier ? $tier->discount_type : null,
            'tier_discount_amount' => $request->multi_tier_applier ? $tier->discount_amount : 0,
            'order_discount_amount' => $request->multi_tier_applier ? convert_price_to_usd($discount_value) : 0,
        ];
    }

    private function calculatePriceWithDiscount($coupon, $request)
    {
        if ($coupon->discount_type == 'percent') {
            $discounted_amount = ($request->totalAmount * $coupon->discount_amount) / 100;
        } elseif ($coupon->discount_type == 'amount') {
            $discounted_amount = $coupon->discount_amount;
        }

        if ($coupon->maximum_discount_amount != 0 && $coupon->maximum_discount_amount < $discounted_amount) {
            $discounted_amount = $coupon->maximum_discount_amount;
        }

        $total_amount = $request->totalAmount - convert_price($discounted_amount);

        $request->merge(['totalAmount' => $total_amount]);
        $request->merge(['discount' => convert_price($discounted_amount)]);

        return $coupon->id;
    }

    // 5. Get User Addresses (for address book selection)
    public function getUserAddresses()
    {
        $user = auth('api')->user();
        $addresses = $user->address()->get()->map(function ($address) {
            return $address;
        });

        return response()->json([
            'status' => true,
            'addresses' => $addresses,
        ]);
    }

    // --- Helper functions below ---
    protected function calculateShippingCharge($user, $deliveryMethod = 'home_delivery')
    {
        // Implement your shipping logic (e.g., based on user location, delivery method)
        if ($deliveryMethod === 'store_pickup' && get_settings('enable_store_pickup')) {
            return get_settings('store_pickup_fee');
        }
        // Default shipping charge
        return 100; // example fixed charge, replace with your logic
    }

    protected function calculateTax($subTotal)
    {
        // Your tax calculation logic
        $taxRate = 0.05; // example 5% tax
        return $subTotal * $taxRate;
    }

    protected function validateCouponForUser(Coupon $coupon, $user)
    {
        // Validate coupon expiry, usage limit, user eligibility, etc.
        if ($coupon->end_date && now()->greaterThan($coupon->end_date)) {
            return ['status' => false, 'message' => 'Coupon expired'];
        }

        // Add your more rules here...

        return ['status' => true];
    }

    private function buildAddress($request, $type = 'billing')
    {
        return [
            'country'  => $request->input("{$type}_country"),
            'state'    => $request->input("{$type}_area"),
            'city'     => $request->input("{$type}_city"),
            'address'  => $request->input("{$type}_address"),
            'address2' => $request->input("{$type}_address2"),
        ];
    }

}

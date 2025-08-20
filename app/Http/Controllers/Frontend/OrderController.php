<?php

namespace App\Http\Controllers\Frontend;

use App\CPU\paypal;
use App\CPU\SmsHelper;
use App\Models\Cart;
use App\Models\City;
use App\Models\Order;
use App\Models\Country;
use App\Models\Payment;
use App\Models\CartDetail;
use App\Models\UserAddress;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\PricingTier;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Interface\OrderRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\CartRepositoryInterface;

class OrderController extends Controller
{
    private $orderRepository;
    private $userRepository;
    private $product;
    private $cartRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        UserRepositoryInterface $userRepository,
        ProductRepositoryInterface $product,
        CartRepositoryInterface $cartRepository,
    )
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->product = $product;
        $this->cartRepository = $cartRepository;
    }

    public function orderValidate(Request $request)
    {
        $request->validate([
            'order_id' => [
                'required',
                'regex:/^#[A-Za-z0-9]{11,13}$/',
                'exists:orders,unique_id'
            ],
        ], [
            'order_id.required' => 'The Order ID field is required.',
            'order_id.regex' => 'The Order ID must start with "#" and contain 11 to 13 digits.',
            'order_id.exists' => 'Invalid Order Id'
        ]);

        return response()->json(['valid' => true]);
    }

    public function checkout(Request $request)
    {

        // dd(Session::all());
        $countryName = Session::get('country');
        $country = Country::where('name', $countryName)->with(['city' => function ($query) {
            $query->where('status', 1)->select('id', 'name', 'country_id');
        }])->first();
        if($country) {
            $countryID = $country->id;
            $cities = $country->city;
        } else {
            $country = Country::where('name', 'Bangladesh')->with(['city' => function ($query) {
                $query->where('status', 1)->select('id', 'name', 'country_id');
            }])->first();
            $countryID = $country->id;
        }
        $cities = $country->city;
        $currencyCode = Session::get('currency_code');
        $userInfo = $this->userRepository->informations($countryID);

        $defaultAddress = null;
        $user = Auth::guard('customer')->user();

        if($user->address && $user->address()->where('is_default', 1)->exists()) {
            $defaultAddress = $user->address()->where('is_default', 1)->first();
        }

        // cart
        $items = [];
        $counter = 0;
        $total_price = 0;
        $tax_amount = 0;
        $models = [];
        // if (Auth::guard('customer')->check()) {
        //     $cart = Cart::where('user_id', Auth::guard('customer')->user()->id)->first();
        // } else {
        //     $cart = Cart::where('ip', $request->ip())->first();
        // }
        $cart = Cart::find(Session::get('cart_id'));

        if (!$cart) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::guard('customer')->user()->id ?? null, 'ip' => $request->ip()],
                ['total_quantity' => 0, 'currency_id' => 1]
            );

            Session::put('cart_id', $cart->id);
        }

        $items = CartDetail::where('cart_id', $cart->id)->get();

        $cart_updated = false;
        foreach ($items as $item) {
            $stockResponse = getProductStock($item->product_id);
            if (!$stockResponse['status']) {
                $cart_updated = true;
                $itemQuantity = $item->quantity;
                $item->delete();
                $cart->total_quantity -= $itemQuantity;
                $cart->save();
            } else {
                $product_tax_amount = 0;
                $price = $this->product->discountPrice($item->product);
                $total_price += ($price * $item->quantity);

                if ($item->product->taxes->isNotEmpty()) {
                    $amount = $item->product->taxes->where('tax_type', 'amount')->sum('tax');
                    $percent = $item->product->taxes->where('tax_type', 'percent')->sum('tax');

                    $forPercent = ($percent * $item->product->unit_price) / 100;
                    $tax_amount += ($amount + $forPercent) * $item->quantity;

                }

                $models[] = [
                    'id' => $item->id,
                    'slug' => $item->product->slug,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'shipping_cost' => $item->product->details->shipping_cost,
                    'tax' => $product_tax_amount
                ];
            }
        }

        $total_price += $tax_amount;
        if (get_settings('shipping_cost_type') != 'product_wise') {
            $shipping_charge = get_shipping_cost() ?? 10;
        } else {
            $shipping_charge = array_sum(array_column($models, 'shipping_cost'));
        }

        $total_price += $shipping_charge;

        if (count($models) == 0) {
            return redirect()->route('home')->withErrors('Your Cart is Empty!');
        }

        // for pricing tier
        $tier = null;
        if (get_settings('pricing_tier') == 1 && Session::has('currency_id')) {

            $currency_id = Session::get('currency_id');
            $tier = getApplicablePricingTier($currency_id, $models);

        }

        // dd($defaultAddress);
        return view('frontend.order.checkout', compact('userInfo', 'defaultAddress', 'shipping_charge', 'tax_amount', 'total_price', 'countryName', 'countryID', 'models', 'cities', 'currencyCode', 'tier'));
    }

    public function store(Request $request)
    {

        if (!isset($request->token) && !isset($request->PayerID)) {

            $order = $this->orderRepository->store($request);
            $this->cacheForgetter();
            if ($order['order']->is_cod || $order['order']->is_negative_balance_order) {
                
                // $request->coupon_code
                SendOrderConfirmationEmail::dispatch($this->orderRepository->details($order['order']->id))->onQueue('high');

                // Sent OTP -> Order Confirmation
                if($request->customer_phone != '' && $order['order']->payment_status == 'Paid') {
                    $template = get_settings('sms_password_reset_template');
                    $template = str_replace('[[ORDER_ID]]', $order['order']->unique_id, $template);
                    $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);

                    $sms = new SmsHelper();   
                    $sms->sendSms($request->customer_phone, $template);   
                }

                // Remove Cart Item
                $this->cartRepository->clearCart();
                
                return redirect()->route('order.confirm', str_replace('#', '', $order['order']->unique_id))->with(['success' => "Order Completed!"]);
            }
        }

        if ($request->payment_option == 'paypal' && $order['order']->id) {
            $payment = paypal::processPayment($request->currency_code, $request->totalAmount, $order['order']->unique_id);
        }

        if ($request->payment_option == 'sslcommerz' && $order['order']->id) {
            return redirect()->route('sslcommerz.process', [
                'currency_code' => $request->currency_code,
                'subtotal' => round($request->totalAmount, 2),
                'transaction_id' => $order['order']->unique_id,
                'address' => $request->billing_address . ',' . $request->billing_area,
                'phone' => $request->customer_phone,
                'name' => $request->customer_name,
                'email' => $request->customer_email,
            ]);
        }
        if (json_decode($payment->getContent())) {
            $err = json_decode($payment->getContent())->error;
            $errMSG = is_array(json_decode($err)->details) ? json_decode($err)->details[0]->issue : $err;
            return redirect()->back()->with(['error' => str_replace('_', ' ', $errMSG)]);
        } elseif (is_array($payment) && isset($payment['approval_url'])) {
            return redirect($payment['approval_url']);
        }


        if (isset($request->token) && isset($request->PayerID)) {
            $capture = paypal::capturePayment($request->token);
            $capture_contents = json_decode($capture->getContent());

            if (isset($capture_contents->details->status) && $capture_contents->details->status === 'COMPLETED') {
                // Update payment information
                $pay = Payment::where('payment_order_id', $capture_contents->details->id)->first();

                if ($pay) {
                    $pay->update([
                        'email' => $capture_contents->details->payer->email_address,
                        'payer_id' => $capture_contents->details->payer->payer_id,
                        'status' => $capture_contents->details->status,
                    ]);

                    // Update order status
                    $order = Order::with('details')->where('unique_id', $pay->payment_unique_id)->first();
                    $order->update([
                        'payment_id' => $pay->id,
                        'payment_status' => 'Paid',
                    ]);
                    $details = json_decode($order->details->details);
                    $this->orderRepository->updateStockByOrderId($order->id, 'order');
                    // Dispatch the email job to the 'high' queue
                    SendOrderConfirmationEmail::dispatch($this->orderRepository->details($order->id))->onQueue('high');

                    // Remove Cart Item
                    $this->cartRepository->clearCart();
                    
                    return redirect()->route('order.confirm', $order->unique_id)->with(['success' => "Order Completed!"]);
                }
            } else {
                return redirect()->back()->with(['error' => json_decode($capture_contents->error)->details[0]->issue]);
            }
        }
        return redirect()->back()->with(['error' => 'Something Went Wrong!']);
    }

    public function orderConfirmation($id)
    {
        $id = '#' . $id;
        $order = Order::where('unique_id', $id)->firstOrFail();
        $details = $this->orderRepository->details(decode(encode($order->id)));


        return view('frontend.order-confirmation', compact('details'));
    }

    public function orderTrackingInformation($id)
    {
        $id = '#' . $id;
        $order = Order::where('unique_id', $id)->firstOrFail();
        $details = $this->orderRepository->details(decode(encode($order->id)));

        $statusArray = ['pending'];
        if ($order->status == 'packaging') {
            $statusArray = ['pending', 'packaging'];
        } elseif ($order->status == 'shipping') {
            $statusArray = ['pending', 'packaging', 'shipping'];
        } elseif ($order->status == 'out_of_delivery') {
            $statusArray = ['pending', 'packaging', 'shipping', 'out_of_delivery'];
        } elseif ($order->status == 'delivered') {
            $statusArray = ['pending', 'packaging', 'shipping', 'out_of_delivery', 'delivered'];
        } elseif ($order->status == 'returned') {
            $statusArray = ['pending', 'packaging', 'shipping', 'out_of_delivery', 'delivered', 'returned'];
        } elseif ($order->status == 'failed') {
            $statusArray = ['failed'];
        }

        return view('frontend.order-tracking', compact('order', 'statusArray', 'details'));
    }

    public function address($id)
    {
        $address = UserAddress::find($id);
        if (!isset($address)) {
            return response()->json(['success' => false, 'massage' => 'Address Not Found']);
        }
        return response()->json(['success' => true, 'address' => $address]);
    }

    public function reOrder(Request $request)
    {
        $orderId = '#' . $request->order_id;

        $order = Order::where('unique_id', $orderId)->first();
        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Invalid Order']);
        }

        if (!$order->details) {
            return response()->json(['status' => false, 'message' => 'Invalid Order']);
        }

        $productIds = json_decode($order->details->product_ids);
        if (!is_array($productIds) && count($productIds) == 0) {
            return response()->json(['status' => false, 'message' => 'No Product Found on this Order']);
        }

        foreach ($productIds as $productId) {
            $product = $this->product->getProductById($productId);
            if ($product) {
                $request->merge(['slug' => $product->slug]);
                $this->cartRepository->addToCart($request);
            }
        }
        $this->cacheForgetter();
        return response()->json(['status' => true, 'message' => 'Item added to cart successfully.']);

    }

    private function cacheForgetter()
    {
        $ranges = ['daily', 'weekly', 'monthly', 'yearly'];
        $statuses = ['pending', 'packaging', 'shipping', 'out_of_delivery', 'delivered', 'returned', 'failed'];

        foreach ($ranges as $range) {
            foreach ($statuses as $status) {
                Cache::forget('orderData_' . $range . '_' . $status);
            }
        }
        return true;
    }
}

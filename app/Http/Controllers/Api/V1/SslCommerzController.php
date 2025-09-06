<?php

namespace App\Http\Controllers\Api\V1;

use App\CPU\SmsHelper;
use App\Http\Controllers\Controller;
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Interface\CartRepositoryInterface;
use App\Repositories\Interface\OrderRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SslCommerzController extends Controller
{
    private $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartRepositoryInterface $cartRepository,
    )
    {
        $this->orderRepository = $orderRepository;
    }

    public function order(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'subtotal' => 'required|numeric',
            'currency_code' => 'required|string',
        ]);

        $client = new Client();

        // Determine the API URL based on the sandbox setting
//        $url = env('SSLCOMMERZ_SANDBOX') ?
//            'https://sandbox.sslcommerz.com/gwprocess/v4/api.php' :
//            'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
        $url ='https://sandbox.sslcommerz.com/gwprocess/v4/api.php';

        // Prepare the data for the payment request
        $data = [
            'store_id' => env('SSLCOMMERZ_STORE_ID'),
            'store_passwd' => env('SSLCOMMERZ_STORE_PASSWORD'),
            'total_amount' => $request->subtotal,
            'currency' => $request->currency_code,
            'tran_id' => str_replace('#', '', $request->transaction_id),
            'success_url' => route('api.sslcommerz.success', ['user_id' => $request->user_id]),
            'fail_url' => route('api.sslcommerz.failure'),
            'cancel_url' => route('api.sslcommerz.cancel'),
            'product_name' => 'Order' . $request->transaction_id, // Example product name
            'cus_name' => $request->name, // Replace with actual customer name
            'cus_email' => $request->email,
            'cus_add1' => $request->address ?? Session::get('user_city') . ',' . Session::get('user_country'),
            'cus_city' => Session::get('user_city') ?? "Dhaka",
            'cus_phone' => $request->phone,
            'shipping_method' => "NO",
            'product_category' => "Electronic",
            'product_profile' => "General",
            'cus_country' => Session::get('user_country')??'Bangladesh',
            // 'ipn_url' => env('SSLCOMMERZ_ALLOW_LOCALHOST') === true
            // ? 'http://127.0.0.1:8000/sslcommerz/ipn'
            // : route('sslcommerz.ipn'),
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
            // Decode the response from SSLCommerz
            $result = json_decode($response->getBody()->getContents(), true);
            // Check for GatewayPageURL and redirect
            if (isset($result['GatewayPageURL']) && isset($result['status']) && $result['status']=='SUCCESS') {
                return response()->json([ 'status' => true, 'gateway' => $result['GatewayPageURL'] . '?user_id=' . $request->user_id]);

            } else {
                return response()->json(['error' => 'Payment gateway URL not found.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');
        $amount = $request->input('amount');
        $store_id = env('SSLCOMMERZ_STORE_ID');
        $store_passwd = env('SSLCOMMERZ_STORE_PASSWORD');
        $order = Order::where('unique_id', '#' .preg_replace('/RTP\d+$/', '', $tran_id))->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'error' => 'Order not found',
            ], 404);
        }
        $client = new Client();
//        $validation_url = env('SSLCOMMERZ_SANDBOX', true)
//            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
//            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';
        $validation_url = 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php';

        $response = $client->get($validation_url, [
            'query' => [
                'val_id' => $val_id,
                'store_id' => $store_id,
                'store_passwd' => $store_passwd,
                'v' => 1,
                'format' => 'json',
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        if ($result['status'] === 'VALID' || $result['status'] === 'VALIDATED') {

            // Create payment record
            $pay = Payment::create([
                'user_id' => $order->user_id,
                'trx_id' => $tran_id,
                'amount' => $amount,
                'currency' => $result['currency'],
                'payer_id' => $result['card_no'] ?? ($result['card_ref_id'] ?? null),
                'gateway_name' => 'SslCommerz ' . ($result['card_type'] ? '-' . $result['card_type'] : ''),
                'status' => $result['status'],
                'payment_unique_id' => $result['val_id'] ?? null,
                'payment_order_id' => '#' . $tran_id,
            ]);

            // Update order status
            $order->payment_status = 'Paid';
            $order->payment_id = $pay->id;
            $order->status = 'pending';
            $order->save();
            $this->orderRepository->updateStockByOrderId($order->id, 'order');

            if ($order->user_id) {
                $user = User::find($order->user_id);
                if ($user) {
                        $token = $user->createToken('api_token')->plainTextToken;
                        return response()->json([
                            'status' => true,
                            'message' => 'Order Successful',
                            'new_token'=>$token,
                            'order'=>$order,
                        ]);
                }
            }

        }
        if ($order->user_id) {
            $user = User::find($order->user_id);
            if ($user) {
                $pay = Payment::create([
                    'user_id' => $order->user_id,
                    'trx_id' => $tran_id,
                    'amount' => $amount,
                    'currency' => $result['currency'],
                    'gateway_name' => 'SslCommerz',
                    'status' => $result['status'],
                    'payment_order_id' => '#' . $tran_id,
                ]);
                $order->payment_id = $pay->id;
                $order->save();
                $token = $user->createToken('api_token')->plainTextToken;
                return response()->json([
                    'status' => false,
                    'message' => 'Payment validation failed',
                    'new_token'=>$token
                ]);
            }
        }

    }


    public function failure(Request $request)
    {
        $order = Order::where('unique_id', "#" . $request->tran_id)->first();
        if ($order &&$order->user_id) {
            $user = User::find($order->user_id);
            if ($user) {
                $pay = Payment::create([
                    'user_id' => $order->user_id,
                    'trx_id' => $request->tran_id,
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'gateway_name' => 'SslCommerz',
                    'status' => $request->status,
                    'payment_order_id' => '#' . $request->tran_id,
                ]);
                $order->payment_id = $pay->id;
                $order->status = 'failed';
                $order->save();
                $token = $user->createToken('api_token')->plainTextToken;
                return response()->json([
                    'status' => false,
                    'message' => 'Order Payment Failed',
                    'new_token'=>$token
                ]);
            }

        }
        return response()->json([
            'status' => false,
            'error' => 'Order not found',
        ], 404);
    }

    public function cancel(Request $request)
    {
        $order = Order::where('unique_id', "#" . $request->tran_id)->first();
        if ($order && $order->user_id) {
                $user = User::find($order->user_id);
                if ($user) {
                    $pay = Payment::create([
                        'user_id' => $order->user_id,
                        'trx_id' => $request->tran_id,
                        'amount' => $request->amount,
                        'currency' => $request->currency,
                        'gateway_name' => 'SslCommerz',
                        'status' => $request->status,
                        'payment_order_id' => '#' . $request->tran_id,
                    ]);
                    $order->payment_id = $pay->id;
                    $order->status = 'failed';
                    $order->save();
                    $token = $user->createToken('api_token')->plainTextToken;
                    return response()->json([
                        'status' => false,
                        'message' => 'Order Payment Cancelled',
                        'new_token'=>$token
                    ]);
                }

        }
        return response()->json([
            'status' => false,
            'error' => 'Order not found',
        ], 404);
    }


    public function ipn(Request $request)
    {
        Log::info('SSLCommerz IPN Callback:', $request->all());

        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');
        $amount = $request->input('amount');
        $status = $request->input('status');
        $store_id = env('SSLCOMMERZ_STORE_ID');
        $store_passwd = env('SSLCOMMERZ_STORE_PASSWORD');

        // Check for existing payment to avoid duplicates
        $existingPayment = Payment::where('trx_id', $tran_id)->first();
        if ($existingPayment) {
            Log::info('IPN: Payment already processed for tran_id: ' . $tran_id);
            return response()->json(['status' => 'already processed'], 200);
        }

        // Verify payment with SSLCommerz API
        $client = new Client();
        $validation_url = env('SSLCOMMERZ_SANDBOX', true)
            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';

        try {
            $response = $client->get($validation_url, [
                'query' => [
                    'val_id' => $val_id,
                    'store_id' => $store_id,
                    'store_passwd' => $store_passwd,
                    'v' => 1,
                    'format' => 'json',
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if ($result['status'] === 'VALID' || $result['status'] === 'VALIDATED') {
                $order = Order::where('unique_id', $tran_id)->first();
                if ($order && $order->subtotal == $amount) {
                    Payment::create([
                        'user_id' => $order->user_id,
                        'trx_id' => $tran_id,
                        'amount' => $amount,
                        'currency' => $result['currency'],
                        'payer_id' => $result['card_no'] ?? ($result['card_ref_id'] ?? null),
                        'gateway_name' => 'SslCommerz ' . ($result['card_type'] ? '.' . $result['card_type'] : ''),
                        'status' => $result['status'],
                        'payment_unique_id' => $result['val_id'] ?? null,
                        'payment_order_id' => '#' . $tran_id,
                    ]);

                    $order->payment_status = 'Paid';
                    $order->status = 'Pending';
                    $order->save();

                    Log::info('IPN: Payment processed successfully for tran_id: ' . $tran_id);
                    return response()->json(['status' => 'success'], 200);
                }
                Log::error('IPN: Order mismatch for tran_id: ' . $tran_id);
                return response()->json(['error' => 'Order mismatch'], 400);
            }

            Log::error('IPN: Payment validation failed for tran_id: ' . $tran_id, $result);
            return response()->json(['error' => 'Payment validation failed'], 400);
        } catch (\Exception $e) {
            Log::error('IPN: Error processing request for tran_id: ' . $tran_id, ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Processing error'], 500);
        }
    }
}

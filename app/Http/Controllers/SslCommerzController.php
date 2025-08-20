<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SslCommerzController extends Controller
{
    public function order(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'subtotal' => 'required|numeric',
            'currency_code' => 'required|string',
        ]);

        $client = new Client();

        // Determine the API URL based on the sandbox setting
        $url = env('SSLCOMMERZ_SANDBOX') ?
            'https://sandbox.sslcommerz.com/gwprocess/v4/api.php' :
            'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        // Prepare the data for the payment request
        $data = [


            'store_id' => env('SSLCOMMERZ_STORE_ID'),
            'store_passwd' => env('SSLCOMMERZ_STORE_PASSWORD'),
            'total_amount' => $request->subtotal,
            'currency' => $request->currency_code,
            'tran_id' => str_replace('#', '', $request->transaction_id),
            'success_url' => route('sslcommerz.success'),
            'fail_url' => route('sslcommerz.failure'),
            'cancel_url' => route('sslcommerz.cancel'),
            'product_name' => 'Order' . $request->transaction_id, // Example product name
            'cus_name' => $request->name, // Replace with actual customer name
            'cus_email' => $request->email,
            'cus_add1' => $request->address ?? Session::get('user_city') . ',' . Session::get('user_country'),
            'cus_city' => Session::get('user_city') ?? "Dhaka",
            'cus_phone' => $request->phone,
            'shipping_method' => "NO",
            'product_category' => "Electronic",
            'product_profile' => "General",
            'cus_country' => Session::get('user_country'),
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
            if (isset($result['GatewayPageURL'])) {
                return redirect()->to($result['GatewayPageURL']);
            } else {
                return response()->json(['error' => 'Payment gateway URL not found.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Request $request)
    {
        dd($request->all());
        \Log::info('SSLCommerz Success Callback:', $request->all());

        // Check if CSRF token is valid
        if (!$request->isMethod('post') || !$request->has('_token') || $request->input('_token') !== session()->token()) {
            \Log::warning('CSRF Token Mismatch');
            abort(419); // This will handle the CSRF failure
        }

        // Regenerate the session token
        session()->regenerateToken();

        // Continue with your payment processing logic
    }



    public function failure(Request $request)
    {
        // Handle payment failure
        $order = Order::where('unique_id', $request->tran_id)->first();
        if ($order) {
            $order->status = 'Failed';
            $order->save();
        }

        return "Payment failed!";
    }

    public function cancel(Request $request)
    {
        // Handle payment cancellation
        $order = Order::where('unique_id', $request->tran_id)->first();
        if ($order) {
            $order->status = 'Cancelled';
            $order->save();
        }

        return "Payment was cancelled!";
    }

    public function ipn(Request $request)
    {
        // Handle Instant Payment Notification (IPN) if needed
    }
}

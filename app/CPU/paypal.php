<?php

namespace App\CPU;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;


class paypal
{
    public static function processPayment($currencyCode, $amount, $uID)
    {
        try {
            //  sandbox
            $SandboxEnvironment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID_SANDBOX'), env('PAYPAL_SECRET_SANDBOX'));
            //    Live
            $ProductionEnvironment = new ProductionEnvironment(env('PAYPAL_CLIENT_ID_LIVE'), env('PAYPAL_SECRET_LIVE'));

            $paypalEnvironment = env('PAYPAL_SANDBOX_MODE') ? $SandboxEnvironment : $ProductionEnvironment;
            $client = new PayPalHttpClient($paypalEnvironment);


            // Create Payment
            $createRequest = new OrdersCreateRequest();
            $createRequest->prefer('return=representation');
            $createRequest->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [[
                    "amount" => [
                        "currency_code" => $currencyCode,
                        "value" => round($amount, 2)
                    ]
                ]],

                "application_context" => self::buildApplicationContext(0)
            ];

            // Execute the create payment request
            $createResponse = $client->execute($createRequest);
            // Check if order approval is required
            if ($createResponse->statusCode === 201 && $createResponse->result->status === 'CREATED') {
                Payment::create([
                    'user_id' => Auth::guard('customer')->id(),
                    'amount' => round($amount, 2),
                    'currency' => $currencyCode,
                    'gateway_name' => 'Paypal',
                    'status' => $createResponse->result->status,
                    'payment_order_id' => $createResponse->result->id,
                    'payment_unique_id' => $uID,

                ]);
                // Return the approval URL and unique ID in the response
                return [
                    'approval_url' => $createResponse->result->links[1]->href,
                    'order_id' => $createResponse->result->id,
                ];
            } else {
                // Return error response if payment creation failed
                return ['error' => json_decode(json_decode($createResponse->getContent())->error)->details[0]->issue];
            }
        } catch (\Exception $ex) {
            // Handle exceptions
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public static function capturePayment($orderId)
    {
        try {
            //  sandbox
            $paypalEnvironment = new SandboxEnvironment(env('PAYPAL_CLIENT_ID_SANDBOX'), env('PAYPAL_SECRET_SANDBOX'));
            //    Live
            // $paypalEnvironment= new ProductionEnvironment(env('PAYPAL_CLIENT_ID_LIVE'), env('PAYPAL_SECRET_LIVE'));
            $client = new PayPalHttpClient($paypalEnvironment);

            // Capture Payment
            $captureRequest = new OrdersCaptureRequest($orderId);
            $captureRequest->prefer('return=representation');

            // Execute the capture request
            $captureResponse = $client->execute($captureRequest);

            if ($captureResponse->statusCode === 201) {
                return response()->json(['status' => 'Payment captured successfully', 'details' => $captureResponse->result]);
            } else {
                return response()->json(['error' => 'Failed to capture payment'], 400);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
    private static function buildApplicationContext($orderID)
    {
        return [
            "cancel_url" => route('order.checkout'),
            "return_url" => route('order.store')
        ];
    }

    // public function returnPayment(Request $request)
    // {

    //     try {

    //         // Retrieve the unique ID from the request
    //         $uniqueId = $request->input('unique_id');

    //         if (!$uniqueId) {
    //             return response()->json(['error' => 'Unique ID not provided'], 400);
    //         }
    //         // Retrieve the corresponding PayPal order from the database using the unique ID
    //         $order = PayPalOrder::where('unique_id', $uniqueId)->first();

    //         if (!$order) {
    //             return response()->json(['error' => 'Order ID not found'], 400);
    //         }

    //         // Capture Payment
    //         $client = new PayPalHttpClient(Helpers::getPayPalEnvironment());
    //         $captureRequest = new OrdersCaptureRequest($order->order_id);
    //         $captureResponse = $client->execute($captureRequest);

    //         // Delete the PayPal order
    //         $order->delete();
    //         // Check if payment was successful
    //         if ($captureResponse->statusCode == 201) {

    //             // dd($request->input('user'), $captureResponse,$captureResponse->result->purchase_units[0]->payments->captures[0]->amount->value);
    //             $data = $captureResponse->result;

    //             $paymentHistory = new PaymentHistory();
    //             $paymentHistory->payment_id = $data->id;
    //             $paymentHistory->user_id = $request->user;
    //             $paymentHistory->status = $data->status;
    //             $paymentHistory->amount = $data->purchase_units[0]->payments->captures[0]->amount->value;
    //             $paymentHistory->email_address = $data->payer->email_address;
    //             $paymentHistory->account_status = $data->payment_source->paypal->account_status;
    //             $paymentHistory->payer_id = $data->payer->payer_id;

    //             if ($data->status === 'COMPLETED') {

    //                 if($subscription->type==='year'){
    //                     $paymentHistory->subscription_end_date =  Carbon::now()->addYear($subscription->duration);
    //                 }elseif($subscription->type==='month'){
    //                     $paymentHistory->subscription_end_date =  Carbon::now()->addMonth($subscription->duration);
    //                 }elseif($subscription->type==='day'){
    //                     $paymentHistory->subscription_end_date =  Carbon::now()->addDays($subscription->duration);

    //                 }else{
    //                     $paymentHistory->subscription_end_date =  Carbon::now()->addDays(30);
    //                 }

    //                 $paymentHistory->save();

    //                 // Update user to premium
    //                 $user = User::find($request->user);
    //                 $user->isPremium = true;
    //                 $user->subscription_id=$subscription->id;
    //                 $user->save();
    //             } else {
    //                 $paymentHistory->save();
    //             }


    //             // Payment successful
    //             return response()->json(['message' => 'Payment successful'], 200);
    //         } else {
    //             return response()->json(['error' => 'Payment capture failed'], 400);
    //         }
    //     } catch (\Exception $ex) {
    //         // Handle exceptions
    //         return response()->json(['error' => $ex->getMessage()], 500);
    //     }
    // }
    public function cancelPayment(Request $request)
    {
        try {
            // You can implement your cancellation logic here
            return response()->json(['message' => 'Payment canceled'], 200);
        } catch (\Exception $ex) {
            // Handle exceptions
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}

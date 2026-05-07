<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPlaceOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true; // already protected by auth:sanctum middleware
    }

    public function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            // 'billing_country' => 'required|string',
            'billing_area' => 'nullable|string',
            'billing_city' => 'required|string',
            // 'billing_city' => 'required|integer|exists:cities,id',
            'billing_address' => 'required|string|max:500',
            'billing_address2' => 'nullable|string|max:500',
            'different_shipping_address' => 'nullable|boolean',
            // 'country_name' => 'required_if:different_shipping_address,true|string',
            'shipping_area' => 'nullable|string|required_if:different_shipping_address,true',
            'shipping_city' => 'nullable|string|required_if:different_shipping_address,true',
            // 'shipping_city' => 'nullable|integer|required_if:different_shipping_address,true|exists:cities,id',
            'shipping_address' => 'nullable|string|required_if:different_shipping_address,true|max:500',
            'shipping_address2' => 'nullable|string|max:500',
            'order_notes' => 'nullable|string|max:1000',
            'coupon_code' => 'nullable|string',
            'delivery_method' => 'required|string|in:home_delivery,store_pickup',
            'payment_option' => 'required|string|in:cash_on_delivery,sslcommerz,manual_pay,negative_balance',
            'currency_code' => 'required|string|max:10',
        ];
    }
}

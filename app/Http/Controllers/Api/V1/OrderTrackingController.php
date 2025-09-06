<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Repositories\Interface\OrderRepositoryInterface;
use Illuminate\Support\Carbon;

class OrderTrackingController extends Controller
{
    private $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
    ) {
        $this->orderRepository = $orderRepository;
    }

    public function trackOrder($id)
    {
        // Add '#' prefix if needed (based on your unique_id format)
        $uniqueId = '#' . $id;

        // Fetch order by unique_id or fail
        $order = Order::where('unique_id', $uniqueId)->with('statusHistory')->firstOrFail();

        // Get order details from your repository or directly, adjust if needed
        $details = $this->orderRepository->details(decode(encode($order->id)));

        // Prepare ordered items with thumb image URL
        $items = [];
        foreach ($details['details'] as $d) {
            $product = \App\Models\Product::select('thumb_image')->find($d->id);
            $items[] = [
                'id' => $d->id,
                'name' => $d->name,
                'slug' => $d->slug,
                'thumb_image_url' => $product ? asset($product->thumb_image) : null,
                'qty' => $d->qty,
                'unit_price' => $d->unit_price,
                'total_price' => $d->total_price,
            ];
        }

        // Prepare billing info
        $billingInfo = [
            'premium_user_discount_amount' => $details['premium_user_discount_amount'] ?? null,
            'subtotal' => $details['order_amount'],
            'shipping_fee' => $details['shipping_charge'],
            'tax' => $details['tax_amount'],
            'discount' => $details['discount_amount'],
            'total' => $details['final_amount'],
        ];

        // Helper to format datetime or return null
        $formatDateTime = function($dt) {
            if (!$dt) return null;
            // If $dt is a string, parse it as Carbon
            return Carbon::parse($dt)->format('Y-m-d H:i:s');
        };


        // Build tracking history array
        $trackingHistory = [
            [
                'status' => 'new_order',
                'title' => 'Order Placed',
                'icon_url' => asset('pictures/svg/order-placed.png'),
                'time' => $order->statusHistory ? $formatDateTime($order->statusHistory->pending_time) : null,
                'is_current' => $order->status === 'new_order',
            ],
            [
                'status' => 'pending',
                'title' => 'Pending Order',
                'icon_url' => asset('pictures/svg/order-placed.png'),
                'time' => $order->statusHistory ? $formatDateTime($order->statusHistory->pending_time) : null,
                'is_current' => $order->status === 'pending',
            ],
            [
                'status' => 'packaging',
                'title' => 'Packed the product',
                'icon_url' => asset('pictures/svg/order-packaging.png'),
                'time' => $order->statusHistory ? $formatDateTime($order->statusHistory->packaging_time) : null,
                'is_current' => $order->status === 'packaging',
            ],
            [
                'status' => 'shipping',
                'title' => 'Arrived in the warehouse',
                'icon_url' => asset('pictures/svg/order-in-shipping.png'),
                'time' => $order->statusHistory ? $formatDateTime($order->statusHistory->shipping_time) : null,
                'is_current' => $order->status === 'shipping',
            ],
            [
                'status' => 'out_of_delivery',
                'title' => 'Out for Delivery',
                'icon_url' => asset('pictures/svg/order-in-delivery.svg'),
                'time' => $order->statusHistory ? $formatDateTime($order->statusHistory->out_for_delivery_time) : null,
                'is_current' => $order->status === 'out_of_delivery',
            ],
            [
                'status' => 'delivered',
                'title' => 'Delivered',
                'icon_url' => asset('pictures/svg/delivered.png'),
                'time' => $order->statusHistory ? $formatDateTime($order->statusHistory->delivered_time) : null,
                'is_current' => $order->status === 'delivered',
            ],
            [
                'status' => 'returned',
                'title' => 'Returned',
                'icon_url' => asset('pictures/svg/return.png'),
                'time' => $order->statusHistory ? $formatDateTime($order->statusHistory->returned_time) : null,
                'is_current' => $order->status === 'returned',
            ],
            [
                'status' => 'failed',
                'title' => 'Order Failed',
                'icon_url' => asset('pictures/svg/cancelled.png'),
                'time' => null,
                'is_current' => $order->status === 'failed',
            ],
        ];

        // Filter out statuses that have no time and not current (optional)
        $trackingHistory = array_filter($trackingHistory, function($item) {
            return $item['time'] !== null || $item['is_current'] === true;
        });

        // Prepare final response array
        $response = [
            'status' => true,
            'data' => [
                'order_id' => $order->unique_id,
                'status' => $order->status,
                'shipping_info' => [
                    'user_name' => $details['user_name'],
                    'billing_address' => $details['billing_address'],
                    'phone' => $details['phone'],
                    'user_company' => $details['user_company'] ?? null,
                    'email' => $details['email'],
                ],
                'items_ordered' => $items,
                'billing_info' => $billingInfo,
                'tracking_history' => array_values($trackingHistory),
            ],
        ];

        return response()->json($response);
    }

}

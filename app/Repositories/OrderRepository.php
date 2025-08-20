<?php

namespace App\Repositories;

use App\CPU\SmsHelper;
use App\Events\OrderCreated;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\ProductDetail;
use App\Models\UserNegetiveBalanceWallet;
use App\Models\City;
use App\Models\Order;
use App\Models\Country;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\UserBroughtCoupon;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Repositories\Interface\OrderRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class OrderRepository implements OrderRepositoryInterface
{
    public function index($request)
    {
        return Order::when(isset($request->status) && $request->status == 'pending', function ($q) {
            $q->where('status', 'pending');
        })
            ->when(isset($request->status) && $request->status == 'packaging', function ($q) {
                $q->where('status', 'packaging');
            })
            ->when(isset($request->status) && $request->status == 'shipping', function ($q) {
                $q->where('status', 'shipping');
            })
            ->when(isset($request->status) && $request->status == 'out_of_delivery', function ($q) {
                $q->where('status', 'out_of_delivery');
            })
            ->when(isset($request->status) && $request->status == 'delivered', function ($q) {
                $q->where('status', 'delivered')->where('is_delivered', 1);
            })
            ->when(isset($request->status) && $request->status == 'returned', function ($q) {
                $q->where('status', 'returned');
            })
            ->when(isset($request->status) && $request->status == 'failed', function ($q) {
                $q->where('status', 'failed');
            })
            ->when(isset($request->payment_status) && $request->payment_status == 'Paid', function ($q) {
                $q->where('payment_status', 'Paid');
            })
            ->when(isset($request->payment_status) && $request->payment_status == 'Paid', function ($q) {
                $q->where('payment_status', 'Paid');
            })
            ->when(isset($request->status) && $request->status == 'refund_requested', function ($q) {
                $q->where('is_refund_requested', 1);
            })->with('details:id,order_id,phone,email', 'user:id,name', 'payment:id,currency,gateway_name', 'currency:id,code,symbol')->get()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'unique_id' => $order->unique_id,
                    'user_name' => $order->user->name,
                    'phone' => $order->details->phone,
                    'email' => $order->details->email,
                    'currency' => isset($order->payment->currency) ? $order->payment->currency : $order->currency->code,
                    'currency_symbol' => isset($order->currency->symbol) ? $order->currency->symbol : null,
                    'gateway_name' => $order->is_cod ? 'Cash on Delivery' : (isset($order->payment->gateway_name) ? $order->payment->gateway_name : null),
                    'payment_status' => $order->payment_status,
                    'status' => $order->is_refund_requested ? "Refund Requested" : $order->status,
                    'amount' => $order->final_amount * $order->exchange_rate,
                    'created_at' => $order->created_at
                ];
            });
    }

    public function userOrders()
    {
        return Order::where('user_id', Auth::guard('customer')->id())->select('id', 'unique_id', 'final_amount', 'exchange_rate', 'payment_status', 'status', 'created_at', 'payment_id', 'currency_id', 'is_cod', 'is_refund_requested')->with('payment:id,currency,gateway_name', 'currency:id,code,symbol')->latest()->paginate()->map(function ($order) {
            return [
                'id' => $order->id,
                'unique_id' => $order->unique_id,
                'currency' => isset($order->payment->currency) ? $order->payment->currency : @$order->currency->code,
                'currency_symbol' => isset($order->currency->symbol) ? $order->currency->symbol : null,
                'gateway_name' => $order->is_cod ? 'Cash on Delivery' : (isset($order->payment->gateway_name) ? $order->payment->gateway_name : null),
                'payment_status' => $order->payment_status,
                'status' => $order->is_refund_requested ? "Refund Requested" : $order->status,
                'amount' => $order->final_amount * $order->exchange_rate,
                'created_at' => $order->created_at->format('d M Y, h:i:s A')
            ];
        });
    }

    public function userData()
    {
        $userId = Auth::guard('customer')->id();

        // Use a single query with selective columns and proper aggregations
        $data = Order::where('user_id', $userId)
            ->selectRaw('
                COUNT(*) as total_orders,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_orders,
                SUM(CASE WHEN status NOT IN ("returned", "failed") THEN final_amount ELSE 0 END) as total_amount
            ')
            ->first();

        return [
            'total_orders' => isset($data->total_orders) ? $data->total_orders : 0,
            'pending_orders' => isset($data->pending_orders) ? $data->pending_orders : 0,
            'total_amount' => round($data->total_amount, 3) !== null ? round($data->total_amount, 3) : 0,
        ];
    }

    public function indexDatatable($models)
    {
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('status', function ($model) {
                if ($model['status'] == 'pending') {
                    $badge = 'warning text-dark';
                } elseif ($model['status'] == 'delivered') {
                    $badge = 'success';
                } elseif ($model['status'] == 'packaging') {
                    $badge = 'info text-dark';
                } elseif ($model['status'] == 'shipping' || $model['status'] == 'out_of_delivery') {
                    $badge = 'info text-dark';
                } else {
                    $badge = 'danger';
                }
                return '<div class="text-center"><span class="badge bg-' . $badge . '">' . ucfirst($model['status']) . '</div>';
            })->editColumn('payment_status', function ($model) {
                $paymentBadge = $model['payment_status'] == 'Paid' ? 'success' : 'danger';
                return '<div class="text-center"><span class="badge bg-' . $paymentBadge . ' text-white">' . str_replace('_', ' ', ucfirst($model['payment_status'])) . '</div>';
            })
            ->editColumn('gateway_name', function ($model) {
                $gatewayBadge = $model['gateway_name'] == 'Cash on Delivery' ? 'dark' : 'success';
                return '<div class="text-center"><span class="badge bg-' . $gatewayBadge . ' text-white">' . ucfirst($model['gateway_name']) . '</div>';
            })->editColumn('unique_id', function ($model) {

                return '<a class="dropdown-item" href="' . route('admin.order.invoice', $model['id']) . '">
                <i class="bi bi-receipt"></i>
               ' . strtoupper(str_replace('#', '', $model['unique_id'])) . '
                </a>';
            })
            ->editColumn('amount', function ($model) {

                return $model['currency_symbol'] . round($model['amount'], 2);
            })
            ->editColumn('created_at', function ($model) {

                return preg_replace('/,/', ',<br>', $model['created_at']->format('d M Y, h:i:s A'), 1);
            })
            ->addColumn('customer', function ($model) {
                return ' <div class="row">
                            <div class="col-md-12">' . $model['user_name'] . '</div>
                            <div class="col-md-12">' . $model['email'] . '</div>
                        </div>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.order.action', compact('model'));
            })
            ->rawColumns(['action', 'unique_id', 'status', 'customer', 'payment_status', 'gateway_name', 'amount', 'created_at'])
            ->make(true);
    }

    public function store($request)
    {

        // Step 1: Validate and Modify Data
        $this->validateRequest($request);
        $getProductsData = $this->productsdata($request['product'], $request['country_id'], isset($request['shipping_city']) ? $request['shipping_city'] : $request['billing_city']);

        $productIds = $getProductsData->pluck('id')->toArray();
        $details['products'] = $getProductsData->map(function ($item) {
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

        $details['company_name'] = $request['customer_company'];
        $details['user_name'] = $request['customer_name'];
        $details['shipping_charge'] = convert_price_to_usd($request['shipping_charge']);
        if (isset($request->saved)) {
            $details['premium_user_order'] = true;
            $details['premium_user_discount_amount'] = $request['saved'];

        }

        $billingAddress = $this->generateAddress($request, 'billing');
        $shippingAddress = isset($request['different_shipping_address'])
            ? $this->generateAddress($request, 'shipping')
            : $billingAddress;

        try {
            DB::beginTransaction();

            // Here if the coupon is valid then $request->discount && $request->totalAmount will be replaced.
            $couponId = $this->checkCoupon($request);

            // Multi Tier
            $tierInfo = $this->multiTierApplied($request);

            // Step 2: Create the order
            $order = Order::create([
                'unique_id' => uniqid('#'),
                'payment_id' => null,
                'user_id' => Auth::guard('customer')->user()->id,
                'order_amount' => round(convert_price_to_usd(($request['totalAmount'] + $request['discount'] + ($tierInfo['order_discount_amount'] ?? 0)) - ($request['total_tax'] + $request['shipping_charge'])), 2),
                'tax_amount' => round(convert_price_to_usd($request['total_tax']), 2),
                'discount_amount' => round(convert_price_to_usd($request['discount']), 2),
                'final_amount' => round(convert_price_to_usd($request['totalAmount']), 2),
                'exchange_rate' => get_exchange_rate(Session::get('currency_code')),
                'currency_id' => Session::get('currency_id') ?? Auth::guard('customer')->user()->currency_id,
                'payment_status' => 'Not_Paid',
                'status' => 'pending',
                'is_delivered' => false,
                'is_cod' => $request['payment_option'] === 'cash_on_delivery',
                'is_negative_balance_order' => $request['payment_option'] === 'negative_balance',
                'is_refund_requested' => false,
            ]);

            // If order is created => create status table
            $this->createOrUpdateOrderStatus($order, 'pending');

            // Step 4: Create the order details
            OrderDetail::create([
                'order_id' => $order->id,
                'product_ids' => json_encode($productIds),
                'details' => json_encode($details),
                'notes' => isset($request['notes']) ? $request['notes'] : null,
                'shipping_method' => isset($request['shipping_method']) ? $request['shipping_method'] : 'Default',
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'phone' => $request['customer_phone'],
                'email' => $request['customer_email'],
                'coupon_id' => $couponId,
                'tier_info' => $tierInfo != null ? json_encode($tierInfo) : null,
            ]);

            // Step 5: Adjust Stock for Paid Orders
            // if(!$order->is_cod){
            //     $this->adjustStock($getProductsData);
            // }
            if ($request['payment_option'] === 'negative_balance') {
                $this->adjustNegativeBalanceWallet(round($request['totalAmount'], 2), $order);
            }


            DB::commit();

            broadcast(new OrderCreated($order))->toOthers();

            // Return success response
            return [
                'status' => true,
                'order' => $order,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function createOrUpdateOrderStatus($order, $status)
    {
        $orderStatus = OrderStatusHistory::where('order_id', $order->id)->first();

        if ($orderStatus) {
            switch ($status) {
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


    public function details($id)
    {
        $order = Order::where('id', $id)
            ->with('details', 'payment:id,currency,gateway_name', 'currency:id,code,symbol', 'user:id,name')
            ->first();

        if ($order) {
            $exchange_rate = $order->exchange_rate;
            $symbol = $order->currency->symbol ?? '';

            $order_discount_amount = isset($order->details->tier_info) ? (float)json_decode($order->details->tier_info)->order_discount_amount : 0;

            return [
                'id' => $order->id,
                'unique_id' => $order->unique_id,
                'user_name' => ucfirst($order->user->name),
                'phone' => $order->details->phone,
                'email' => $order->details->email,
                'product_ids' => json_decode($order->details->product_ids),
                'user_company' => json_decode($order->details->details)->company_name,
                'details' => collect(json_decode($order->details->details)->products)->transform(function ($product) use ($exchange_rate, $symbol) {
                    $discount = $product->discount_type !== 'percentage' ? round($product->discount * $exchange_rate, 2) : round((($product->discount * $product->unit_price) / 100) * $exchange_rate, 2);
                    $product->unit_price = $symbol . round($product->unit_price * $exchange_rate, 2);
                    $product->total_price = $symbol . round(($product->total_price + $product->tax ?? 0) * $exchange_rate, 2) - $discount;
                    $product->discount = $symbol . $discount;
                    $product->tax = $symbol . round($product->tax * $exchange_rate, 2);
                    return $product;
                }),
                'premium_user_discount_amount' => (isset(json_decode($order->details->details)->premium_user_order) && json_decode($order->details->details)->premium_user_order == 'true' ? $symbol . round(json_decode($order->details->details)->premium_user_discount_amount * $exchange_rate, 2) : 0),
                'order_discount_amount' => $symbol . ($order_discount_amount * $exchange_rate),
                'tier_info' => json_decode($order->details->tier_info),
                'stock_ids_and_qtys' => array_map(function ($product) {
                    return [
                        'stock_id' => $product->stock_id,
                        'qty' => $product->qty,
                    ];
                }, json_decode($order->details->details)->products),
                'currency' => $order->payment->currency ?? $order->currency->code,
                'gateway_name' => $order->is_cod ? 'Cash on Delivery' : ($order->payment->gateway_name ?? null),
                'is_delivered' => $order->is_delivered,
                'is_cod' => $order->is_cod,
                'payment_status' => str_replace('_', ' ', $order->payment_status),
                'status' => $order->status,
                'is_refund_requested' => $order->is_refund_requested,
                'refunded_details' => json_decode($order->refunded_details) ?? null,
                'order_amount' => $symbol . round(($order->order_amount * $exchange_rate), 2),
                'discount_amount' => $symbol . round(($order->discount_amount * $exchange_rate), 2),
                'shipping_charge' => $symbol . round(((json_decode($order->details->details)->shipping_charge ?? 0) * $exchange_rate), 2),
                'tax_amount' => $symbol . round(($order->tax_amount * $exchange_rate), 2),
                'final_amount' => $symbol . round(($order->final_amount * $exchange_rate), 2),
                'note' => $order->details->notes,
                'shipping_method' => $order->details->shipping_method,
                'shipping_address' => $order->details->shipping_address,
                'billing_address' => $order->details->billing_address,
                'created_at' => $order->created_at->format('d M Y, h:i:s A')
            ];
        }

        return null;
    }

    private function getProductDetails($details)
    {
        $data = json_decode($details, true);

        return collect($data['products'])->map(function ($product) {
            return [
                'product_id' => $product['id'],
                'stock_id' => $product['stock_id'],
                'quantity' => (int)$product['qty']
            ];
        });
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

    public function updateStockByOrderId($orderId, $type = 'order')
    {
        $orderDetails = OrderDetail::where('order_id', $orderId)->first();

        if ($orderDetails && $orderDetails->details) {
            $products = $this->getProductDetails($orderDetails->details);

            $productIds = $products->pluck('product_id');
            $stockIds = $products->pluck('stock_id');

            DB::beginTransaction();

            try {
                $productStocks = ProductStock::whereIn('id', $stockIds)
                    ->with('product:id,name,minus_stock', 'productDetail:id,product_id,current_stock,number_of_sale')
                    ->get()
                    ->keyBy('id');

                $products->map(function ($product) use ($type, $productStocks) {
                    $productStock = $productStocks->get($product['stock_id']);

                    if ($productStock) {
                        $productDetail = $productStock->productDetail;
                        $quantity = $product['quantity'];

                        if ($productDetail) {
                            if ($type == 'order') {
                                if ($productStock->stock >= $quantity) {
                                    $productStock->decrement('stock', $quantity);
                                } else {
                                    $remainingQty = $quantity - $productStock->stock;
                                    $productStock->stock = 0;
                                    $productStock->save();

                                    $product = $productStock->product;
                                    $minusStock = $product->minus_stock ?? 0;

                                    if ($minusStock >= $remainingQty) {
                                        $product->decrement('minus_stock', $remainingQty);
                                        $product->save();
                                    } else {
                                        throw new \Exception('Not enough stock available for product "' . $product->name . '"');
                                    }
                                }

                                $productDetail->decrement('current_stock', $quantity);
                                $productDetail->increment('number_of_sale', $quantity);
                                $productDetail->save();

                                $productStock->increment('number_of_sale', $quantity);

                            } else {
                                $productStock->increment('stock', $quantity);
                                $productStock->decrement('number_of_sale', $quantity);

                                $productDetail->increment('current_stock', $quantity);
                                $productDetail->decrement('number_of_sale', $quantity);
                                $productDetail->save();

                                $product = $productStock->product;
                                $product->increment('minus_stock', $quantity);
                                $product->save();
                            }

                            $productStock->in_stock = $productStock->stock > 0;
                            $productStock->save();
                        }
                    }
                });

                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
        return false;
    }


    public function updateStatus($request, $orderId)
    {
        $order = Order::find($orderId);
        // dd($order->details ?? $order->details->phone);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.'
            ]);
        }

        $type = $request->input('type');
        $newStatus = $request->input('value');
        $currentStatus = $order->status;

        // Define restricted transitions
        $restrictedTransitions = [
            'packaging' => ['pending'],
            'shipping' => ['pending', 'packaging'],
            'out_of_delivery' => ['pending', 'packaging', 'shipping'],
            'delivered' => ['pending', 'packaging', 'shipping', 'out_of_delivery'],
            'returned' => ['pending', 'packaging', 'shipping', 'out_of_delivery', 'delivered'],
        ];

        // Check if transition is allowed
        if ($type === 'order_status' && isset($restrictedTransitions[$currentStatus]) && in_array($newStatus, $restrictedTransitions[$currentStatus])) {
            return response()->json([
                'status' => false,
                'message' => "Cannot change status from $currentStatus to $newStatus."
            ]);
        }
        // Check if payment_status changes from Unpaid to Paid
        if ($type === 'payment_status' && $order->payment_status === 'Not_Paid' && $newStatus === 'Paid' && $order->is_cod) {
            // Get stock_ids and quantities from the order
            $stockIdsAndQtys = $request->input('stock_ids_and_qtys');

            // Update stock quantities
            $stock = $this->updateStockQuantities($stockIdsAndQtys);
            if (!$stock['status']) {
                return response()->json($stock);
            }

            if($order->details && $order->details->phone != '') {

                $template = get_settings('sms_password_reset_template');
                $template = str_replace('[[ORDER_ID]]', $order->unique_id, $template);
                $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);

                $sms = new SmsHelper();   
                $sms->sendSms($order->details->phone, $template);   
            }

            // Assign Points to the user
            $this->assignPoints($order->id, $order->user_id);
        }

        if (!$order->is_cod && $type == 'payment_status' && $order->payment_status === 'Paid' && $newStatus === 'Not_Paid') {
            return response()->json([
                'status' => false,
                'message' => "Cannot change Paid Payments to Unpaid."
            ]);
        }

        if ($order->is_cod && $type == 'payment_status' && $order->payment_status === 'Paid' && $newStatus === 'Not_Paid') {
            $this->updateStockByOrderId($order->id, 'returned');

            // deduct Points to the user
            $this->deductPoints($order->id, $order->user_id);
        }

        // No restrictions for 'failed'
        if ($type === 'order_status' && $newStatus === 'failed') {
            $order->status = $newStatus;
        } elseif ($type === 'order_status') {
            if ($newStatus === 'delivered') {
                $order->is_delivered = !$order->is_delivered;
            }
            $order->status = $newStatus;
        } elseif ($type === 'payment_status') {
            $order->payment_status = $newStatus;
        }

        $order->save();
        if ($order) {

            if($newStatus == 'packaging' && $order->details && $order->details->phone != '') {

                $template = get_settings('sms_phone_number_verification_template');
                $template = str_replace('[[ORDER_ID]]', $order->unique_id, $template);
                $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);

                $sms = new SmsHelper();   
                $sms->sendSms($order->details->phone, $template);   
            }

            if($newStatus == 'shipping' && get_settings('sms_order_placement_status') == 1 && $order->details && $order->details->phone != '') {

                $trackOrderLink = route('order.tracking.information', $order->unique_id);

                $template = get_settings('sms_online_order_placement_template');
                $template = str_replace('[[ORDER_ID]]', $order->unique_id, $template);
                $template = str_replace('[[TRACKING_LINK]]', $trackOrderLink, $template);
                $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);

                $sms = new SmsHelper();   
                $sms->sendSms($order->details->phone, $template);   
            }

            if($newStatus == 'out_of_delivery' && get_settings('sms_out_for_delivery_status') == 1 && $order->details && $order->details->phone != '') {

                $template = get_settings('sms_out_for_delivery_template');
                $template = str_replace('[[ORDER_ID]]', $order->unique_id, $template);
                $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);

                $sms = new SmsHelper();   
                $sms->sendSms($order->details->phone, $template);   
            }

            if($newStatus == 'delivered' && get_settings('sms_delivery_status_change') == 1 && $order->details && $order->details->phone != '') {

                $template = get_settings('sms_order_processing_update_template');
                $template = str_replace('[[ORDER_ID]]', $order->unique_id, $template);
                $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);

                $sms = new SmsHelper();   
                $sms->sendSms($order->details->phone, $template);   
            }
            
            if($newStatus == 'returned' && get_settings('sms_order_return_status') == 1 && $order->details && $order->details->phone != '') {

                $trackOrderLink = route('order.tracking.information', $order->unique_id);
                $template = get_settings('sms_order_return_template');
                $template = str_replace('[[ORDER_ID]]', $order->unique_id, $template);
                $template = str_replace('[[STATUS]]', "Returned", $template);
                $template = str_replace('[[RETURN_LINK]]', $trackOrderLink, $template);
                $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);

                $sms = new SmsHelper();   
                $sms->sendSms($order->details->phone, $template);   
            }

            $this->createOrUpdateOrderStatus($order, $newStatus);
        }

        return response()->json([
            'status' => true,
            'message' => ucfirst(str_replace('_', ' ', $type)) . ' updated successfully.'
        ]);
    }

    public function assignPoints($orderId, $userId)
    {
        $orderDetails = OrderDetail::where('order_id', $orderId)->first();

        if ($orderDetails && $orderDetails->details) {
            $products = $this->getProductDetails($orderDetails->details);

            $user = User::find($userId);
            $products->map(function ($model) use ($user) {
                $product = Product::with('details')->find($model['product_id']);
                $quantity = $model['quantity'];

                if ($product && $product->details && $product->details->points > 0) {
                    $user->points += ($quantity * $product->details->points);
                    $user->save();

                    if ($user) {
                        UserPoint::create([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'points' => $product->details->points,
                            'quantity' => $quantity,
                            'method' => 'plus'
                        ]);
                    }
                }
            });

            return true;
        }
        return false;
    }

    public function deductPoints($orderId, $userId)
    {
        $orderDetails = OrderDetail::where('order_id', $orderId)->first();

        if ($orderDetails && $orderDetails->details) {
            $products = $this->getProductDetails($orderDetails->details);

            $user = User::find($userId);
            $products->map(function ($model) use ($user) {
                $product = Product::with('details')->find($model['product_id']);
                $quantity = $model['quantity'];

                if ($product && $product->details && $product->details->points > 0) {
                    $user->points -= ($quantity * $product->details->points);
                    $user->save();

                    if ($user) {
                        UserPoint::create([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'points' => $product->details->points,
                            'quantity' => $quantity,
                            'method' => 'minus'
                        ]);
                    }
                }
            });

            return true;
        }
        return false;
    }

    private function updateStockQuantities(array $stockIdsAndQtys)
    {
        $stockIds = array_column($stockIdsAndQtys, 'stock_id');
        $quantities = array_column($stockIdsAndQtys, 'qty', 'stock_id');

        DB::beginTransaction();

        try {
            $stocks = ProductStock::whereIn('id', $stockIds)
                ->with('product:id,name,minus_stock', 'productDetail:id,product_id,current_stock,low_stock_quantity,number_of_sale')  // Eager load minus_stock from Product
                ->get();
            $insufficientStocks = $stocks->filter(function ($stock) use ($quantities) {
                $availableStock = $stock->stock;

                if ($availableStock < $quantities[$stock->id]) {
                    $availableStock += $stock->product->minus_stock ?? 0;
                }

                return $availableStock < $quantities[$stock->id];
            })->map(function ($stock) use ($quantities) {
                $availableStock = $stock->stock;

                if ($availableStock < $quantities[$stock->id]) {
                    $availableStock += $stock->product->minus_stock ?? 0;
                }

                return [
                    'stock_id' => $stock->id,
                    'product' => $stock->product->name,
                    'available' => $availableStock,
                    'requested' => $quantities[$stock->id],
                ];
            });

            if ($insufficientStocks->isNotEmpty()) {
                return [
                    'status' => false,
                    'message' => 'Insufficient stock available for "' . $insufficientStocks[0]['product'] . '" Requested: ' . $insufficientStocks[0]['requested'] . ', Available: ' . $insufficientStocks[0]['available'],
                ];
            }

            $stocks->map(function ($stock) use ($quantities) {
                $availableStock = $stock->stock;
                $requestedQty = $quantities[$stock->id];
                if ($availableStock <= $stock->productDetail->low_stock_quantity) {
                    Notification::create([
                        'message' => 'Low Stock: ' . ucwords($stock->product->name),
                        'go_to_link' => route('admin.stock.index'),
                    ]);
                }
                if ($availableStock >= $requestedQty) {
                    $newStockQuantity = $availableStock - $requestedQty;
                    $stock->stock = max(0, $newStockQuantity);
                    $stock->number_of_sale += $requestedQty;
                    $stock->in_stock = $stock->stock > 0;

                    $productDetail = $stock->productDetail;
                    if ($productDetail) {
                        $productDetail->decrement('current_stock', $requestedQty);
                        $productDetail->increment('number_of_sale', $requestedQty);
                        $productDetail->save();
                    }

                    $stock->save();
                } else {
                    $remainingQty = $requestedQty - $availableStock;
                    $stock->stock = 0;

                    $stock->save();

                    $product = $stock->product;
                    $productMinusStock = $product->minus_stock ?? 0;

                    if ($productMinusStock >= $remainingQty) {
                        $product->minus_stock -= $remainingQty;
                        $product->save();

                        $productDetail = $stock->productDetail;
                        if ($productDetail) {
                            $productDetail->decrement('current_stock', $remainingQty);
                            $productDetail->increment('number_of_sale', $remainingQty);
                            $productDetail->save();
                        }
                    } else {
                        throw new \Exception('Not enough stock available in product and minus_stock for "' . $stock->product->name . '"');
                    }
                }
            });

            DB::commit();

            return [
                'status' => true,
                'message' => 'Stock quantities and product details updated successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
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

    private function validateRequest($request)
    {
        try {
            $request->validate([
                'customer_name' => 'required',
                'customer_email' => 'required|email',
                'customer_phone' => 'required',
                'customer_company' => 'required',
                'billing_city' => 'required|exists:cities,id',
                'billing_area' => 'required',
                'billing_address' => 'required',
                'billing_address2' => 'required',
                'product' => 'required|array',
                'shipping_charge' => 'required|numeric',
                'total_tax' => 'required|numeric',
                'discount' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'payment_option' => 'required',
                'currency_code' => 'required|exists:currencies,code',
            ]);

            if ($request->filled('different_shipping_address')) {
                $request->validate([
                    'shipping_city' => 'required',
                    'shipping_area' => 'required',
                    'shipping_address' => 'required',
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('order.checkout')
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    private function productsdata($products, $countryId, $cityId)
    {
        return collect($products)->map(function ($product) use ($countryId, $cityId) {
            $item = Product::where('slug', $product['slug'])
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
                        'order_qty' => $product['qty'],
                        'tax' => $tax * $product['qty'],
                    ];
                }
            }

            return null;
        })->filter();
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

    public function checkCoupon($request)
    {
        $coupon_code = $request->coupon_code;

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
        if ($coupon->is_sellable == 1 && Auth::guard('customer')->user()->coupons->exists()) {
            $userBoughtCoupon = Auth::guard('customer')->user()->coupons->where('coupon_id', $coupon->id)->first();
            if (!$userBoughtCoupon) {
                return null;
            }

            if ($userBoughtCoupon->status == 1) {
                return null;
            }
        }

        $userCoupon = UserCoupon::create([
            'user_id' => Auth::guard('customer')->user()->id,
            'coupon_id' => $coupon->id,
            'discount_amount' => $request->discount
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

    private function userCoupon($couponId)
    {
        return Auth::guard('customer')->user()->userCoupon->where('coupon_id', $couponId)->first();
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

    private function multiTierApplied($request)
    {
        if (!Session::has('currency_id')) {
            return null;
        }

        $currency_id = Session::get('currency_id');
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
}

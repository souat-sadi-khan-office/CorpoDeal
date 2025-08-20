<?php


namespace App\Repositories;

use App\CPU\Images;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockPurchase;
use App\Models\WishList;
use App\Repositories\Interface\ReportsRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportsRepository implements ReportsRepositoryInterface
{
    public function productsSell($request)
    {
        $filters = $this->filters($request);

        $products = Product::select('id', 'name', 'thumb_image', 'unit_price')
            ->with(['ratings' => function ($query) {
                $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                    ->groupBy('product_id');
            }
            ])->withCount('ratings')
            ->paginate($request->paginate ?? 15)
            ->withQueryString();


        $transformedProducts = $products->getCollection()->map(function ($product) use ($filters) {
            $report = $product->getSalesReport($filters);
            $report['average_rating'] = isset($product->ratings->first()->averageRating) ? round($product->ratings->first()->averageRating, 2) : 0;
            $report['ratings_count'] = $product->ratings_count ?? 0;
            return $report['total_quantity_sold'] > 0 ? $report : null;

        })
            ->filter()
            ->sortByDesc('total_revenue')
            ->values();

        $totalRevenueSum = $transformedProducts->sum('total_revenue');
        $products->setCollection($transformedProducts);

        return [$products, $totalRevenueSum];
    }

    public function orderReport($request)
    {
        $filters = $this->filters($request);
        $orders = Order::with('payment:id,currency,gateway_name')
            ->when(isset($filters['payment_status']) && $filters['payment_status'] !== null, fn($query) => $query->where('payment_status', $filters['payment_status']))
            ->when(isset($filters['status']) && $filters['status'] !== null, fn($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['is_cod']), fn($query) => $query->where('is_cod', $filters['is_cod']))
            ->when(isset($filters['is_refund_requested']) && $filters['is_refund_requested'] !== null, fn($query) => $query->where('is_refund_requested', $filters['is_refund_requested']));

        $this->applyDateFilters($orders, $filters);
        $count = $orders->count();
        $symbol = get_system_default_currency()->symbol ?? '$';
        $summary = $this->counts($orders, $count, $symbol);
        $orders = $orders->latest()->paginate($request->paginate)->withQueryString();
        $orders->setCollection($this->transformOrdersCollection($orders->getCollection(), $symbol));

        return [$orders, $summary];
    }

    public function transactions($request, $type)
    {
        $filters = $this->filters($request);
        $symbol = get_system_default_currency()->symbol ?? '$';

        $orders = Order::where('payment_status', 'Paid')
            ->when($type === 'cod', fn($query) => $query->where('is_cod', 1))
            ->when($type !== 'cod', fn($query) => $query->with('payment:id,currency,gateway_name,payer_id')->where('is_cod', 0));

        $this->applyDateFilters($orders, $filters);

        $count = $orders->count();
        $summary = $this->counts($orders, $count, $symbol);

        $orders = $orders->latest()->paginate($request->paginate)->withQueryString();

        $orders->setCollection($orders->getCollection()->map(fn($order) => $this->transformTransaction($order, $symbol)));

        return [$orders, $summary];

    }

    public function stockPurchaseReport($request)
    {
        $symbol = get_system_default_currency()->symbol ?? '$';

        $stock = StockPurchase::with('product:id,name', 'stocks:id,product_id,stock_purchase_id,stock', 'admin:id,name')
            ->when($request->has('search') && $request->search, function ($query) use ($request) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereHas('product', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })->orWhere('sku', 'like', '%' . $searchTerm . '%');
                });
            });
        $filters = $this->filters($request);
        $this->applyDateFilters($stock, $filters);
        $count = $stock->count();

        $summary = [
            'count' => $count,
            'totalSum' => $symbol . round(covert_to_defalut_currency($stock->sum('purchase_total_price')), 2),
        ];

        $paginatedStock = $stock->latest()
            ->paginate($request->paginate)
            ->withQueryString();

        $mappedStock = $paginatedStock->getCollection()->map(function ($item) use ($symbol) {
            return [
                "id" => $item->id,
                "product_name" => $item->product->name,
                "admin_name" => $item->admin->name,
                "sku" => $item->sku,
                "quantity" => $item->quantity,
                "unit_price" => $symbol . round(covert_to_defalut_currency($item->unit_price), 2),
                "purchase_unit_price" => $symbol . round(covert_to_defalut_currency($item->purchase_unit_price), 2),
                "purchase_total_price" => $symbol . round(covert_to_defalut_currency($item->purchase_total_price), 2),
                "file" => $item->file,
                "stock" => $item->stocks->sum('stock'),
                "is_sellable" => $item->is_sellable,
                "created_at" => get_system_date($item->created_at) . ' ' . get_system_time($item->created_at),
            ];
        });

        $paginatedStock->setCollection($mappedStock);

        return [$paginatedStock, $summary];

    }

    public function profitReport($request)
    {
        $symbol = get_system_default_currency()->symbol ?? '$';
        $filters = $this->filters($request);

        $orderDetailsQuery = OrderDetail::whereHas('order', function ($query) use($filters) {
            $query->where('status', 'delivered')
                ->where('payment_status', 'Paid')
                ->where('is_delivered', true)
                ->when(isset($filters['is_cod']), fn($query) => $query->where('is_cod', $filters['is_cod']));
        });

        $this->applyDateFilters($orderDetailsQuery, $filters);

        $paginatedOrderDetails = $orderDetailsQuery->latest()->paginate($request->paginate)->withQueryString();

        $stockIds = $paginatedOrderDetails->getCollection()->flatMap(function ($orderDetail) {
            $orderData = json_decode($orderDetail->details, true);
            return collect($orderData['products'])->pluck('stock_id');
        })->unique()->toArray();

        $stocks = ProductStock::with(['purchase:id,purchase_unit_price'])
            ->whereIn('id', $stockIds)
            ->get()
            ->keyBy('id');

        $orderProfitData = $paginatedOrderDetails->getCollection()->map(function ($orderDetail) use ($stocks) {
            $orderData = json_decode($orderDetail->details, true);
            $orderTotal = 0;
            $totalProfit = 0;
            $purchaseTotal = 0;

            collect($orderData['products'])->each(function ($productData) use ($stocks, &$orderTotal, &$totalProfit, &$purchaseTotal) {
                $stock = $stocks->get($productData['stock_id']);
                if (!$stock) return;

                $purchaseUnitPrice = $stock->purchase->purchase_unit_price ?? 0;
                $unitSalePrice = $productData['unit_price'];
                $qty = $productData['qty'];
                $profitPerUnit = $unitSalePrice - $purchaseUnitPrice;
                $totalProfitForProduct = $profitPerUnit * $qty;

                $orderTotal += $unitSalePrice * $qty;
                $purchaseTotal += $purchaseUnitPrice * $qty;
                $totalProfit += $totalProfitForProduct;
            });
            $totalProfit=$totalProfit-$orderDetail->order->discount_amount;
            $Ccode=$orderDetail->order->currency->code;
            return [
                'order_id' => $orderDetail->order_id,
                'unique_id' => $orderDetail->order->unique_id,
                'order_status' => $orderDetail->order->status,
                'payment_status' => $orderDetail->order->payment_status,
                'gateway_name' => $orderDetail->order->is_cod ? 'Cash on Delivery' : ($orderDetail->order->payment->gateway_name ?? null),
                'created_at' => $orderDetail->order->created_at,
                'order_total' => covert_to_defalut_currency($orderTotal),
                'discount_total' => reportCurrency($orderDetail->order->discount_amount,$Ccode),
                'tax_total' => reportCurrency($orderDetail->order->tax_amount,$Ccode),
                'purchase_total' => covert_to_defalut_currency($purchaseTotal),
                'total_profit' =>covert_to_defalut_currency($totalProfit,$Ccode),
                'is_profit'=>$totalProfit>0,
            ];

            //TODO::Shipping Amount Not Calculated Yet

        });

        // Summary
        $totalProfitSum = $orderProfitData->sum('total_profit');
        $orderTotalSum = $orderProfitData->sum('order_total');
        $purchaseTotalSum = $orderProfitData->sum('purchase_total');
        $discountTotalTotalSum = $orderProfitData->sum('discount_total');
        $taxTotalSum = $orderProfitData->sum('tax_total');

        $summary = [
            'count' => $paginatedOrderDetails->total(),
            'total_profit' => $symbol . round($totalProfitSum, 2),
            'total_order_value' => $symbol . round($orderTotalSum, 2),
            'total_purchase_value' => $symbol . round($purchaseTotalSum, 2),
            'total_discount_value' => $symbol . round($discountTotalTotalSum, 2),
            'total_tax_value' => $symbol . round($taxTotalSum, 2),
            'is_profit'=>$totalProfitSum>0,
        ];

        // Format paginated data with currency symbols after calculations
        $formattedOrderProfitData = $orderProfitData->map(function ($order) use ($symbol) {
            $order['order_total'] = $symbol . round($order['order_total'], 2);
            $order['purchase_total'] = $symbol . round($order['purchase_total'], 2);
            $order['total_profit'] = $symbol . round($order['total_profit'], 2);
            $order['discount_total'] = $symbol . round($order['discount_total'], 2);
            $order['tax_total'] = $symbol . round($order['tax_total'], 2);
            $order['created_at'] = get_system_date($order['created_at'] ) . ' ' . get_system_time($order['created_at']);
            return $order;
        });

        return [$paginatedOrderDetails->setCollection($formattedOrderProfitData), $summary];
    }


//    Private Functions

    private function counts($orders, $count, $symbol)
    {
        $orderSum = 0;
        $taxSum = 0;
        $discountSum = 0;
        $finalSum = 0;

        $orders->get()->map(function ($order) use (&$orderSum, &$taxSum, &$discountSum, &$finalSum) {

            $order->order_amount = covert_to_defalut_currency($order->order_amount);
            $order->discount_amount = covert_to_defalut_currency($order->discount_amount);
            $order->tax_amount = covert_to_defalut_currency($order->tax_amount);
            $order->final_amount = covert_to_defalut_currency($order->final_amount);

            $orderSum += $order->order_amount;
            $taxSum += $order->tax_amount;
            $discountSum += $order->discount_amount;
            $finalSum += $order->final_amount;
        });

        return [
            'count' => $count,
            'totalSum' => $symbol . round($finalSum, 2),
            'orderSum' => $symbol . round($orderSum, 2),
            'taxSum' => $symbol . round($taxSum, 2),
            'discountSum' => $symbol . round($discountSum, 2),
        ];
    }



    private function transformOrdersCollection($ordersCollection, $symbol)
    {
        return $ordersCollection->map(fn($order) => $this->transformOrder($order, $symbol));
    }

    private function transformOrder($order, $symbol)
    {

        return [
            'id' => $order->id,
            'unique_id' => $order->unique_id,
            'gateway_name' => $order->is_cod ? 'Cash on Delivery' : ($order->payment->gateway_name ?? null),
            'payment_status' => str_replace('_', ' ', $order->payment_status),
            'status' => ucwords(str_replace('_', ' ', $order->status)),
            'is_refund_requested' => $order->is_refund_requested,
            'order_amount' => $symbol . round(covert_to_defalut_currency($order->order_amount),2),
            'discount_amount' => $symbol . round(covert_to_defalut_currency($order->discount_amount),2),
            'tax_amount' => $symbol . round(covert_to_defalut_currency($order->tax_amount),2),
            'final_amount' => $symbol . round(covert_to_defalut_currency($order->final_amount),2),
            'status_badge' => $order->status == 'pending' ? 'warning text-dark' :
                ($order->status == 'delivered' ? 'success' :
                    (in_array($order->status, ['packaging', 'shipping', 'out_of_delivery']) ? 'info text-dark' : 'danger')),
            'created_at' => get_system_date($order->created_at) . ' ' . get_system_time($order->created_at),
        ];
    }

    private function transformTransaction($order, $symbol)
    {
        return [
            'id' => $order->id,
            'unique_id' => $order->unique_id,
            'payer_id' => $order->payment->payer_id ?? null,
            'gateway_name' => $order->is_cod ? 'Cash on Delivery' : ($order->payment->gateway_name ?? null),
            'payment_status' => str_replace('_', ' ', $order->payment_status),
            'order_amount' => $symbol . round(covert_to_defalut_currency($order->order_amount),2),
            'discount_amount' => $symbol . round(covert_to_defalut_currency($order->discount_amount),2),
            'tax_amount' => $symbol . round(covert_to_defalut_currency($order->tax_amount),2),
            'final_amount' => $symbol . round(covert_to_defalut_currency($order->final_amount),2),
            'created_at' => get_system_date($order->created_at) . ' ' . get_system_time($order->created_at),
        ];
    }


    private function applyDateFilters($query, $filters)
    {
        if (isset($filters['date_range']) && !empty($filters['date_range']['from']) && !empty($filters['date_range']['to'])) {
            $query->whereBetween('created_at', [$filters['date_range']['from'], $filters['date_range']['to']]);
        } elseif (isset($filters['between']) && !isset($filters['date_range']['from'])) {
            switch ($filters['between']) {
                case 'last_day':
                    $query->whereDate('created_at', Carbon::now()->subDay()->format('Y-m-d'));
                    break;
                case 'last_week':
                    $query->whereBetween('created_at', [
                        Carbon::now()->subWeek()->startOfDay()->format('Y-m-d'),
                        Carbon::now()->endOfDay()->format('Y-m-d')
                    ]);
                    break;
                case 'last_month':
                    $query->whereBetween('created_at', [
                        Carbon::now()->subMonth()->startOfDay()->format('Y-m-d'),
                        Carbon::now()->endOfDay()->format('Y-m-d')
                    ]);
                    break;
                case 'last_year':
                    $query->whereBetween('created_at', [
                        Carbon::now()->subYear()->startOfDay()->format('Y-m-d'),
                        Carbon::now()->endOfDay()->format('Y-m-d')
                    ]);
                    break;
            }
        }
    }

    private function filters($request)
    {
        return [
            'payment_status' => $request->payment_status ?? null,
            'status' => $request->status ?? null,
            'is_cod' => $request->payment_method === "cod"?1:null,
            'is_refund_requested' => isset($request->status) && $request->status === 'refund_requested',
            'date_range' => [
                'from' => $request->from ?? null,
                'to' => $request->to ?? null,
            ],
            'between' => $request->between ?? null,
        ];
    }

    public function wishlistDataTable()
    {
        $models = WishList::all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('product', function ($model) {
                if($model->product) {
                    return '<div class="row"><div class="col-auto">' . Images::show($model->product->thumb_image) . '</div><div class="col">' . $model->product->name . '</div></div>';
                } else {
                    return '';
                }
            })
            ->editColumn('customer', function ($model) {
                return $model->user ? $model->user->name : '';
            })
            ->editColumn('date', function ($model) {
                return get_system_date($model->created_at) .' '. get_system_time($model->created_at);
            })
            ->addColumn('action', function ($model) {
                return view('backend.reports.wishlist.action', compact('model'));
            })
            ->rawColumns(['action', 'product', 'customer', 'date'])
            ->make(true);
    }

    public function deleteWishlist($id)
    {
        $model = Wishlist::find($id);
        if($model) {
            $model->delete();
        }
    }

}

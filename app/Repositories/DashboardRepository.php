<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Rating;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\FlashDeal;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use App\Models\ProductStock;
use App\Models\StockPurchase;
use App\Models\ProductQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Interface\DashboardRepositoryInterface;


class DashboardRepository
{
    public function index($request)
    {
        // Start timing -Istiyak
        $startTime = microtime(true);

        // Recording the CPU usage before executing the logic -Istiyak
        $startUsage = getrusage();

        $orderStats = Order::selectRaw('
            COUNT(*) as total_orders,
            SUM(final_amount) as total_order_amount,
            SUM(CASE WHEN payment_status = "Paid" THEN final_amount ELSE 0 END) as total_paid_order_amount,
            SUM(CASE WHEN payment_status = "Not_Paid" THEN final_amount ELSE 0 END) as total_unpaid_order_amount,
            SUM(CASE WHEN status = "delivered" AND payment_status = "Paid" AND is_cod = true THEN final_amount ELSE 0 END) as total_cash_on_delivery,
            COUNT(CASE WHEN status = "pending" THEN 1 END) as total_pending_orders,
            COUNT(CASE WHEN status = "packaging" THEN 1 END) as total_packaging_orders,
            COUNT(CASE WHEN status = "delivered" THEN 1 END) as total_delivered_orders,
            COUNT(CASE WHEN status = "returned" THEN 1 END) as total_returned_orders,
            COUNT(CASE WHEN status IN ("shipping", "out_of_delivery") THEN 1 END) as total_shipping_orders,
            COUNT(CASE WHEN status = "failed" THEN 1 END) as total_failed_orders
        ')
            ->first();

        $topUsers = Cache::remember('top_users', 90, function () {
            return User::select('id', 'name', 'email', 'avatar')
                ->withCount([
                    'orders' => function ($query) {
                        $query->whereNotIn('status', ['returned', 'failed']);
                    }
                ])
                ->withSum('orders as total_final_amount', 'final_amount')
                ->having('orders_count', '>', 0)
                ->orderBy('total_final_amount', 'desc')
                ->take(5)
                ->get();
        });

        $topVisitedProducts = Cache::remember('top_visited_products', 90, function () {
            return ProductDetail::orderBy('visitor_counter', 'DESC')
                ->take(5)
                ->get();
        });

        $productStats = Product::selectRaw('
            COUNT(*) as total_products,
            COUNT(CASE WHEN status = true THEN 1 END) as total_active_products
        ')
            ->first();

        $brandStats = Brand::selectRaw('
            COUNT(*) as total_brands,
            COUNT(CASE WHEN status = true THEN 1 END) as active_brands
        ')
            ->first();

        $categoryStats = Cache::remember('categories_count_dashboard', 3600 * 24, function () {
            return Category::selectRaw('
            COUNT(*) as total_categories,
            COUNT(CASE WHEN parent_id IS NULL THEN 1 END) as total_primary_categories,
            COUNT(CASE WHEN status = true THEN 1 END) as active_categories,
            COUNT(CASE WHEN parent_id IS NOT NULL THEN 1 END) as total_sub_categories
        ')
                ->first();
        });

        $currencyStats = Cache::remember('currency_count_dashboard', 3600 * 24, function () {
            return Currency::selectRaw('
            COUNT(*) as total_currency,
            COUNT(CASE WHEN status = true THEN 1 END) as total_active_currency
        ')->first();
        });

        $stockStats = ProductStock::selectRaw('
            SUM(stock) as total_stock
        ')
            ->first();

        $purchaseStats = StockPurchase::selectRaw('
            SUM(quantity) as total_stock_purchase,
            SUM(purchase_total_price) as total_stock_purchase_amount
        ')
            ->first();

        $totalRatings = Rating::count();
        $totalUsers = User::count();
        $totalCoupons = Coupon::where('status', true)->count();

        $now = now();
        $totalQuestions = ProductQuestion::count();
        $currentMonthQuestions = ProductQuestion::where('created_at', '>=', $now->copy()->firstOfMonth())->count();
        $lastMonthQuestions = ProductQuestion::whereBetween('created_at', [
            $now->copy()->subMonth()->firstOfMonth(),
            $now->copy()->subMonth()->endOfMonth()
        ])->count();

        $percentageChange = $lastMonthQuestions > 0
            ? (($currentMonthQuestions - $lastMonthQuestions) / $lastMonthQuestions) * 100
            : ($currentMonthQuestions > 0 ? 100 : 0);

        $totalQuantityAcrossAllOrders = OrderDetail::all()->map(function ($orderDetail) {
            $details = json_decode($orderDetail->details, true);
            return collect($details['products'])->sum('qty');
        })->sum();

        $totalFlashDeals = FlashDeal::where('status', true)->count();

        $symbol = get_system_default_currency()->symbol ?? "$";
        $cacheSize = env('CACHE_STORE') === 'database' ? $this->getDatabaseCacheSize() : $this->getLaravelCacheSize();

        // Stop timing -Istiyak
        $endTime = microtime(true);

        // Recording the CPU usage after executing the logic -Istiyak
        $endUsage = getrusage();

        $executionTime = round(($endTime - $startTime) * 1000, 2) . " ms";
        $cpu = $this->getCpuUsageOverInterval($startUsage, $endUsage, $startTime, $endTime);
        return [
            'executionTime' => $executionTime,
            'cpu' => $cpu,
            'cacheSize' => $cacheSize,
            'total_users' => $totalUsers,
            'total_products' => $productStats->total_products,
            'total_active_products' => $productStats->total_active_products,
            'total_questions' => $totalQuestions,
            'questions_percentage_change' => round($percentageChange, 2),
            'total_stock' => $stockStats->total_stock,
            'order_to_stock_ratio%' => intval($stockStats->total_stock) > 0
                ? round(($totalQuantityAcrossAllOrders / $stockStats->total_stock) * 100, 4)
                : 0,
            'total_stock_purchase' => $purchaseStats->total_stock_purchase,
            'total_stock_purchase_amount' => $symbol . round(covert_to_defalut_currency($purchaseStats->total_stock_purchase_amount), 3),
            'total_orders' => $orderStats->total_orders,
            'total_order_qty' => $totalQuantityAcrossAllOrders,
            'total_order_amount' =>  $symbol . round(covert_to_defalut_currency($orderStats->total_order_amount), 3),
            'total_paid_order_amount' => $symbol . round(covert_to_defalut_currency($orderStats->total_paid_order_amount), 3),
            'total_unpaid_order_amount' =>  $symbol . round(covert_to_defalut_currency($orderStats->total_unpaid_order_amount), 3),
            'total_cash_on_delivery' => $symbol . round(covert_to_defalut_currency($orderStats->total_cash_on_delivery), 3),
            'total_pending_orders' => $orderStats->total_pending_orders,
            'total_packaging_orders' => $orderStats->total_packaging_orders,
            'total_delivered_orders' => $orderStats->total_delivered_orders,
            'total_returned_orders' => $orderStats->total_returned_orders,
            'total_shipping_orders' => $orderStats->total_shipping_orders,
            'total_failed_orders' => $orderStats->total_failed_orders,
            'total_currency' => $currencyStats->total_currency,
            'total_active_currency' => $currencyStats->total_active_currency,
            'total_brands' => $brandStats->total_brands,
            'active_brands' => $brandStats->active_brands,
            'total_categories' => $categoryStats->total_categories,
            'total_primary_categories' => $categoryStats->total_primary_categories,
            'active_categories' => $categoryStats->active_categories,
            'total_sub_categories' => $categoryStats->total_sub_categories,
            'total_ratings' => $totalRatings,
            'total_coupons' => $totalCoupons,
            'total_flash_deals' => $totalFlashDeals,
            'top_customers' => $topUsers,
            'top_visited_products' => $topVisitedProducts
        ];
    }


    private function getCpuUsageOverInterval($startUsage, $endUsage, $startTime, $endTime)
    {
        //  elapsed real (wall-clock) time in seconds -Istiyak
        $elapsedTime = $endTime - $startTime;

        //  User CPU time used (in seconds) -Istiyak
        $userCpuTime = ($endUsage["ru_utime.tv_sec"] - $startUsage["ru_utime.tv_sec"]) +
            ($endUsage["ru_utime.tv_usec"] - $startUsage["ru_utime.tv_usec"]) / 1e6;

        //  System CPU time used (in seconds) -Istiyak
        $systemCpuTime = ($endUsage["ru_stime.tv_sec"] - $startUsage["ru_stime.tv_sec"]) +
            ($endUsage["ru_stime.tv_usec"] - $startUsage["ru_stime.tv_usec"]) / 1e6;

        $idleTime = max(0, $elapsedTime - ($userCpuTime + $systemCpuTime));

        $activeTime = $userCpuTime + $systemCpuTime;
        $cpuUsagePercentage = ($activeTime / $elapsedTime) * 100;

        return [
            'user_cpu_time' => round($userCpuTime * 1000, 2) . " ms",  // in milliseconds
            'system_cpu_time' => round($systemCpuTime * 1000, 2) . " ms", // in milliseconds
            'idle_time' => round($idleTime * 1000, 2) . " ms",  // in milliseconds
            'cpu_usage_percentage' => round($cpuUsagePercentage, 2) . " %"
        ];
    }
    private function getDatabaseCacheSize()
    {
        $cacheTable = 'cache';
        $cachedItems = DB::table($cacheTable)->get();

        $totalSize = $cachedItems->sum(function ($item) {
            return strlen($item->key) + strlen($item->value);
        });

        return [
            'size' => $totalSize < 1024 ? $totalSize . " B" : ($totalSize < 1024 * 1024 ? round($totalSize / 1024, 1) . " KB" :
                round($totalSize / 1024 / 1024, 2) . " MB"),
            'count' => $cachedItems->count(),
        ];
    }



    private function getLaravelCacheSize($cacheDir = null)
    {
        $cacheDir = $cacheDir ?: __DIR__ . '/../../storage/framework/cache/data';
        $size = 0;
        $fileCount = 0;

        if (!is_dir($cacheDir)) {
            echo "Cache directory not found: " . $cacheDir;
            return ['size' => $size, 'count' => $fileCount];
        }

        $files = scandir($cacheDir);
        if (!$files) {
            return ['size' => $size, 'count' => $fileCount];
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $cacheDir . DIRECTORY_SEPARATOR . $file;

            if (is_file($filePath)) {
                $size += filesize($filePath);
                $fileCount++;
            } elseif (is_dir($filePath)) {
                $result = $this->getLaravelCacheSize($filePath);
                $size += $result['size'];
                $fileCount += $result['count'];
            }
        }

        return [
            'size' => round($size / 1024 / 1024, 4) >= 0 ?
                round($size / 1024, 1) . " KB" :
                round($size / 1024 / 1024, 2) . " MB",
            'count' => $fileCount
        ];
    }
}

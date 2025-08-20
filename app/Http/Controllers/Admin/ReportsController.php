<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WishList;
use App\Repositories\Interface\ReportsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    private $reportRepository;

    public function __construct(ReportsRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function productsSellReport(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product-sale-report.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        list($data, $revenue) = $this->reportRepository->productsSell($request);
        return view('backend.reports.product_sell', compact('data', 'revenue'));
    }

    public function orderReport(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('order-report.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        list($data, $amounts) = $this->reportRepository->orderReport($request);
        return view('backend.reports.orders', compact('data', 'amounts'));

    }

    public function transactions(Request $request, $type)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('transaction-report.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        list($data, $amounts) = $this->reportRepository->transactions($request, $type);
        return view('backend.reports.transactions', compact('data', 'amounts', 'type'));
    }

    public function stockPurchaseReport(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('stock-purchase-report.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        list($data, $amounts) = $this->reportRepository->stockPurchaseReport($request);
        return view('backend.reports.stockPurchase', compact('data', 'amounts'));
    }

    public function profitReport(Request $request)
    {
        list($data, $amounts) = $this->reportRepository->profitReport($request);
        return view('backend.reports.profits', compact('data', 'amounts'));
    }

    public function wishlistReport(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('wishlist.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->reportRepository->wishlistDataTable();
        }

        $totalProductsInWishlist = Wishlist::distinct('product_id')->count('product_id');

        $mostWishlistProduct = Wishlist::select('product_id', DB::raw('count(*) as wishlist_count'))
            ->groupBy('product_id')
            ->orderByDesc('wishlist_count')
            ->first();

        $product = Product::find($mostWishlistProduct->product_id); 


        return view('backend.reports.wishlist.index', compact('totalProductsInWishlist', 'mostWishlistProduct', 'product'));
    }

    public function deleteWishlist($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('wishlist.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->reportRepository->deleteWishlist($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Record deleted successfully"
        ]);
    }
}

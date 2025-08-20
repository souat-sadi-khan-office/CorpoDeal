<?php
namespace App\Repositories\Interface;

use Illuminate\Http\Request;

interface ReportsRepositoryInterface
{
    public function productsSell($request);
    public function orderReport($request);
    public function transactions($request,$type);
    public function stockPurchaseReport($request);
    public function profitReport($request);
    public function wishlistDataTable();
    public function deleteWishlist($id);
}

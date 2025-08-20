<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductDetail;
use App\Models\StockPurchase;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\ProductStockRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class ProductStockRepository implements ProductStockRepositoryInterface
{
    public function getAllStock()
    {
        return StockPurchase::with('product', 'admin')->orderBy('id', 'DESC')->get();
    }

    public function dataTable()
    {
        $models = $this->getAllStock();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('product', function ($model) {
                $productSlug = route('slug.handle', $model->product->slug);
                return '<div class="row">
                    <div class="col-auto">
                        ' . Images::show($model->product->thumb_image) . '
                    </div>
                    <div class="col">
                        ' . $model->product->name . '
                        <a href="'. $productSlug .'" target="_blank">
                            <i style="margin-left: 10px;" class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>';
            })
            ->editColumn('created_at', function ($model) {
                return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
            })
            ->editColumn('created_by', function ($model) {
                return $model->admin ? $model->admin->name : 'No admin found';
            })
            ->editColumn('sku', function ($model) {
                return $model->sku;
            })
            ->editColumn('status', function ($model) {
                $checked = $model->is_sellable == 1 ? 'checked' : '';
                
                if (Auth::guard('admin')->user()->hasPermissionTo('stock.create')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.stock.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->is_sellable == 1 ? 'success' : 'warning';
                    $status = $model->is_sellable == 1 ? 'Sellable' : 'Not Sellable';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }

            })
            ->editColumn('unit_price', function ($model) {
                return get_system_default_currency()->symbol. number_format(covert_to_defalut_currency($model->unit_price), 0);
            })
            ->editColumn('total_price', function ($model) {
                return get_system_default_currency()->symbol. number_format(covert_to_defalut_currency($model->quantity * $model->unit_price), 0);
            })
            ->addColumn('action', function ($model) {
                return view('backend.stock.action', compact('model'));
            })
            ->rawColumns(['action', 'created_at', 'status', 'created_by', 'sku', 'unit_price', 'total_price', 'product'])
            ->make(true);
    }

    public function findStockById($id)
    {
        return StockPurchase::findOrFail($id);
    }

    public function checkQuantity($data)
    {
        $totalGivenQuantity = $data['quantity'];
        $totalGetQuantity = 0;
        $stockType = $data['stock_types'];
        switch($stockType) {
            case 'globally': 
                $totalGetQuantity = $data['globally_stock_amount'];
            break;
            case 'zone_wise':
                $totalGetQuantity = array_sum($data['zone_wise_stock_quantity']);
            break;
            case 'country_wise':
                $totalGetQuantity = array_sum($data['country_wise_quantity']);
            break;
            case 'city_wise':
                $totalGetQuantity = array_sum($data['city_wise_quantity']);
            break;
        }

        if($totalGivenQuantity != $totalGetQuantity) {
            return response()->json(['status' => false, 'message' => 'Your total given quantity is: '. $totalGivenQuantity. ', but we got '. $totalGetQuantity]);
        }
    }

    public function createStock($data)
    {
        // check if the total quantity is the same as the stock types
        $this->checkQuantity($data); 

        // Stock Purchase
        $stockPurchase = new StockPurchase;
        $stockPurchase->product_id = $data['product_id'];
        $stockPurchase->admin_id = Auth::guard('admin')->id();
        $stockPurchase->currency_id = $data['currency_id'];
        $stockPurchase->sku = $data['sku'];
        $stockPurchase->quantity = $data['quantity'];
        $stockPurchase->unit_price = covert_to_usd($data['unit_price']);
        $stockPurchase->purchase_unit_price = covert_to_usd($data['purchase_unit_price']);
        $stockPurchase->purchase_total_price = covert_to_usd($data['purchase_total_price']);
        if(isset($data['file'])) {
            $stockPurchase->file = Images::upload('products.files',$data['file']);
        }
        $stockPurchase->is_sellable = $data['is_sellable'];
        $stockPurchase->save();
        if($stockPurchase && $stockPurchase->is_sellable == 1) {

            // update product in_stock column
            $product = Product::find($stockPurchase->product_id);
            $product->stock_types = $data['stock_types'];
            $product->unit_price = covert_to_usd($data['unit_price']);
            $product->in_stock = 1;
            $product->save();

            // Add stock data into product_stock table by stock_types
            switch($data['stock_types']) {
                case 'globally':
                    $stock = new ProductStock;
                    $stock->product_id = $product->id;
                    $stock->stock_purchase_id  = $stockPurchase->id;
                    $stock->in_stock = 1;
                    $stock->number_of_sale = 0;
                    $stock->stock = $data['globally_stock_amount'];
                    $stock->save();
                break;
                case 'zone_wise':

                    $zoneIdArray = $data['zone_id'];
                    $zoneWiseStockQuantityArray = $data['zone_wise_stock_quantity'];

                    if(count($zoneIdArray) > 0) {
                        for($zoneCounter = 0; $zoneCounter < count($zoneIdArray); $zoneCounter++) {
                            $stock = new ProductStock;
                            $stock->product_id = $product->id;
                            $stock->stock_purchase_id  = $stockPurchase->id;
                            $stock->zone_id  = $zoneIdArray[$zoneCounter];
                            $stock->in_stock = 1;
                            $stock->number_of_sale = 0;
                            $stock->stock = $zoneWiseStockQuantityArray[$zoneCounter];
                            $stock->save();
                        }
                    }

                break;
                case 'country_wise':
                    $countryIdArray = $data['country_id'];
                    $countryWiseStockQuantityArray = $data['country_wise_quantity'];

                    if(count($countryIdArray) > 0) {
                        for($countryCounter = 0; $countryCounter < count($countryIdArray); $countryCounter++) {
                            $stock = new ProductStock;
                            $stock->product_id = $product->id;
                            $stock->stock_purchase_id  = $stockPurchase->id;
                            $stock->country_id  = $countryIdArray[$countryCounter];
                            $stock->in_stock = 1;
                            $stock->number_of_sale = 0;
                            $stock->stock = $countryWiseStockQuantityArray[$countryCounter];
                            $stock->save();
                        }
                    }
                break;
                case 'city_wise':

                    $cityIdArray = $data['city_id'];
                    $cityWiseStockQuantityArray = $data['city_wise_quantity'];

                    if(count($cityIdArray) > 0) {
                        for($cityCounter = 0; $cityCounter < count($cityIdArray); $cityCounter++) {
                            $stock = new ProductStock;
                            $stock->product_id = $product->id;
                            $stock->stock_purchase_id  = $stockPurchase->id;
                            $stock->city_id  = $cityIdArray[$cityCounter];
                            $stock->in_stock = 1;
                            $stock->number_of_sale = 0;
                            $stock->stock = $cityWiseStockQuantityArray[$cityCounter];
                            $stock->save();
                        }
                    }

                break;
            }
        }

        // update current_stock data on product_details table
        $details = ProductDetail::where('product_id', $stockPurchase->product_id)->first();
        $details->current_stock = $data['quantity'] + $details->current_stock;
        $details->low_stock_quantity = $data['low_stock_quantity'];
        $details->save();

        return response()->json(['status' => true, 'message' => 'Stock created successfully.', 'goto' => route('admin.stock.index')]);
    }

    public function deleteStock($id)
    {
        $purchase = StockPurchase::findOrFail($id);
        if($purchase->delete()) {
            $product = Product::find($purchase->product_id);
            $productDetails = ProductDetail::where('product_id', $product->id)->first();

            $current_stock = $productDetails->current_stock - $purchase->quantity;
            if($current_stock <= $productDetails->low_stock_quantity) {
                $product->low_stock = 1;
            } else {
                $product->low_stock = 0;
            }

            if($current_stock == 0) {
                $product->in_stock = 0;
            } else {
                $product->in_stock = 1;
            }

            $productDetails->current_stock = $current_stock;
            $productDetails->save();
            $product->save();
        }

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Stock purchase record deleted successfully"
        ]);
    }

    public function updateStatus($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $purchase = StockPurchase::find($id);
        if (!$purchase) {
            return response()->json(['success' => false, 'message' => 'Brand not found.'], 404);
        }

        $purchase->is_sellable = $request->input('status');
        $purchase->save();

        $product = Product::find($purchase->product_id);
        $productDetails = ProductDetail::where('product_id', $product->id)->first();
        if($request->input('status') == 0) {
            $current_stock = $productDetails->current_stock - $purchase->quantity;
            if($current_stock <= $productDetails->low_stock_quantity) {
                $product->low_stock = 1;
            } else {
                $product->low_stock = 0;
            }

            if($current_stock == 0) {
                $product->in_stock = 0;
            } else {
                $product->in_stock = 1;
            }

            $productDetails->current_stock = $current_stock;
            $productDetails->save();
            $product->save();
        } else {
            $current_stock = $productDetails->current_stock + $purchase->quantity;
            if($current_stock <= $productDetails->low_stock_quantity) {
                $product->low_stock = 1;
            } else {
                $product->low_stock = 0;
            }

            if($current_stock == 0) {
                $product->in_stock = 0;
            } else {
                $product->in_stock = 1;
            }

            $productDetails->current_stock = $current_stock;
            $productDetails->save();
            $product->save();
        }

        return response()->json(['success' => true, 'message' => 'Stock purchase status updated successfully.']);
    }
}
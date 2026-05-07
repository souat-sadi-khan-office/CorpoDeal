<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Interface\SupplierRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StockRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\CityRepositoryInterface;
use App\Repositories\Interface\ZoneRepositoryInterface;
use App\Repositories\Interface\CountryRepositoryInterface;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use App\Repositories\Interface\ProductStockRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\DB;



class ProductStockController extends Controller
{
    private $stockRepository;
    private $currencyRepository;
    private $zoneRepository;
    private $countryRepository;
    private $cityRepository;
    private $supplierRepository;

    public function __construct(
        ProductStockRepositoryInterface $stockRepository,
        CurrencyRepositoryInterface $currencyRepository,
        ZoneRepositoryInterface $zoneRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository,
        SupplierRepositoryInterface $supplierRepository,

    ) {
        $this->stockRepository = $stockRepository;
        $this->currencyRepository = $currencyRepository;
        $this->zoneRepository = $zoneRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->supplierRepository = $supplierRepository;

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('stock.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->stockRepository->dataTable();
        }

        return view('backend.stock.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('stock.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $product_id = null;
        if($request->get('product_id')) {
            $product_id = $request->get('product_id');
        }

        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        $zones = $this->zoneRepository->getAllActiveZones();
        $countries = $this->countryRepository->getAllActiveCountry();
        $cities = $this->cityRepository->getAllActiveCity();
        $suppliers = $this->supplierRepository->index()->where('status', 1);


        return view('backend.stock.create', compact('currencies','zones', 'countries', 'cities','suppliers', 'product_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('stock.create') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->stockRepository->createStock($request->all());
    }

    public function generateStockForAll()
    {
        DB::beginTransaction();
        try {
            $products = Product::all();

            foreach ($products as $product) {
                $randomUnitPrice = rand(10, 500); // random price between 10–500 USD
                $purchaseUnitPrice = rand(5, $randomUnitPrice - 1);
                $quantity = 100;

                $data = [
                    'product_id' => $product->id,
                    'supplier_id' => 1,
                    'currency_id' => 1,
                    'sku' => $product->sku ?? 'SKU-' . strtoupper(uniqid()),
                    'quantity' => $quantity,
                    'unit_price' => $randomUnitPrice,
                    'purchase_unit_price' => $purchaseUnitPrice,
                    'purchase_total_price' => $purchaseUnitPrice * $quantity,
                    'is_sellable' => 1,
                    'stock_types' => 'globally',
                    'globally_stock_amount' => $quantity,
                    'low_stock_quantity' => 10, // default low stock alert
                ];

                // call repository method
                $this->stockRepository->createStock($data);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Stock generated for all products successfully!',
                'goto' => route('admin.stock.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('stock.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->stockRepository->findStockById($id);

        return view('backend.stock.show', compact('model'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('stock.delete') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->stockRepository->deleteStock($id);
    }

    /**
     * Update the specified resource from storage.
     */
    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('stock.create') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->stockRepository->updateStatus($request, $id);
    }
}

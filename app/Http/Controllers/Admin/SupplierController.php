<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductSerial;
use App\Models\StockPurchase;
use App\Repositories\Interface\SupplierRepositoryInterface;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private $supplier;

    public function __construct(
        SupplierRepositoryInterface $supplier,
    )
    {
        $this->supplier = $supplier;
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('supplier.create') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->supplier->store($request);
    }

    public function addform()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('supplier.update') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return view('backend.supplier.add');
    }

    public function updatestatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('supplier.update') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->supplier->updatestatus($request, $id);
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('supplier.update') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->supplier->update($request, $id);
    }

    public function edit(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('supplier.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $supplier = $this->supplier->edit($id);

        return view('backend.supplier.edit', compact('supplier'));
    }


    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('supplier.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $models = $this->supplier->index();
        $view = $this->supplier->view($models);

        if ($request->ajax()) {
            return $view;
        }
        return view('backend.supplier.index');
    }
    public function serials(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.serial-view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $models = $this->supplier->serials();
        $view = $this->supplier->viewSerials($models);

        if ($request->ajax()) {
            return $view;
        }
        return view('backend.stock.index-serial');
    }

    public function serialForm($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.serial-view') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        $stock = StockPurchase::find($id);
        if (!$stock) {
            return back()->with('error', 'Stock Not Found!');
        }

        return view('backend.stock.serial', compact('stock'));

    }

    public function serialCsvForm($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.serial-add') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        $stock = StockPurchase::find($id);
        if (!$stock) {
            return back()->with('error', 'Stock Not Found!');
        }

        return view('backend.stock.serial-csv', compact('stock'));

    }

    public function serialUpdate(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.serial-add') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        $stock = StockPurchase::find($id);
        if (!$stock) {
            return back()->with('error', 'Stock Not Found!');
        }
        $data = [];
        foreach ($request->serial as $serial) {
            $data[] = [
                'serial' => $serial,
                'supplier_id' => $stock->supplier_id,
                'stock_purchase_id' => $stock->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ProductSerial::where('supplier_id', $stock->supplier_id)->where('stock_purchase_id', $stock->id)->delete();
        ProductSerial::insert($data);

        return response()->json([
            'status' => true,
            'load' => true,
            'message' => "Serials Updated Succesfully"
        ]);

    }


    public function serialCsvUpdate(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.serial-add') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don't have permission"
            ]);
        }

        $stock = StockPurchase::find($id);
        if (!$stock) {
            return response()->json([
                'status' => false,
                'load' => true,
                'message' => 'Stock Not Found!'
            ]);
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getRealPath()));

            if (!empty($csvData) && isset($csvData[0][0]) && $csvData[0][0] === 'Serial') {
                array_shift($csvData);
            }

            $existingSerials = ProductSerial::where('stock_purchase_id', $stock->id)
                ->where('supplier_id', $stock->supplier_id)
                ->pluck('serial')
                ->toArray();

            $data = [];
            foreach ($csvData as $row) {
                $serial = trim($row[0] ?? '');
                if (!empty($serial) && !in_array($serial, $existingSerials)) {
                    $data[] = [
                        'serial' => $serial,
                        'supplier_id' => $stock->supplier_id,
                        'stock_purchase_id' => $stock->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $existingSerials[] = $serial;
                }
            }

            if (!empty($data)) {
                ProductSerial::insert($data);
                return response()->json([
                    'status' => true,
                    'load' => true,
                    'message' => "Serials uploaded successfully"
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'load' => true,
                    'message' => "No new serials to upload or all serials are duplicates"
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'load' => true,
                'message' => "Error processing CSV file: " . $e->getMessage()
            ]);
        }
    }

}

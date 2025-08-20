<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\TaxRepositoryInterface;

class TaxController extends Controller
{
    private $taxRepository;
    
    public function __construct(TaxRepositoryInterface $taxRepository)
    {
        $this->taxRepository = $taxRepository;
    }

    public function index(Request $request)
    {

        if (auth()->guard('admin')->user()->hasPermissionTo('settings.vat-tax') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->taxRepository->dataTable();
        }

        return view('backend.tax.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.vat-tax') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return view('backend.tax.create');
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.vat-tax') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'status'        => 'required'
        ]);

        $this->taxRepository->createTax($data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Tax created successfully"
        ]);
    }

    public function edit($id)
    {   
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.vat-tax') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $model = $this->taxRepository->findTaxById($id);
        return view('backend.tax.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.vat-tax') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'status'        => 'required'
        ]);

        $this->taxRepository->updateTax($id, $data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Tax updated successfully"
        ]);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.vat-tax') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->taxRepository->deleteTax($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Tax deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.vat-tax') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->taxRepository->updateStatus($request, $id);
    }
}

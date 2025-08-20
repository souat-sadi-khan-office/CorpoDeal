<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Repositories\Interface\CurrencyRepositoryInterface;

class CurrencyController extends Controller
{
    private $currencyRepository;

    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.currency') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->currencyRepository->dataTable();
        }

        return view('backend.currency.index');
    }

    public function create()
    {   
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.currency') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $countries = Country::where('status', 1)->get();
        return view('backend.currency.create', compact('countries'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.currency') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'code'          => 'required',
            'status'        => 'required',
            'decimal_separator' => 'required',
            'symbol'        => 'required|string|max:255',
            'country_id'    => 'required|unique:currencies',
            'exchange_rate' => 'required|numeric',
        ]);

        $this->currencyRepository->createCurrency($data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Currency created successfully"
        ]);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.currency') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        $countries = Country::where('status', 1)->get();
        $model = $this->currencyRepository->findCurrencyById($id);
        return view('backend.currency.edit', compact('model', 'countries'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.currency') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'code'          => 'required',
            'decimal_separator' => 'required',
            'status'        => 'required',
            'symbol'        => 'required|string|max:255',
            'country_id'    => 'required|unique:currencies,country_id,' . $id,
            'exchange_rate' => 'required|numeric',
        ]);

        $this->currencyRepository->updateCurrency($id, $data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Currency updated successfully"
        ]);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.currency') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        $this->currencyRepository->deleteCurrency($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Currency deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('settings.currency') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->currencyRepository->updateStatus($request, $id);
    }
}

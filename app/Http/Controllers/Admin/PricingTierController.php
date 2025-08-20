<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\PricingTierRepositoryInterface;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PricingTierController extends Controller
{
    private $tierRepository;
    private $currencyRepository;
    
    public function __construct(
        PricingTierRepositoryInterface $tierRepository,
        CurrencyRepositoryInterface $currencyRepository
    ) {
        $this->tierRepository = $tierRepository;
        $this->currencyRepository = $currencyRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->tierRepository->dataTable();
        }

        return view('backend.pricing-tier.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        return view('backend.pricing-tier.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'currency_id' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required|numeric',
            'threshold' => 'required|numeric',
            'with_product_tax'        => 'required',
            'applicable_to'        => 'required',
            'start_date'        => 'nullable',
            'end_date'        => 'nullable',
            'description'        => 'nullable',
            'usage_limit'        => 'required',
        ]);

        $data['start_date'] = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : null;
        $data['end_date'] = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : null;
        $data['admin_id'] = Auth::guard('admin')->user()->id;

        $this->tierRepository->store($data);

        return response()->json([
            'status' => true, 
            'goto' => route('admin.pricing-tier.index'),
            'message' => "Pricing Tier created successfully"
        ]);
    }

    public function edit($id)
    {   
        if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->tierRepository->find($id);
        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        return view('backend.pricing-tier.edit', compact('model', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'currency_id' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required|numeric',
            'threshold' => 'required|numeric',
            'with_product_tax'        => 'required',
            'applicable_to'        => 'required',
            'start_date'        => 'nullable',
            'end_date'        => 'nullable',
            'description'        => 'nullable',
            'usage_limit'        => 'required',
        ]);

        $data['start_date'] = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : null;
        $data['end_date'] = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : null;

        $this->tierRepository->update($id, $data);

        return response()->json([
            'status' => true, 
            'goto' => route('admin.pricing-tier.index'),
            'message' => "Pricing Tier updated successfully"
        ]);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->tierRepository->destroy($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Pricing Tier deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->tierRepository->updateStatus($request, $id);
    }
}

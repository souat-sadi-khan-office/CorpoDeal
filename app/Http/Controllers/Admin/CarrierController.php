<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\CarrierRepositoryInterface;
use App\Repositories\Interface\CountryRepositoryInterface;

class CarrierController extends Controller
{
    private $carrierRepository;
    private $countryRepository;

    public function __construct(
        CarrierRepositoryInterface $carrierRepository,
        CountryRepositoryInterface $countryRepository
    ) {
        $this->carrierRepository = $carrierRepository;
        $this->countryRepository = $countryRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->carrierRepository->dataTable();
        }

        return view('backend.carrier.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $countries = $this->countryRepository->getAllActiveCountry();
        return view('backend.carrier.create', compact('countries'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->carrierRepository->store($request);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->carrierRepository->find($id);

        // dd($model->rules->groupBy('country_id'));
        $countries = $this->countryRepository->getAllActiveCountry();
        return view('backend.carrier.edit', compact('model', 'countries'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'cost' => 'required|numeric'
        ]);

        $data['cost'] = covert_to_usd($request->cost);

        $this->carrierRepository->update($id, $data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Zone updated successfully"
        ]);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->carrierRepository->destroy($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Carrier deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->carrierRepository->updateStatus($request, $id);
    }
}

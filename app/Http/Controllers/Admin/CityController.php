<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\CityRepositoryInterface;

class CityController extends Controller
{
    private $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.city') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->cityRepository->dataTable();
        }

        return view('backend.cities.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.city') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $countries = Country::where('status', 1)->get();
        return view('backend.cities.create', compact('countries'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.city') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $data = $request->validate([
            'country_id'   => 'required',
            'name'      => 'required|string|max:255',
            'status'    => 'required',
            'cost'      => 'required|numeric'
        ]);

        $data['cost'] = covert_to_usd($request->cost);

        $this->cityRepository->createCity($data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "City created successfully"
        ]);
    }

    public function getCityInformationById(Request $request)
    {
        $cityId = $request->city_id;
        return $this->cityRepository->findCityById($cityId);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.city') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->cityRepository->findCityById($id);
        $countries = Country::where('status', 1)->get();
        return view('backend.cities.edit', compact('model', 'countries'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.city') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $data = $request->validate([
            'country_id'   => 'required',
            'name'      => 'required|string|max:255',
            'status'    => 'required|string',
            'cost'      => 'required|numeric'
        ]);

        $data['cost'] = covert_to_usd($request->cost);

        $this->cityRepository->updateCity($id, $data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "City updated successfully"
        ]);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.city') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->cityRepository->deleteCity($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "City deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.city') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->cityRepository->updateStatus($request, $id);
    }
}

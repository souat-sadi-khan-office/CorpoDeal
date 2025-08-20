<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Images;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\CountryRepositoryInterface;

class CountryController extends Controller
{
    private $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }
            
        if ($request->ajax()) {
            return $this->countryRepository->dataTable();
        }

        return view('backend.countries.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $zones = Zone::where('status', 1)->get();
        return view('backend.countries.create', compact('zones'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $request->validate([
            'zone_id'   => 'required',
            'name'      => 'required|string|max:255',
            'status'    => 'required',
            'image'     => 'required|image',
            'cost'      => 'required|numeric'
        ]);

        $data = [
            'zone_id' => $request->zone_id,
            'name'    => $request->name,
            'status'  => $request->status,
            'image'   => $request->image ? Images::upload('countries', $request->image) : null,
            'cost'    => covert_to_usd($request->cost)
        ];

        $this->countryRepository->createCountry($data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Country created successfully"
        ]);
    }

    public function getCountryInformationById(Request $request) 
    {
        $countryId = $request->country_id;
        return $this->countryRepository->findCountryById(($countryId));
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->countryRepository->findCountryById($id);
        $zones = Zone::where('status', 1)->get();
        return view('backend.countries.edit', compact('model', 'zones'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $request->validate([
            'zone_id'   => 'required',
            'name'      => 'required|string|max:255',
            'status'    => 'required|string',
            'image'     => 'image|nullable',
            'cost'      => 'required|numeric'
        ]);

        $data = [
            'zone_id' => $request->zone_id,
            'name'    => $request->name,
            'status'  => $request->status,
            'cost'    => covert_to_usd($request->cost)
        ];

        if($request->image) {
            $data['image'] = Images::upload('countries', $request->image);
        }

        $this->countryRepository->updateCountry($id, $data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Country updated successfully"
        ]);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->countryRepository->deleteCountry($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Country deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->countryRepository->updateStatus($request, $id);
    }
}

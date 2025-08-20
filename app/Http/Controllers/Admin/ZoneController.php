<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\ZoneRepositoryInterface;

class ZoneController extends Controller
{
    private $zoneRepository;

    public function __construct(ZoneRepositoryInterface $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->zoneRepository->dataTable();
        }

        return view('backend.zone.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.zone.create');
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
            'cost' => 'required|numeric'
        ]);

        $data['cost'] = covert_to_usd($request->cost);

        $this->zoneRepository->createZone($data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Zone created successfully"
        ]);
    }

    public function getZoneInformationById(Request $request)
    {
        $zoneId = $request->zone_id;
        return $this->zoneRepository->findZoneById($zoneId);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->zoneRepository->findZoneById($id);
        return view('backend.zone.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
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

        $this->zoneRepository->updateZone($id, $data);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Zone updated successfully"
        ]);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->zoneRepository->deleteZone($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Admin deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->zoneRepository->updateStatus($request, $id);
    }
}

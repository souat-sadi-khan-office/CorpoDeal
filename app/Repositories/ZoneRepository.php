<?php

namespace App\Repositories;

use App\Models\Zone;
use App\Repositories\Interface\ZoneRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class ZoneRepository implements ZoneRepositoryInterface
{
    public function getAllZone()
    {
        return Zone::all();
    }
    
    public function getAllActiveZones()
    {
        return Zone::where('status', 1)->get();
    }

    public function dataTable()
    {
        $models = $this->getAllZone();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('serial', function($model) {
                return $model->id;
            })
            ->editColumn('cost', function($model) {
                return '$'. number_format(covert_to_defalut_currency($model->cost), 2);
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') === false) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.zone.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $active = $model->status == 1 ? 'Active' : 'Inactive';
                    return '<span class="badge bg-'. $bg .'">'. $active .'</span>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.zone.action', compact('model'));
            })
            ->rawColumns(['action', 'cost', 'status'])
            ->make(true);
    }

    public function findZoneById($id)
    {
        return Zone::findOrFail($id);
    }

    public function createZone(array $data)
    {
        $zone = Zone::create($data);

        return $zone;
    }

    public function updateZone($id, array $data)
    {
        $zone = Zone::findOrFail($id);
        $zone->update($data);

        return $zone;
    }

    public function deleteZone($id)
    {
        $role = Zone::findOrFail($id);
        return $role->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $zone = Zone::find($id);

        if (!$zone) {
            return response()->json(['success' => false, 'message' => 'Zone not found.'], 404);
        }

        $zone->status = $request->input('status');
        $zone->save();

        return response()->json(['success' => true, 'message' => 'Zone status updated successfully.']);
    }
}
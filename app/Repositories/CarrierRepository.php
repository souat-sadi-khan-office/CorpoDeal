<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\ShippingCarrier;
use App\Models\ShippingCarrierRules;
use App\Repositories\Interface\CarrierRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CarrierRepository implements CarrierRepositoryInterface
{
    public function all()
    {
        return ShippingCarrier::all();
    }
    
    public function activeAll()
    {
        return ShippingCarrier::where('status', 1)->get();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('serial', function($model) {
                return $model->id;
            })
            ->editColumn('logo', function ($model) {
                return Images::show($model->logo);
            })
            ->editColumn('cost', function($model) {
                return '$'. number_format(covert_to_defalut_currency($model->cost), 2);
            })
            ->editColumn('status', function ($model) {
                $checked = $model->is_active == 1 ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.carrier.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.carrier.action', compact('model'));
            })
            ->rawColumns(['action', 'logo', 'status'])
            ->make(true);
    }

    public function find($id)
    {
        return ShippingCarrier::findOrFail($id);
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'transit_time' => 'nullable|string|max:255',
            'tracking_url' => 'nullable|url',
            'logo' => 'nullable|image|max:255',
            'status' => 'required|boolean',
            'free_shipping' => 'required|boolean',
            'rule_type' => 'required|string|in:fixed,weight,price,quantity',
            'delimiter1' => 'array',
            'delimiter2' => 'array',
            'zones' => 'array',
            'carrier_price' => 'nullable|array',
            'carrier_price.*' => 'nullable|array',
            'carrier_price.*.*' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        $logo = null;
        if($request->logo) {
            $logo = Images::upload('carrier', $request->logo);
        }

        DB::beginTransaction();

        $carrier = ShippingCarrier::create([
            'name' => $request->name,
            'transit_time' => $request->transit_time,
            'tracking_url' => $request->tracking_url,
            'is_active' => $request->status,
            'free_shipping' => $request->free_shipping,
            'logo' => $logo
        ]);

        if($carrier) {

            if(is_array($request->zones) && count($request->zones) > 0) {
                foreach($request->zones as $key => $zone) {

                    if(is_array($request->delimiter1) && is_array($request->delimiter2) && count($request->delimiter1) > 0 && count($request->delimiter2) > 0 && (count($request->delimiter1) == count($request->delimiter2))) {
                        $delimiter1Counter = count($request->delimiter1);
                        for($i = 0; $i < $delimiter1Counter; $i++) {
                            $model = ShippingCarrierRules::create([
                                'carrier_id' => $carrier->id,
                                'country_id' => $zone,
                                'rule_type'  => $request->rule_type,
                                'min_value'  => $request->delimiter1[$i],
                                'max_value'  => $request->delimiter2[$i],
                                'rate'  => $request->carrier_price[$zone][$i],
                            ]);
                        }
                    }
                }
            }
            DB::commit();
        } else {

            DB::rollBack();
        }

        return response()->json(['status' => true, 'goto' => route('admin.carrier.index'), 'message' => "Carrier created successfully."]);
    }

    public function update($id, array $data)
    {
        $zone = ShippingCarrier::findOrFail($id);
        $zone->update($data);

        return $zone;
    }

    public function destroy($id)
    {
        $role = ShippingCarrier::findOrFail($id);
        return $role->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $model = ShippingCarrier::find($id);

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Carrier not found.'], 404);
        }

        $model->is_active = $request->input('status');
        $model->save();

        return response()->json(['success' => true, 'message' => 'Carrier status updated successfully.']);
    }
}
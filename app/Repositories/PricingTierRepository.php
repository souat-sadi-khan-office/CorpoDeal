<?php

namespace App\Repositories;

use App\Models\PricingTier;
use App\Repositories\Interface\PricingTierRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class PricingTierRepository implements PricingTierRepositoryInterface
{
    public function all()
    {
        return PricingTier::all();
    }
    
    public function getAllActive()
    {
        return PricingTier::select('id', 'name')->where('status', 1)->orderBy('name', 'ASC')->get();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('currency', function($model) {
                return $model->currency ? $model->currency->name : '';
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (auth()->guard('admin')->user()->hasPermissionTo('pricing-tier.update') === false) {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                } else {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.pricing-tier.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                   
                }

            })
            ->addColumn('action', function ($model) {
                return view('backend.pricing-tier.action', compact('model'));
            })
            ->rawColumns(['action', 'currency', 'status'])
            ->make(true);
    }

    public function find($id)
    {
        return PricingTier::findOrFail($id);
    }

    public function store(array $data)
    {
        $tax = PricingTier::create($data);

        return $tax;
    }

    public function update($id, array $data)
    {
        $tax = PricingTier::findOrFail($id);
        $tax->update($data);

        return $tax;
    }

    public function destroy($id)
    {
        $tax = PricingTier::findOrFail($id);
        return $tax->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $model = PricingTier::find($id);

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Tier not found.'], 404);
        }

        $model->status = $request->input('status');
        $model->save();

        return response()->json(['success' => true, 'message' => 'Tier status updated successfully.']);
    }
}
<?php

namespace App\Repositories;

use DataTables;
use App\Models\Tax;
use App\Repositories\Interface\TaxRepositoryInterface;


class TaxRepository implements TaxRepositoryInterface
{
    public function getAllTax()
    {
        return Tax::all();
    }
    
    public function getAllActiveTaxes()
    {
        return Tax::select('id', 'name')->where('status', 1)->orderBy('name', 'ASC')->get();
    }

    public function dataTable()
    {
        $models = $this->getAllTax();
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.tax.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.tax.action', compact('model'));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function findTaxById($id)
    {
        return Tax::findOrFail($id);
    }

    public function createTax(array $data)
    {
        $tax = Tax::create($data);

        return $tax;
    }

    public function updateTax($id, array $data)
    {
        $tax = Tax::findOrFail($id);
        $tax->update($data);

        return $tax;
    }

    public function deleteTax($id)
    {
        $tax = Tax::findOrFail($id);
        return $tax->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $tax = Tax::find($id);

        if (!$tax) {
            return response()->json(['success' => false, 'message' => 'Tax not found.'], 404);
        }

        $tax->status = $request->input('status');
        $tax->save();

        return response()->json(['success' => true, 'message' => 'Tax status updated successfully.']);
    }
}
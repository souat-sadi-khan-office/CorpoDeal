<?php

namespace App\Repositories;

use App\CPU\Helpers;
use App\Models\ProductSerial;
use App\Models\Supplier;
use App\Repositories\Interface\SupplierRepositoryInterface;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function index()
    {
        return Supplier::all();
    }

    public function serials()
    {
        return ProductSerial::all();
    }
    public function edit($id)
    {
        return Supplier::find($id);
    }

    public function store($data)
    {

        $validator = $this->validate($data);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }

        try {
            Supplier::create([
                'name' => $data->name,
                'contact_email' => $data->contact_email,
                'contact_phone' => $data->contact_phone,
                'address' => $data->address ?? null,
                'website' => $data->website ?? null,
                'status' => $data->status ?? true,
                'created_by' => Auth::guard('admin')->id()
            ]);

            return response()->json(['message' => 'Created successfully!', 'status' => true, 'load' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    public function update($data, $id)
    {
        $validator = $this->validate($data, $id);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->update([
                'name' => $data->name,
                'contact_email' =>  $data->contact_email,
                'contact_phone' => $data->contact_phone,
                'address' => $data->address?? $supplier->address,
                'website' => $data->website?? $supplier->website,
                'status' =>$data->status ?? $supplier->status,
            ]);

            return response()->json(['message' => 'Updated successfully!', 'status' => true, 'load' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    public function updatestatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['success' => false, 'message' => 'Supplier not found.'], 404);
        }

        $supplier->status = $request->input('status');
        $supplier->save();

        return response()->json(['success' => true, 'message' => 'Supplier status updated successfully.']);
    }

    private function validate($data, $id = null)
    {
        return Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'contact_email' => 'required|email|unique:suppliers,contact_email,' . $id,
            'contact_phone' => 'required|string',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
        ]);
    }

    public function view($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (Auth::guard('admin')->user()->hasPermissionTo('supplier.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.supplier.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->status == 1 ? 'warning' : 'success';
                    $status = $model->status == 1 ? 'Featured' : 'Normal';

                    return '<span class="badge bg-' . $bg . '">' . $status . '</span>';
                }
            })
            ->editColumn('created_by', function ($model) {
                return Helpers::adminName($model->created_by);
            })
            ->editColumn('email', function ($model) {
                return '<a style="color:#000;" href="mailto:'. $model->contact_email .'" target="_blank">'. $model->contact_email .'</a>';
            })
            ->editColumn('phone', function ($model) {
                return '<a style="color:#000;" href="tel:'. $model->contact_phone .'">'. $model->contact_phone .'</a>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.supplier.action', compact('model'));
            })->rawColumns(['action', 'status', 'phone', 'created_by', 'email'])
            ->make(true);
    }

    public function viewSerials($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('product', function ($model) {
                return $model?->stock?->product?->name ?? '';
            })
            ->editColumn('supplier', function ($model) {
                return $model?->supplier?->name ?? $model?->stock?->supplier?->name;
            })
            ->addColumn('serial', function ($model) {
                return $model->serial;
            })->rawColumns(['product', 'supplier', 'serial'])
            ->make(true);
    }


}

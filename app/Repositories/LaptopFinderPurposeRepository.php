<?php

namespace App\Repositories;

use DataTables;
use App\Models\LaptopFinderPurpose;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\LaptopFinderPurposeRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class LaptopFinderPurposeRepository implements LaptopFinderPurposeRepositoryInterface
{
    public function all()
    {
        return LaptopFinderPurpose::orderBy('id', 'DESC')->get();
    }

    public function dataTable()
    {
        $models = $this->all();
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('created_at', function ($model) {
                return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
            })
            ->editColumn('created_by', function ($model) {
                return $model->admin ? $model->admin->name : '';
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.laptop.purpose.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.laptop-finder.purpose.action', compact('model'));
            })
            ->rawColumns(['action', 'created_at', 'created_by', 'status'])
            ->make(true);
    }

    public function find($id)
    {
        return LaptopFinderPurpose::findOrFail($id);
    }

    public function create($data)
    {
        LaptopFinderPurpose::create([
            'name' => $data->name,
            'details' => $data->details,
            'status' => $data->status,
            'created_by' => Auth::guard('admin')->user()->id
        ]);

        $json = ['status' => true, 'load' => true, 'message' => 'Record created successfully'];
        return response()->json($json);
    }

    public function update($id, $data)
    {
        $model = LaptopFinderPurpose::findOrFail($id);
        $model->name = $data->name;
        $model->status = $data->status;
        $model->details = $data->details;
        $model->update();

        return response()->json(['status' => true, 'load' => true, 'message' => 'Record updated successfully.']);
    }

    public function delete($id)
    {
        $model = LaptopFinderPurpose::findOrFail($id);
        return $model->delete();
    }

    public function updateStatus($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $model = LaptopFinderPurpose::find($id);

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $model->status = $request->input('status');
        $model->save();

        return response()->json(['success' => true, 'message' => 'Record status updated successfully.']);
    }
}
<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\Rating;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\CustomerReviewRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class CustomerReviewRepository implements CustomerReviewRepositoryInterface
{
    public function all()
    {
        return Rating::orderBy('id', 'DESC')->get();
    }

    public function dataTableWithAjaxSearch($product_id)
    {
        $models = $this->all()->where('product_id', $product_id);
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('date', function ($model) {
                return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (auth()->guard('admin')->user()->hasPermissionTo('customer-review.update') === false) {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status . '</span>';
                } else {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.customer-review.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.reviews.action', compact('model'));
            })
            ->editColumn('provider', function($model) {
                return $model->provider_name == null ? 'Normal Login User': ucfirst($model->provider_name) . ' User';
            })
            ->rawColumns(['action', 'date', 'customer', 'status'])
            ->make(true);
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('product', function ($model) {
                return '<div class="row"><div class="col-auto">' . Images::show($model->product->thumb_image) . '</div><div class="col">' . $model->product->name . '</div></div>';
            })
            ->editColumn('date', function ($model) {
                return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (auth()->guard('admin')->user()->hasPermissionTo('customer-review.update') === false) {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status . '</span>';
                } else {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.customer-review.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.reviews.action', compact('model'));
            })
            ->editColumn('provider', function($model) {
                return $model->provider_name == null ? 'Normal Login User': ucfirst($model->provider_name) . ' User';
            })
            ->rawColumns(['action', 'date', 'customer', 'status', 'product'])
            ->make(true);
    }

    public function find($id)
    {
        return Rating::findOrFail($id);
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'status' => 'required|boolean',
            'review_name' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if ($fileName = Images::upload('reviews', $file)) {
                    $files[] = $fileName;
                }
            }
        }

        $filesName = implode(',', $files);
        foreach($request->review_name as $review_key => $review_name) {
            Rating::create([
                'product_id' => $request->product_id,
                'name' => $review_name,
                'rating' => $request->review_rating[$review_key],
                'created_at' => $request->review_date[$review_key] . ' '. $request->review_time[$review_key],
                'review' => $request->review[$review_key],
                'files' => $filesName,
                'status' => $request->status
            ]);
        }

        $json = ['status' => true, 'goto' => route('admin.customer-review.index'), 'message' => 'Record created successfully'];

        return response()->json($json);
    }

    public function update($id, $data)
    {
        $model = Rating::findOrFail($id);
        $model->product_id = $data->product_id;
        $model->name = $data->name;
        $model->email = $data->email;
        $model->rating = $data->rating;
        $model->status = $data->status;
        $model->review = $data->review;

        $files = [];
        if ($data->hasFile('files')) {
            foreach ($data->file('files') as $file) {
                if ($fileName = Images::upload('reviews', $file)) {
                    $files[] = $fileName;
                }
            }

            $filesName = implode(',', $files);
            $model->files = $filesName;
        }

        $model->update();

        return response()->json(['status' => true, 'load' => true, 'message' => 'Record updated successfully.']);
    }

    public function destroy($id)
    {
        $model = Rating::findOrFail($id);
        return $model->delete();
    }

    public function updateStatus($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $model = Rating::find($id);
        
        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);
        }

        $model->status = $request->input('status');
        $model->save();

        return response()->json(['success' => true, 'message' => 'Review status updated successfully.']);
    }
}
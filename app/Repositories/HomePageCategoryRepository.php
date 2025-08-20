<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\HomeCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\HomePageCategoryRepositoryInterface;

class HomePageCategoryRepository implements HomePageCategoryRepositoryInterface
{
    public function all()
    {
        return HomeCategory::all();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('category_id', function ($model) {
                return $model->category ? $model->category->name : '';
            })
            ->editColumn('picture', function ($model) {
                return Images::show($model->picture);
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.home-page-category.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
            })
            ->editColumn('align', function ($model) {
                $buttonClass = $model->is_right == 1 ? 'btn btn-success' : 'btn btn-primary';
                $badgeText = $model->is_right == 1 ? 'Right To Left' : 'Left to Right';

                return '<button type="button" class="' . $buttonClass . ' position-relative">
                '.$badgeText.' <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info"> Aligned</span>
            </button>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.home-category.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'category_id', 'picture','align'])
            ->make(true);
    }

    public function find($id)
    {
        return HomeCategory::findOrFail($id);
    }

    public function store($data)
    {
        HomeCategory::create([
            'name' => $data->name,
            'category_id' => $data->category_id,
            'status' => $data->status,
            'alt_tag' => $data->alt_tag,
            'is_right' => $data->is_right,
            'picture' => $data->picture ? Images::upload('banners', $data->picture) : null,
        ]);

        Cache::forget('home_categories_');

        $json = ['status' => true, 'goto' => route('admin.home-page-category.index'), 'message' => 'Record created successfully'];
        return response()->json($json);
    }

    public function update($id, $data)
    {
        $model = HomeCategory::findOrFail($id);
        $model->name = $data->name;
        $model->category_id = $data->category_id;
        $model->is_right = $data->is_right;
        $model->status = $data->status;
        $model->alt_tag = $data->alt_tag;

        if ($data->picture) {
            $model->picture = Images::upload('banners', $data->picture);
        }

        $model->update();

        Cache::forget('home_categories_');

        return response()->json(['status' => true, 'goto' => route('admin.home-page-category.index'), 'message' => 'Record updated successfully.']);
    }

    public function destroy($id)
    {
        $model = HomeCategory::findOrFail($id);

        Cache::forget('home_categories_');

        return $model->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $model = HomeCategory::find($id);

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $model->status = $request->input('status');
        $model->save();

        Cache::forget('home_categories_');

        return response()->json(['success' => true, 'message' => 'Record status updated successfully.']);
    }
}

<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\CategoryPicture;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\CategoryBannerRepositoryInterface;

class CategoryBannerRepository implements CategoryBannerRepositoryInterface
{
    public function all()
    {
        return CategoryPicture::all();
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
                return '<div class="form-check form-switch"><input data-url="' . route('admin.category-banner.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.category-picture.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'category_id', 'picture'])
            ->make(true);
    }

    public function find($id)
    {
        return CategoryPicture::findOrFail($id);
    }

    public function store($data)
    {
        CategoryPicture::create([
            'name' => $data->name,
            'category_id' => $data->category_id,
            'status' => $data->status,
            'position' => $data->position,
            'picture' => $data->picture ? Images::upload('category.banners', $data->picture) : null,
        ]);

        $json = ['status' => true, 'goto' => route('admin.category-banner.index'), 'message' => 'Record created successfully'];
        return response()->json($json);
    }

    public function update($id, $data)
    {
        $brand = CategoryPicture::findOrFail($id);
        $brand->name = $data->name;
        $brand->category_id = $data->category_id;
        $brand->position = $data->position;
        $brand->status = $data->status;

        if ($data->picture) {
            $brand->logo = Images::upload('banners', $data->picture);
        }

        $brand->update();

        return response()->json(['status' => true, 'goto' => route('admin.category-banner.index'), 'message' => 'Record updated successfully.']);
    }

    public function destroy($id)
    {
        $brand = CategoryPicture::findOrFail($id);

        return $brand->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $model = CategoryPicture::find($id);
        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $model->status = $request->input('status');
        $model->save();

        return response()->json(['success' => true, 'message' => 'Record status updated successfully.']);
    }
}

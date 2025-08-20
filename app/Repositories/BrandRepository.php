<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\BrandRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class BrandRepository implements BrandRepositoryInterface
{
    public function getAllBrands()
    {
        return Brand::all();
    }

    public function dataTable()
    {
        $models = $this->getAllBrands();
        return FacadesDataTables::of($models)
            ->addIndexColumn()
            ->editColumn('logo', function ($model) {
                return Images::show($model->logo);
            })
            ->editColumn('created_by', function ($model) {
                return $model->creator->name;
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';
                if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.home-page-category') === false) { 
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                } else {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.brand.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                }
            })
            ->editColumn('featured', function ($model) {
                $is_featured = $model->is_featured == 1 ? 'checked' : '';

                if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.home-page-category') === false) { 
                    $bg = $model->is_featured == 1 ? 'warning' : 'success';
                    $status = $model->is_featured == 1 ? 'Featured' : 'Normal';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                } else {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.brand.featured', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $is_featured . ' data-id="' . $model->id . '"></div>';
                }

            })
            ->addColumn('action', function ($model) {
                return view('backend.brands.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'created_by', 'featured', 'logo'])
            ->make(true);
    }

    public function getBrandBySlug($slug)
    {
        return Brand::where('slug', $slug)->first();
    }

    public function findBrandById($id)
    {
        return Brand::findOrFail($id);
    }

    public function createBrand($data)
    {
        $brand = Brand::create([
            'name' => $data->name,
            'created_by' => Auth::guard('admin')->id(),
            'slug' => $data->slug,
            'status' => $data->status,
            'is_featured' => $data->is_featured,
            'description' => $data->description,
            'meta_title' => $data->meta_title,
            'meta_keyword' => $data->meta_keyword,
            'meta_description' => $data->meta_description,
            'meta_article_tag' => $data->meta_article_tag,
            'meta_script_tag' => $data->meta_script_tag,
            'logo' => $data->logo ? Images::upload('brands', $data->logo) : null,
        ]);

        Cache::forget('brands_');

        $json = ['status' => true, 'goto' => route('admin.brand.index'), 'message' => 'Brand created successfully'];
        return response()->json($json);
    }

    public function updateBrand($id, $data)
    {
        $brand = Brand::findOrFail($id);
        $brand->name = $data->name;
        $brand->slug = $data->slug;
        $brand->status = $data->status;
        $brand->is_featured = $data->is_featured;
        $brand->description = $data->description;
        $brand->meta_title = $data->meta_title;
        $brand->meta_keyword = $data->meta_keyword;
        $brand->meta_description = $data->meta_description;
        $brand->meta_article_tag = $data->meta_article_tag;
        $brand->meta_script_tag = $data->meta_script_tag;

        if($data->logo) {
            $brand->logo = Images::upload('brands', $data->logo);
        }

        $brand->update();

        Cache::forget('brands_');

        return response()->json(['status' => true, 'goto' => route('admin.brand.index'), 'message' => 'Brand updated successfully.']);
    }

    public function deleteBrand($id)
    {
        $brand = Brand::findOrFail($id);

        Cache::forget('brands_');

        return $brand->delete();
    }

    public function updateStatus($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'Brand not found.'], 404);
        }

        $brand->status = $request->input('status');
        $brand->save();

        Cache::forget('brands_');

        return response()->json(['success' => true, 'message' => 'Brand status updated successfully.']);
    }

    public function updateFeatured($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'Brand not found.'], 404);
        }

        $brand->is_featured = $request->input('status');
        $brand->save();
        
        Cache::forget('brands_');

        return response()->json(['success' => true, 'message' => 'Brand featured status updated successfully.']);
    }
}
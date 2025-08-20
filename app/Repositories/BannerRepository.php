<?php

namespace App\Repositories;

use DataTables;
use App\CPU\Images;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Interface\BannerRepositoryInterface;

class BannerRepository implements BannerRepositoryInterface
{
    public function getAllBanners()
    {
        return Banner::all();
    }

    public function dataTable()
    {
        $models = $this->getAllBanners();
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('image', function ($model) {
                return Images::show($model->image);
            })
            ->editColumn('created_by', function ($model) {
                return $model->admin->name;
            })
            ->editColumn('banner_type', function ($model) {
                $bannerType = "-";

                switch ($model->banner_type) {
                    case 'main':
                        $bannerType = "Main Banner";
                        break;
                    case 'main_sidebar':
                        $bannerType = "Main Sidebar Banner";
                        break;
                    case 'mid':
                        $bannerType = "Mid Website Banner";
                        break;
                    case 'footer':
                        $bannerType = "Footer Banner";
                        break;
                }

                return $bannerType;
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if(Auth::guard('admin')->user()->hasPermissionTo('coupon.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.banner.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.banner.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'created_by', 'banner_type', 'image'])
            ->make(true);
    }

    public function findBannerById($id)
    {
        return Banner::findOrFail($id);
    }

    public function getSourceOptions($source)
    {
        if ($source === 'category') {
            $data = Category::select('id', 'name')
                ->whereNull('parent_id')
                ->where('status', true)
                ->get();
        } elseif ($source === 'product') {
            $data = Product::select('id', 'name')
                ->where('status', true)
                ->get();
        } elseif ($source === 'brand') {
            $data = Brand::select('id', 'name')
                ->where('status', true)
                ->get();
        }

        return $data;
    }


    public function createBanner($data)
    {
        Banner::create([
            'name' => $data->name,
            'header_title' => $data->header_title,
            'old_offer' => $data->old_offer,
            'new_offer' => $data->new_offer,
            'banner_type' => $data->banner_type,
            'created_by' => Auth::guard('admin')->id(),
            'status' => $data->status,
            'source_type' => $data->source_type,
            'source_id' => $data->source_id,
            'link' => $data->link,
            'alt_tag' => $data->alt_tag,
            'image' => $data->image ? Images::upload('banners', $data->image) : null,
        ]);
        Cache::forget('banners');
        Cache::forget('mid_banners');

        $json = ['status' => true, 'goto' => route('admin.banner.index'), 'message' => 'Banner created successfully'];
        return response()->json($json);
    }

    public function updateBanner($id, $data)
    {
        $banner = Banner::findOrFail($id);
        $banner->banner_type = $data->banner_type;
        $banner->name = $data->name;
        $banner->header_title = $data->header_title;
        $banner->old_offer = $data->old_offer;
        $banner->new_offer = $data->new_offer;
        $banner->source_type = $data->source_type;
        $banner->source_id = $data->source_id;
        $banner->link = $data->link;
        $banner->alt_tag = $data->alt_tag;
        $banner->created_by = Auth::guard('admin')->id();
        $banner->status = $data->status;

        if ($data->image) {
            $banner->image = Images::upload('banners', $data->image);
        }

        $banner->update();

        Cache::forget('banners');
        Cache::forget('mid_banners');

        return response()->json(['status' => true, 'goto' => route('admin.banner.index'), 'message' => 'Banner updated successfully.']);
    }

    public function deleteBanner($id)
    {
        $brand = Banner::findOrFail($id);
        // Clear the banners cache
        Cache::forget('banners');
        Cache::forget('mid_banners');
        return $brand->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json(['success' => false, 'message' => 'Banner not found.'], 404);
        }

        $banner->status = $request->input('status');
        $banner->save();
        Cache::forget('banners');
        Cache::forget('mid_banners');
        return response()->json(['success' => true, 'message' => 'Banner status updated successfully.']);
    }
}

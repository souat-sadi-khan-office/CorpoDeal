<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Repositories\Interface\BannerRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    private $bannerRepository;

    public function __construct(BannerRepositoryInterface $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('banner.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->bannerRepository->dataTable();
        }

        return view('backend.banner.index');
    }

    public function slidersApi()
    {
        $banners = Cache::remember('banners', now()->addMinutes(300), function () {
            $data = $this->bannerRepository->getAllBanners();

            return $data->where('status', 1)
                ->groupBy('banner_type')
                ->filter(function ($group, $key) {
                    if ($key === 'main_sidebar' && $group->count() >= 2) {
                        return $group->shuffle()->take(2);
                    }
                    return $key !== 'main_sidebar' ? $group : collect();
                });
        });
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('banner.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.banner.create');
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('banner.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->bannerRepository->createBanner($request);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('banner.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->bannerRepository->findBannerById($id);
        $source = null;
        if ($model->source_type != null) {
            $source = $this->bannerRepository->getSourceOptions($model->source_type);
        }
        return view('backend.banner.edit', compact('model', 'source'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('banner.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->bannerRepository->updateBanner($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('banner.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->bannerRepository->deleteBanner($id);

        return response()->json([
            'status' => true,
            'load' => true,
            'message' => "Banner deleted successfully"
        ]);
    }

    public function show()
    {
        return 1;
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('banner.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->bannerRepository->updateStatus($request, $id);
    }

    public function moveUp($id)
    {
        $banner = Banner::findOrFail($id);
        $above = Banner::where('position', '<', $banner->position)
                    ->orderBy('position', 'desc')
                    ->first();

        if ($above) {
            $temp = $banner->position;
            $banner->position = $above->position;
            $above->position = $temp;

            $banner->save();
            $above->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Moved up successfully'
        ]);
    }

    public function moveDown($id)
    {
        $banner = Banner::findOrFail($id);
        $below = Banner::where('position', '>', $banner->position)
                    ->orderBy('position', 'asc')
                    ->first();

        if ($below) {
            $temp = $banner->position;
            $banner->position = $below->position;
            $below->position = $temp;

            $banner->save();
            $below->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Moved down successfully'
        ]);
    }


}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\BannerRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

/**
 * @group Banner API
 *
 * APIs for fetching frontend banners.
 */
class BannerController extends Controller
{
    protected $banner;

    public function __construct(BannerRepositoryInterface $banner)
    {
        $this->banner = $banner;
    }

    /**
     * Get All Active Banners (grouped by type)
     *
     * Returns banners with status=1, sorted by position and grouped by banner_type.
     *
     * @response 200 {
     *   "main_sidebar": [
     *     {
     *       "id": 1,
     *       "title": "Banner 1",
     *       "image": "https://example.com/image.jpg",
     *       "position": 1
     *     }
     *   ],
     *   "home_slider": [...]
     * }
     */
    public function index(): JsonResponse
    {
        $banners = Cache::remember('banners', now()->addMinutes(300), function () {
            return $this->banner->getAllBanners()
                ->where('platform', 'app')
                ->where('status', 1)
                ->sortBy('position')
                ->groupBy('banner_type')
                ->map(function ($group, $key) {
                    if ($key === 'main_sidebar') {
                        return $group->take(2);
                    }
                    return $group;
                });
        });

        return response()->json($banners);
    }
}

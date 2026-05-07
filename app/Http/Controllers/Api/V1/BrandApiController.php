<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Brand;
use App\Models\Product;

class BrandApiController extends Controller
{
    public function productsBySlug(string $slug): JsonResponse
    {
        $brand = Brand::where('slug', $slug)->where('status', 1)->first();

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        // Get all products for this brand
        $products = Product::with(['category']) // include more relationships if needed
            ->where('brand_id', $brand->id)
            ->where('status', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'brand' => [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => $brand->slug,
                ],
                'product_count' => $products->count(),
                'products' => $products,
            ]
        ]);
    }
}

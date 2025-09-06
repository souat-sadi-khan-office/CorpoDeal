<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    public function allCategoriesWithChildren(): JsonResponse
    {
        $categories = Category::with('children')
            ->where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $this->formatCategoriesRecursive($categories)
        ]);
    }

    private function formatCategoriesRecursive($categories)
    {
        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $category->icon,
                'children' => $this->formatCategoriesRecursive($category->children)
            ];
        });
    }


    public function featuredCategories(): JsonResponse
    {
        $categories = Category::withCount('children')
            ->with(['children' => function ($query) {
                $query->where('status', 1);
            }])
            ->where('status', 1)
            ->where('is_featured', 1)
            ->whereNull('parent_id')
            ->orderByDesc('children_count')
            ->orderBy('name', 'ASC')
            ->limit(10)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'children_count' => $category->children_count,
                    'has_children' => $category->children_count > 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function productsBySlug(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->where('status', 1)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Recursive descendant IDs
        $allCategoryIds = $category->getAllCategoryIds(); // Must return itself + all descendants

        $products = Product::with(['category', 'brand']) // add relationships as needed
            ->whereIn('category_id', $allCategoryIds)
            ->where('status', 1)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'product_count' => $products->count(),
                'products' => $products,
            ]
        ]);
    }

}

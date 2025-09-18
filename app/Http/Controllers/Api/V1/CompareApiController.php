<?php 

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductSpecification;

class CompareApiController extends Controller
{
    protected function getCompareKey($token)
    {
        return "compare_list:{$token}";
    }

    public function addToCompare(Request $request)
    {
        $slug = $request->input('slug');
        $token = $request->header('X-Compare-Token') ?? $request->input('compare_token') ?? (string) Str::uuid();

        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found.'], 404);
        }

        $key = $this->getCompareKey($token);
        $compareList = cache()->get($key, []);

        if (in_array($product->id, $compareList)) {
            return response()->json(['status' => false, 'message' => 'Product already in compare list.'], 409);
        }

        if (count($compareList) >= 3) {
            return response()->json(['status' => false, 'message' => 'You can only compare up to 3 products.'], 403);
        }

        $compareList[] = $product->id;
        cache()->put($key, $compareList, now()->addHours(24));

        return response()->json([
            'status' => true,
            'message' => 'Product added to compare list.',
            'compare_token' => $token,
            'compare_count' => count($compareList),
        ]);
    }

    public function getCompareList(Request $request)
    {
        $token = $request->header('X-Compare-Token') ?? $request->input('compare_token');
        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Compare token required.'], 400);
        }

        $compareList = cache()->get($this->getCompareKey($token), []);
        if (empty($compareList)) {
            return response()->json([
                'status' => true,
                'compare_token' => $token,
                'products' => [],
                'specifications' => []
            ]);
        }

        // eager load all required relations
        $products = Product::with([
            'brand',
            'ratings',
            'specifications.specificationKey',
            'specifications.specificationKeyType',
            'specifications.specificationKeyTypeAttribute',
        ])->whereIn('id', $compareList)->get();

        $models = [];
        $allSpecifications = [];

        foreach ($products as $product) {
            // ⭐ Ratings
            $averageRating = $product->ratings->isNotEmpty() ? $product->ratings->first()->averageRating : 0;
            $averageRatingCount = $product->ratings->count();
            $averageRatingPercentage = $averageRating ? ($averageRating / 5) * 100 : 0;

            // ⭐ Feature summary
            $summery = $product->specifications->where('key_feature', 1)->map(function ($spec) {
                return [
                    'type_name' => optional($spec->specificationKeyType)->name,
                    'attr_name' => optional($spec->specificationKeyTypeAttribute)->name,
                ];
            })->values();

            // ⭐ Model data
            $models[] = [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => optional($product->brand)->name,
                'brand_slug' => optional($product->brand)->slug,
                'slug' => $product->slug,
                'image' => asset($product->thumb_image),
                'discount_type' => $product->is_discounted,
                'unit_price' => $product->unit_price,
                'discounted_price' => $this->discountPrice($product),
                'discount' => $product->discount,
                'stage' => $product->stage,
                'stock' => getProductStock($product->id)['status'] ? 'In Stock' : 'Out of Stock',
                'average_rating' => $averageRating,
                'average_rating_percent' => $averageRatingPercentage,
                'average_rating_count' => $averageRatingCount,
                'summery' => $summery,
            ];

            // ⭐ Specification group
            $grouped = $product->specifications
                ->groupBy(fn($spec) => optional($spec->specificationKey)->name)
                ->map(function ($specs) use ($product) {
                    return $specs->groupBy(fn($s) => optional($s->specificationKeyType)->name)
                        ->map(function ($types, $typeName) use ($product) {
                            $attrNames = $types->pluck('specificationKeyTypeAttribute.name');
                            return [$product->id => $attrNames->first()];
                        });
                });

            foreach ($grouped as $keyName => $types) {
                foreach ($types as $typeName => $value) {
                    if (!isset($allSpecifications[$keyName][$typeName])) {
                        $allSpecifications[$keyName][$typeName] = [];
                    }
                    $allSpecifications[$keyName][$typeName] += $value;
                }
            }
        }

        return response()->json([
            'status' => true,
            'compare_token' => $token,
            'products' => $models,
            'specifications' => $allSpecifications,
        ]);
    }


    public function removeFromCompare(Request $request, $slug)
    {
        $token = $request->header('X-Compare-Token') ?? $request->input('compare_token');
        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Compare token required.'], 400);
        }

        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found.'], 404);
        }

        $key = $this->getCompareKey($token);
        $compareList = cache()->get($key, []);

        $updatedList = array_filter($compareList, fn($id) => $id != $product->id);
        cache()->put($key, $updatedList, now()->addHours(24));

        return response()->json([
            'status' => true,
            'message' => 'Product removed from compare list.',
            'compare_count' => count($updatedList),
        ]);
    }

    private function discountPrice($product)
    {
        if ($product->is_discounted && $product->discount_type === 'percent') {
            return $product->unit_price - (($product->unit_price * $product->discount) / 100);
        } elseif ($product->is_discounted) {
            return $product->unit_price - $product->discount;
        }
        return $product->unit_price;
    }
}

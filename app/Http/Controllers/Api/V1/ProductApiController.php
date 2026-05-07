<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductApiController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    public function getAllProducts()
    {
        $products = Product::with(['category']) 
            ->where('status', 1)
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products,
            ]
        ]);
    }

    public function getBySlug($slug)
    {
        $product = $this->productDetails($slug);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Increase counter (optional if stateless)
        $this->increaseCounter($product['id']);

        // Sent to visited list
        $this->sentToVisitedList($product);
        $visited_product_list = $this->productRepository->index('visited_product_list', [], session()->get('visited_product_list'));

        $breadcrumb = $this->getCategoryBreadcrumb($product['category']->id);
        $brand_id = $product['brand_id'] ?? null;

        $source = [
            'category_id' => $product['category']->id,
            'product_id' => $product['id'], 
            'brand_id' => $brand_id 
        ];

        $related_products = $this->productRepository->index('related_products', [], $source);
        $same_category_products = $this->productRepository->index('same_category_products', [], $source);
        $same_brand_products = $this->productRepository->index('same_brand_products', [], $source);

        $spec = $this->productRepository->specificationProductApi($product['id']);
        $keySpec = $this->productRepository->specificationKeyFeaturedProduct($product['id']);

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'breadcrumb' => $breadcrumb,
                'visited_products' => $visited_product_list,
                'related_products' => $related_products,
                'same_category_products' => $same_category_products,
                'same_brand_products' => $same_brand_products,
                'specifications' => [
                    'full' => $spec,
                    'key_features' => $keySpec
                ]
            ]
        ]);
    }

    public function productDetails($slug, $id = null, $source = null)
    {
        $product = Product::where('slug', $slug)->with([
            'details',
            'category:id,name,slug',
            'brand:id,name,slug',
            'ratings' => function ($query) {
                $query->select('id', 'status', 'product_id', 'files', 'name','review','rating');
                $query->where('status', 1);
            }, // Individual ratings
            'image' => function ($query) {
                $query->select('id', 'product_id', 'image')->where('status', 1);
            },
            'specifications' => function ($query) {
                $query->where('key_feature', 1)
                    ->with([
                        'specificationKeyType:id,name,position',
                        'specificationKeyTypeAttribute:id,name,extra'
                    ])
                    ->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                    ->orderBy('specification_key_types.position', 'ASC');
            },
            'question' => function ($query) {
                $query->where('status', 1);
                $query->orderBy('id', 'desc');
            }
        ])->withCount(['ratings as averageRating' => function ($query) {
            $query->select(DB::raw('AVG(rating)'))->groupBy('product_id'); // Calculate average rating
        }])
            ->withCount('ratings') // Count of ratings
            ->first([
                'id',
                'category_id',
                'brand_id',
                'name',
                'thumb_image',
                'sku',
                'slug',
                'unit_price',
                'is_returnable',
                'return_deadline',
                'is_discounted',
                'discount',
                'discount_type'
            ]);

        if (!$product) {
            return null;  // Return null explicitly if product not found
        }

        $discountedPrice = $product->unit_price;
        if ($product->is_discounted && $product->discount > 0) {
            $discountAmount = $product->discount_type == 'amount'
                ? $product->discount
                : ($product->unit_price * ($product->discount / 100));

            $discountedPrice -= $discountAmount;
        }
        $averageRatingPercentage = $product->averageRating !== null ? ($product->averageRating / 5) * 100 : 0;
        $productDetails = [
            'id' => $product->id,
            'category' => $product->category,
            'brand_id' => $product->brand_id,
            'brand_name' => $product->brand ?  $product->brand->name : '',
            'brand_slug' => $product->brand ? $product->brand->slug : '',
            'name' => $product->name,
            'thumb_image' => $product->thumb_image,
            'sku' => $product->sku,
            'slug' => $product->slug,
            'points' => $product->details->points ?? 0,
            'description' => $product->details->description ?? '',
            'site_title' => $product->details->site_title ?? '',
            'meta_title' => $product->details->meta_title ?? '',
            'meta_keyword' => $product->details->meta_keyword ?? '',
            'meta_description' => $product->details->meta_description ?? '',
            'meta_article_tags' => $product->details->meta_article_tags ?? '',
            'meta_script_tags' => $product->details->meta_script_tags ?? '',
            'video_link' => $product->details->video_link ?? '',
            'price' => $product->unit_price,
            'return_deadline' => $product->is_returnable ? $product->return_deadline : 0,
            'ratings_count' => $product->ratings_count,
            'average_rating' => number_format($product->averageRating, 2),
            'average_rating_percantage' => $averageRatingPercentage,
            'discount' => $product->is_discounted ? $product->discount : 0,
            'discount_type' => $product->discount_type,
            'discounted_price' => $discountedPrice,
            'current_stock' => $product->details->current_stock ?? 0,
            'is_low_stock' => isset($product->details) && $product->details->current_stock <= $product->details->low_stock_quantity,
            'is_COD_available' => $product->details->cash_on_delivery ?? false,
            'shipping_charge' => $product->details->shipping_charge ?? 0,
            'total_sold' => $product->details->number_of_sale ?? 0,
            'question' => $product->question,
            'ratings' => $product->ratings,
            'stage' => $product->stage,
            'images' => $product->image, 
            'key_features' => []
        ];

        $stockStatus = 'out_of_stock';
        $stockResponse = getProductStock($product->id, 1);
        $inCity = false;
        $stockResponse = getProductStock($product->id, 1);
        if ($stockResponse['status']) {
            $stockStatus = 'in_stock';
        }

        if( isset($stockResponse['in_city']) && $stockResponse['in_city'] == true) {
            $inCity = true;
        }
        $productDetails['stock_status'] = $stockStatus; 
        $productDetails['available_in_city'] = $inCity; 

        $tax_amount = 0;
        if ($product->taxes->isNotEmpty()) {
            foreach ($product->taxes as $tax) {
                if ($tax->tax_type == 'percent') {
                    $tax_amount += ($product->unit_price * $tax->tax) / 100;
                } else {
                    $tax_amount += $tax->tax;
                }
            }
        }
        
        $productDetails['tax'] = $tax_amount;

        if ($product->specifications->isNotEmpty()) {
            foreach ($product->specifications as $specification) {
                $productDetails['key_features'][] = [
                    'type_id' => $specification->specificationKeyType->id ?? null,
                    'type_name' => $specification->specificationKeyType->name ?? '',
                    'attribute_id' => $specification->specificationKeyTypeAttribute->id ?? null,
                    'attribute_name' => ($specification->specificationKeyTypeAttribute->name ?? '') . ' ' . ($specification->specificationKeyTypeAttribute->extra ?? ''),
                ];
            }
        }

        $ratings = $product['ratings'];
        $totalRatings = count($ratings);
        $ratingCounts = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];

        // Count each rating
        foreach ($ratings as $rating) {
            $ratingCounts[$rating->rating]++;
        }

        // Calculate percentage for each rating
        $ratingPercentages = [];
        foreach ($ratingCounts as $stars => $count) {
            $ratingPercentages[$stars] = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;
        }

        $productDetails['ratingCounts'] = $ratingCounts;
        $productDetails['ratingPercentages'] = $ratingPercentages;

        return $productDetails;
    }

    private function increaseCounter($product_id)
    {
        // Log or increment view
    }

    private function sentToVisitedList($product)
    {
        $visited = session()->get('visited_product_list', []);
        $visited[] = $product['id'];
        session()->put('visited_product_list', array_unique($visited));
    }


    public function getCategoryBreadcrumb($categoryId)
    {
        $breadcrumb = [];

        while ($categoryId) {
            $category = Category::find($categoryId);
            if (!$category) {
                break;
            }

            $breadcrumb[] = $category;
            $categoryId = $category->parent_id;
        }

        return array_reverse($breadcrumb);
    }
}
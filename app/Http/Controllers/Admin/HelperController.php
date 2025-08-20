<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Menu;
use App\Models\Category;
use App\Models\ProductDetail;
use App\Models\FlashDeal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\BrandRepositoryInterface;
use Illuminate\Support\Facades\DB;

class HelperController extends Controller
{

    protected $brandRepository;
    protected $productRepository;
    protected $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->brandRepository = $brandRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function checkSlug(Request $request)
    {
        $slug = $request->get('slug');
        $id = $request->get('id');

        if (isset($id)) {
            $exists = Category::where('slug', $slug)->where('id', '!=', $id)
                ->union(Product::where('slug', $slug)->where('id', '!=', $id))
                ->union(Offer::where('slug', $slug)->where('id', '!=', $id))
                ->union(Brand::where('slug', $slug)->where('id', '!=', $id))
                ->union(FlashDeal::where('slug', $slug)->where('id', '!=', $id))
                ->union(Page::where('slug', $slug)->where('id', '!=', $id))
                ->exists();
        } else {
            $exists = Category::where('slug', $slug)
                ->union(Product::where('slug', $slug))
                ->union(Offer::where('slug', $slug))
                ->union(Brand::where('slug', $slug))
                ->union(FlashDeal::where('slug', $slug))
                ->union(Page::where('slug', $slug))
                ->exists();
        }

        return response()->json(['exists' => $exists]);
    }

    public function searchMenu(Request $request)
    {
        $query = $request->input('query');
        $results = Menu::where('name', 'like', "%{$query}%")->limit(10)
            ->get(['name', 'slug']);

        return response()->json($results);
    }

    public function fetcher($slug, $index = 0)
    {
        $models = ['Product', 'Category', 'Brand', 'Page', 'Offer', 'FlashDeal'];

        if ($index >= count($models)) {
            return view('errors.404');
        }

        // Get the current model name
        $model = $models[$index];
        if ($model == 'Product' && Product::where('slug', $slug)->where('status', 1)->exists()) {
            $product = $this->productDetails($slug);
            if ($product) {

                $this->increaseCounter($product['id']);

                $this->sentToVisitedList($product);

                $breadcrumb = $this->getCategoryBreadcrumb($product['category']);

                $brand_id = null;
                if($product['brand_id']) {
                    $brand_id = $product['brand_id'];
                }

                $source = [
                    'category_id' => $product['category']->id,
                    'product_id' => $product['id'], 
                    'brand_id' => $brand_id 
                ];

                $related_products = $this->productRepository->index('related_products', [], $source);
                $same_category_products = $this->productRepository->index('same_category_products', [], $source);
                $same_brand_products = $this->productRepository->index('same_brand_products', [], $source);
                $visited_product_list = $this->productRepository->index('visited_product_list', [], session()->get('visited_product_list'));

                $spec = $this->productRepository->specificationProduct($product['id']);
                $keySpec = $this->productRepository->specificationKeyFeaturedProduct($product['id']);

                return view('frontend.product-details', compact('product', 'visited_product_list', 'same_brand_products', 'same_category_products', 'related_products', 'breadcrumb', 'keySpec', 'spec'));
            } else {
                return $this->fetcher($slug, $index + 1);
            }
        } elseif ($model == 'Category' && Category::where('slug', $slug)->where('status', 1)->exists()) {
            $model = $this->categoryRepository->getCategoryBySlug($slug);
            if ($model) {
                $Ids =$this->getAllDescendantIds($model);

                $categoryIdArray = $model->getAllCategoryIds();

                $allProductCount = Product::whereIn('category_id', $categoryIdArray)->where('status', 1)->count();
                $products = $this->productRepository->index($slug, $Ids);
                $productCount = $products->count();
                $breadcrumb = $this->getCategoryBreadcrumb($model);
                return view('frontend.listing', compact('model', 'allProductCount', 'productCount', 'products', 'categoryIdArray', 'breadcrumb'));
            } else {
                return $this->fetcher($slug, $index + 1);
            }
        } elseif ($model == 'Brand' && Brand::where('slug', $slug)->where('status', 1)->exists()) {
            $model = $this->brandRepository->getBrandBySlug($slug);
            if ($model) {

                $request['brand_id'] = $model->id;
                $products = $this->productRepository->index($request, null);

                $allProductCount = Product::where('brand_id', $model->id)->where('status', 1)->count();
                $productCount = $products->count();

                return view('frontend.brand-listing', compact('model', 'allProductCount', 'productCount', 'products'));
            } else {
                return $this->fetcher($slug, $index + 1);
            }
        } elseif ($model == 'Page' && Page::where('slug', $slug)->where('status', 1)->exists()) {
            $model = Page::where('status', 1)->where('slug', $slug)->first();
            if($model) {

                return view('frontend.page', compact('model'));
            } else {
                return $this->fetcher($slug, $index + 1);
            }
        } elseif ($model == 'FlashDeal' && FlashDeal::where('slug', $slug)->where('status', 1)->exists()) {
            $model = FlashDeal::where('status', 1)->where('slug', $slug)->first();
            if($model) {

                $starting_time = $model->starting_time;
                $deadline_type = $model->deadline_type;
                $deadline_time = $model->deadline_time;

                $deadline = strtotime("+{$deadline_time} {$deadline_type}", strtotime($starting_time));
                $current_time = time();

                $time_difference = $deadline - $current_time;
                $isCrossedDeadline = true;
                $hours = 0;
                $days = 0;
                $minutes = 0;
                $seconds = 0;

                if ($time_difference > 0) {
                    $isCrossedDeadline = false;

                    $days = floor($time_difference / (24 * 3600));
                    $hours = floor(($time_difference % (24 * 3600)) / 3600);
                    $minutes = floor(($time_difference % 3600) / 60);
                    $seconds = $time_difference % 60;

                }

                // dd($days, $hours, $minutes, $seconds);

                $products = $this->productRepository->flashDealProduct($model->id);

                return view('frontend.flash-deals-details', compact('model', 'days', 'isCrossedDeadline', 'hours', 'minutes', 'products', 'seconds'));
            } else {
                return $this->fetcher($slug, $index + 1);
            }
        } else {
            return $this->fetcher($slug, $index + 1);
        }

        // // Check for data in the current model
        // $data = $model::where('slug', $slug)->first();

        // // If no data found, recursively call fetcher with the next index
        // if (!$data) {
        //     return $this->fetcher($slug, $index + 1);
        // }

        // Return the data to the view based on the model

    }

    private function increaseCounter($productId) 
    {
        $detail = ProductDetail::where('product_id', $productId)->first();
        if($detail) {
            $detail->visitor_counter ++;
            $detail->save();
        }

        return 1;
    }

    public function sentToVisitedList($product)
    {
        $productId = $product['id'];
        $visited_product_list = session()->get('visited_product_list', []);

        if (!in_array($productId, $visited_product_list)) {
            $visited_product_list[] = $product['id'];
        }

        session()->put('visited_product_list', $visited_product_list);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $sort = $request->sort;
        $terms = $request->terms;

        $categories = Category::where('name', 'like', '%' . $search . '%')->get(['id', 'name', 'slug']);
        $categoryIds = $categories->pluck('id')->toArray(); 
        
        $brands = Brand::where('name', 'like', '%' . $search . '%')->get(['id', 'name', 'slug']);
        $brandIds = $brands->pluck('id')->toArray(); 
        
        $products = $this->productRepository->search($search,$categoryIds,$brandIds,$request->sort, $terms);
        return view('frontend.search', compact('search', 'sort','products','brands','categories'));
    }

    public function filterProduct(Request $request)
    {
        if ($request->ajax()) {
            $category = Category::find($request->category_id);

            if(isset($request->brand_id) && $request->brand_id != '') {
                $allIds = $request->brand_id;
                $products = $this->productRepository->index($request, null);
            } else {
                $allIds = $this->getAllDescendantIds($category);
                $products = $this->productRepository->index($request, $allIds);
            }

            return view('frontend.components.product_list', compact('products'));
        }
    }

    private function getAllDescendantIds($model) {
        $ids = [$model->id];
    
        $children = $model->children;

        if(isset($children)){

            foreach ($children as $child) {
                $ids = array_merge($ids, $this->getAllDescendantIds($child));
            }
        }
    
        return $ids;
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
            'brand_name' => $product->brand->name,
            'brand_slug' => $product->brand->slug,
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

    public function getCategoryBreadcrumb($category)
    {
        $breadcrumb = [];
        while ($category) {
            $breadcrumb[] = $category;
            $category = $category->parent;
        }

        return array_reverse($breadcrumb);
    }


    public function cacheClear()
    {
        Artisan::call('optimize:clear');

        return response()->json(['status' => true, 'message' => 'Optimized', 'load' => true]);
    }
}

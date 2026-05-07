<?php 

namespace App\Http\Controllers\Api\V1;

use App\CPU\Images;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\HomeCategory;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\FlashDeal;
use App\Models\ProductDetail;
use App\Models\ProductQuestion;
use App\Models\Rating;
use App\Repositories\Interface\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomepageApiController extends Controller
{
    protected $product;

    public function __construct(ProductRepositoryInterface $product)
    {
        $this->product = $product;
    }

    public function bestSellers(): JsonResponse
    {
        $products = Cache::remember('homeProducts_best_seller', now()->addMinutes(10), function () {
            return $this->product->index(request()->merge(['best_seller' => true]), null);
        });

        return response()->json(['data' => $products]);
    }

    public function featured(): JsonResponse
    {
        $products = Cache::remember('homeProducts_featured', now()->addMinutes(10), function () {
            return $this->product->index(request()->merge(['featured' => true]), null);
        });

        return response()->json(['data' => $products]);
    }

    public function deliveryMethods()
    {
        $methods = [];

        if(get_settings('enable_store_pickup') == 1) {
            $methods[] = [
                'method_name' => 'Store Pickup',
                'input_field_name' => 'store_pickup',
                'charge' => get_settings('store_pickup_fee'),
                'content' => get_settings('store_pickup_address')
            ];
        }

        if(get_settings('shipping_cost_type') == 'free_shipping') {
            $methods[] = [
                'method_name' => 'Free Shipping',
                'input_field_name' => 'free_shipping',
                'charge' => 0,
                'content' => ''
            ];
        }
        
        if(get_settings('shipping_cost_type') == 'flat_rate') {
            $methods[] = [
                'method_name' => 'Home Delivery',
                'input_field_name' => 'home_delivery',
                'charge' => get_settings('system_default_delivery_charge'),
                'content' => ''
            ];
        }

        return response()->json($methods);
    }

    public function paymentMethods()
    {
        $methods = [];

        $methods[] = [
            'method_name' => 'Cash on Delivery',
            'input_field_name' => 'cash_on_delivery',
            'content' => 'Pay when you get the order.'
        ];
        
        $methods[] = [
            'method_name' => 'Direct Bank Pay',
            'input_field_name' => 'manual_pay',
            'content' => 'Please proceed with the bank payment and ensure the payment transaction slip is uploaded afterward via your Order Portal.'
        ];

        if (env('SSLCOMMERZ_SANDBOX') != 'true') {
            $methods[] = [
                'method_name' => 'SSL Commerz',
                'input_field_name' => 'sslcommerz',
                'content' => 'Payments via SSLCommerz will follow our current <a href="/payment-policy" target="_blank">Payment Policy</a>.'
            ];
        }

        return response()->json($methods);
    }

    public function offered(): JsonResponse
    {
        $products = Cache::remember('homeProducts_offered', now()->addMinutes(10), function () {
            return $this->product->index(request()->merge(['offred' => true]), null);
        });

        return response()->json(['data' => $products]);
    }

    public function onSale(): JsonResponse
    {
        $products = Cache::remember('on_sale_products_', now()->addMinutes(10), function () {
            return $this->product->index(request()->merge(['on_sale_product' => true]), null);
        });

        return response()->json(['data' => $products]);
    }

    public function featuredList(): JsonResponse
    {
        $products = Cache::remember('featured_products_', now()->addMinutes(10), function () {
            return $this->product->index(request()->merge(['is_featured_list' => true]), null);
        });

        return response()->json(['data' => $products]);
    }

    public function topRated(): JsonResponse
    {
        $products = Cache::remember('top_rated_product', now()->addMinutes(10), function () {
            return $this->product->index(request()->merge(['top_rated_product' => true]), null);
        });

        return response()->json(['data' => $products]);
    }

    public function brands(): JsonResponse
    {
        $brands = Cache::remember('brands_', now()->addMinutes(10), function () {
            return Brand::select('slug', 'logo', 'name')->where('status', 1)->get();
        });

        return response()->json(['data' => $brands]);
    }

    public function sliders(): JsonResponse
    {
        $products = Cache::remember('newProducts', now()->addMinutes(10), function () {
            return $this->product->index(null, null);
        });

        return response()->json(['data' => $products]);
    }

    public function midBanners(): JsonResponse
    {
        $midBanners = Cache::remember('mid_banners', now()->addMinutes(300), function () {
            return Banner::where('status', 1)
                ->where('banner_type', 'mid')
                ->inRandomOrder()
                ->take(3)
                ->get();
        });

        return response()->json(['data' => $midBanners]);
    }

    public function trending(): JsonResponse
    {
        $products = Cache::remember('trending_', now()->addMinutes(10), function () {
            return $this->product->index(request()->merge(['trending' => true]), null);
        });

        return response()->json(['data' => $products]);
    }

    public function flashDeals(): JsonResponse
    {
        $flashDeals = Cache::remember('flash_deals_', now()->addMinutes(30), function () {
            return FlashDeal::where('status', 1)->with('products')->get();
        });

        return response()->json(['data' => $flashDeals]);
    }

    public function homeCategories(): JsonResponse
    {
        $homeCategories = Cache::remember('home_categories_', now()->addMinutes(30), function () {
            return HomeCategory::with('category')->where('status', 1)->get()->map(function ($homeCategory) {
                $allCategoryIds = $homeCategory->category->getAllCategoryIds();
                $products = Product::withCount('ratings')
                    ->whereIn('category_id', $allCategoryIds)
                    ->where('status', 1)
                    ->take(7)
                    ->get();
                return [
                    'category' => $homeCategory->category,
                    'products' => $products
                ];
            });
        });

        return response()->json(['data' => $homeCategories]);
    }

    public function submitQuestionForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string',
            'question' => 'required|string|max:2500'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::where('slug', $request->slug)->where('status', 1)->first();
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found']);
        }

        ProductQuestion::create([
            'product_id' => $product->id,
            'user_id' => auth('api')->check() ? auth('api')->user()->id : null,
            'name' => $request->name,
            'message' => $request->question
        ]);

        return response()->json([
            'status' => true, 
            'message' => 'We have received your question and will respond to it shortly.'
        ]);
    }

    public function submitReviewForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug'    => 'required|string',
            'rating'  => 'required|numeric',
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|email|max:255',
            'message' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        if ($request->rating < 1) {
            return response()->json(['status' => false, 'message' => 'Please add a rating first.']);
        }

        $product = Product::where('slug', $request->slug)->where('status', 1)->first();
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found']);
        }

        $files = [];
        $filesName = '';
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if ($fileName = Images::upload('reviews', $file)) {
                    $files[] = $fileName;
                }
            }
            $filesName = implode(',', $files);
        }


        // add to rating table
        $rating = Rating::create([
            'product_id' => $product->id,
            'user_id'   => auth('api')->check() ? auth('api')->user()->id : null,
            'name'      => $request->name,
            'email'     => $request->email,
            'rating'    => $request->rating,
            'review'    => $request->message,
            'files'     => $filesName
        ]);

        if ($rating) {
            $numberOfRating = Rating::where('product_id', $product->id)->count();
            $newNumberOfRating = $numberOfRating;

            $averageRating = (Rating::where('product_id', $product->id)->sum('rating') / $numberOfRating);

            $details = ProductDetail::where('product_id', $product->id)->first();
            $details->number_of_rating = $newNumberOfRating;
            $details->average_rating = $averageRating;
            $details->save();
        }

        return response()->json([
            'status' => true, 
            'message' => 'Review Submitted Successfully'
        ]);
    }
}

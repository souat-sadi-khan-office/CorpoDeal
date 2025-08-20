<?php

namespace App\Http\Controllers\Frontend;

use App\CPU\Images;
use Exception;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Rating;
use App\Models\Country;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Category;
use App\Models\WishList;
use App\Models\CartDetail;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Models\ProductQuestion;
use App\Models\HomepageSettings;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\FlashDeal;
use App\Models\HomeCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\BannerRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\FlashDealRepositoryInterface;
use App\Repositories\Interface\BrandRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Interface\CouponRepositoryInterface;
use App\Repositories\Interface\LaptopBudgetRepositoryInterface;
use App\Repositories\Interface\LaptopFinderPurposeRepositoryInterface;
use App\Repositories\Interface\LaptopFinderScreenRepositoryInterface;
use App\Repositories\Interface\LaptopFinderPortabilityRepositoryInterface;
use App\Repositories\Interface\LaptopFinderFeaturesRepositoryInterface;

class HomePageController extends Controller
{
    private $banner;
    private $brands;
    private $product;
    private $flashDeals;
    private $userRepository;
    private $couponRepository;

    private $laptopBudgetRepository;
    private $LaptopFinderPurposeRepository;
    private $laptopFinderScreenRepository;
    private $laptopFinderPortabilityRepository;
    private $laptopFinderFeaturesRepository;

    public function __construct(
        BannerRepositoryInterface $banner,
        ProductRepositoryInterface $product,
        BrandRepositoryInterface $brands,
        FlashDealRepositoryInterface $flashDeals,
        UserRepositoryInterface $userRepository,
        CouponRepositoryInterface $couponRepository,

        LaptopBudgetRepositoryInterface $laptopBudgetRepository,
        LaptopFinderPurposeRepositoryInterface $LaptopFinderPurposeRepository,
        LaptopFinderScreenRepositoryInterface $laptopFinderScreenRepository,
        LaptopFinderPortabilityRepositoryInterface $laptopFinderPortabilityRepository,
        LaptopFinderFeaturesRepositoryInterface $laptopFinderFeaturesRepository,
    )
    {
        $this->brands = $brands;
        $this->banner = $banner;
        $this->product = $product;
        $this->flashDeals = $flashDeals;
        $this->userRepository = $userRepository;
        $this->couponRepository = $couponRepository;

        $this->laptopBudgetRepository = $laptopBudgetRepository;
        $this->LaptopFinderPurposeRepository = $LaptopFinderPurposeRepository;
        $this->laptopFinderScreenRepository = $laptopFinderScreenRepository;
        $this->laptopFinderPortabilityRepository = $laptopFinderPortabilityRepository;
        $this->laptopFinderFeaturesRepository = $laptopFinderFeaturesRepository;
    }

    public function visibility(Request $request, $section)
    {
        try {
            $validSections = [
                'bannerSection',
                'sliderSection',
                'midBanner',
                'dealOfTheDay',
                'trending',
                'brands',
                'popularANDfeatured',
                'newslatter',
            ];

            if (!in_array($section, $validSections)) {
                return response()->json(['error' => 'Invalid section provided.', 'success' => false]);
            }

            $settings = HomepageSettings::first();

            if ($settings) {
                $settings->$section = !$settings->$section;
                $settings->last_updated_by = Auth::guard('admin')->id();
                $settings->save();

                Session::put('homepage_setting.' . $section, $settings->$section);
                Session::put('homepage_setting.last_updated_by', Auth::guard('admin')->user()->name);
                Session::put('homepage_setting.last_updated_at', $settings->updated_at);

                return response()->json(['success' => true, 'message' => Str::upper($section) . ' Section status updated successfully.']);
            } else {
                return response()->json(['error' => 'Homepage settings not found.', 'success' => false]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'success' => false]);
        }
    }

    public function laptopBuyingGuide()
    {
        $laptopBudgets = $this->laptopBudgetRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopPurposes = $this->LaptopFinderPurposeRepository->all()->select('id', 'name', 'details', 'status')->where('status', 1);
        $laptopScreenSizes = $this->laptopFinderScreenRepository->all()->select('id', 'name', 'details', 'status')->where('status', 1);
        $laptopPortables = $this->laptopFinderPortabilityRepository->all()->select('id', 'name', 'details', 'status')->where('status', 1);
        $laptopFeatures = $this->laptopFinderFeaturesRepository->all()->select('id', 'name', 'details', 'status')->where('status', 1);


        // Retrieve the budget ID from the request and store it in the session
        $budget_id = Session::has('laptop_finder_budget') ? Session::get('laptop_finder_budget') : [];
        $screen_size_id = Session::has('laptop_finder_screen_size_id') ? Session::get('laptop_finder_screen_size_id') : null;
        $portable_id = Session::has('laptop_finder_portable_id') ? Session::get('laptop_finder_portable_id') : null;
        $feature_id_array = Session::has('laptop_finder_features_id') ? Session::get('laptop_finder_features_id') : [];
        $purpose_id_array = Session::has('laptop_finder_purpose_id') ? Session::get('laptop_finder_purpose_id') : [];

        // Get the default laptop category and its child categories
        $default_laptop_category = Category::find(get_settings('default_laptop_category'));
        $category = new Category;
        $laptopCategoryIds = $category->getChildCategories($default_laptop_category);
        $laptopCategoryIds = collect($laptopCategoryIds)->pluck('id');
        $laptopCategoryIds->push($default_laptop_category->id);

        // Initialize the product query
        $productQuery = Product::whereIn('category_id', $laptopCategoryIds);

        // Apply filters if they are not null or empty
        if ($budget_id) {
            $productQuery->whereHas('budgets', function ($query) use ($budget_id) {
                $query->where('budget_id', $budget_id);
            });
        }

        if ($screen_size_id) {
            $productQuery->whereHas('screenSizes', function ($query) use ($screen_size_id) {
                $query->where('size_id', $screen_size_id);
            });
        }

        if ($portable_id) {
            $productQuery->whereHas('portabilites', function ($query) use ($portable_id) {
                $query->where('portable_id', $portable_id);
            });
        }

        if (!empty($feature_id_array)) {
            $productQuery->whereHas('features', function ($query) use ($feature_id_array) {
                $query->whereIn('feature_id', $feature_id_array);
            });
        }

        if (!empty($purpose_id_array)) {
            $productQuery->whereHas('purposes', function ($query) use ($purpose_id_array) {
                $query->whereIn('purpose_id', $purpose_id_array);
            });
        }

        $laptopCounter = $productQuery->count() ?? 0;

        return view('frontend.laptop-buying-guide', compact('laptopBudgets', 'laptopPurposes', 'laptopScreenSizes', 'laptopPortables', 'laptopFeatures', 'laptopCounter'));
    }

    public function flashDealsPage()
    {
        $deals = FlashDeal::where('status', 1)->orderBy('id', 'DESC')->get();
        return view('frontend.flash-deals', compact('deals'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function compare()
    {
        list($specifications, $models, $product_id_array) = $this->product->compare();

        $product_id_array = array_reverse($product_id_array);
        // dd($product_id_array, $specifications, $models);
        return view('frontend.compare', compact('models', 'product_id_array', 'specifications'));
    }

    public function removeCompare($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if ($product) {
            if (session()->has('compare_list') && is_array(session()->get('compare_list'))) {
                if (in_array($product->id, session()->get('compare_list'))) {
                    $newCompareList = array_diff(session()->get('compare_list'), [$product->id]);
                    session()->put('compare_list', $newCompareList);
                }
            }
        }

        return redirect()->route('compare');
    }

    public function submitContactForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^(\+88)?01[3-9]\d{8}$/',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model = new ContactMessage;
        $model->user_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
        $model->name = $request->name;
        $model->phone = $request->phone;
        $model->subject = $request->subject;
        $model->message = $request->message;
        $model->save();

        return response()->json(['status' => true, 'message' => 'Thank you for contacting us. We will back to you shortly.']);
    }

    public function index(Request $request)
    {

        $banners = Cache::remember('banners', now()->addMinutes(300), function () {
            $data = $this->banner->getAllBanners();

            return $data->where('status', 1)
                ->groupBy('banner_type')
                ->filter(function ($group, $key) {
                    if ($key === 'main_sidebar' && $group->count() >= 2) {
                        return $group->shuffle()->take(2);
                    }
                    return $key !== 'main_sidebar' ? $group : collect();
                });
        });


        $n = isset($request->best_seller) ? 'best_seller' : (isset($request->featured) ? 'featured' : (isset($request->offred) ? 'offred' : ''));


        if ($request->ajax()) {
            $products = Cache::remember('homeProducts_' . $n, now()->addMinutes(10), function () use ($request) {
                return $this->product->index($request, null);
            });

            if (isset($request->best_seller)) {
                return view('frontend.homepage.sellers-tab', compact('products'));
            } elseif (isset($request->featured)) {
                return view('frontend.homepage.featured-tab', compact('products'));
            } elseif (isset($request->offred)) {
                return view('frontend.homepage.offred-tab', compact('products'));
            } elseif (isset($request->on_sale_product)) {

                $products = Cache::remember('on_sale_products_', (36000 * 10), function () use ($request) {
                    return $this->product->index($request, null);
                });

                return view('frontend.homepage.on-sale-tab-tab', compact('products'));
            } elseif (isset($request->is_featured_list)) {
                $products = Cache::remember('featured_products_', (36000 * 10), function () use ($request) {
                    return $this->product->index($request, null);
                });

                return view('frontend.homepage.featured-list-tab', compact('products'));
            } elseif (isset($request->top_rated_product)) {
                $products = Cache::remember('top_rated_product', (36000 * 10), function () use ($request) {
                    return $this->product->index($request, null);
                });

                return view('frontend.homepage.section.top-rated-product-section', compact('products'));
            } elseif (isset($request->brands)) {

                $brands = Cache::remember('brands_', (36000 * 10), function () use ($request) {
                    return $this->brands->getAllBrands()->select('slug', 'logo', 'name', 'status')->where('status', 1);
                });

                return view('frontend.homepage.section.brands-section', compact('brands'));
            } elseif (isset($request->slider_section)) {

                $newProducts = Cache::remember('newProducts', now()->addMinutes(10), function () {
                    return $this->product->index(null, null);
                });

                return view('frontend.homepage.section.slider-section', compact('newProducts'));

            } elseif (isset($request->mid_banner_section)) {

                $midBanners = Cache::remember('mid_banners', now()->addMinutes(300), function () {
                    $data = $this->banner->getAllBanners();

                    return $data->where('status', 1)
                        ->groupBy('banner_type')
                        ->filter(function ($group, $key) {
                            if ($key === 'mid' && $group->count() >= 3) {
                                return $group->shuffle()->take(3);
                            }
                        });
                });

                return view('frontend.homepage.section.mid-banner-section', compact('midBanners'));

            } elseif (isset($request->trending)) {

                $products = Cache::remember('trending_', (36000 * 10), function () use ($request) {
                    return $this->product->index($request, null);
                });

                return view('frontend.homepage.section.trending-section', compact('products'));
            } elseif (isset($request->flash_deals)) {

                $flashDeals = $this->flashDeals();

                return view('frontend.homepage.section.flash-deals-section', compact('flashDeals'));
            } elseif (isset($request->home_page_categories)) {
                $homeCategories = Cache::remember('home_categories_', (36000 * 10), function () {
                    $data = HomeCategory::with(['category' => function ($query) {
                        $query->where('status', 1);
                    }])->where('status', 1)
                        ->get()
                        ->map(function ($homeCategory) {
                        $allCategoryIds = $homeCategory->category->getAllCategoryIds();
                        $homeCategory->category->product = Product::withCount('ratings')->whereIn('category_id', $allCategoryIds)
                            ->where('status', 1)
                            ->take(7)
                            ->get()
                            ->map(function ($product) {
                                return $this->product->accessMapper($product);
                            });
                        return $homeCategory;
                    });
                    return $data;
                });

                return view('frontend.homepage.section.home-category-section', compact('homeCategories'));
            }
        }


        $featuredCategory = Cache::remember('featured_categories', now()->addMinutes(10), function () {
            return Category::select('status', 'photo', 'is_featured', 'parent_id', 'name', 'slug', 'icon')->where('status', 1)->where('is_featured', 1)->where('parent_id', null)->orderBy('id', 'DESC')->get();
        });

        return view('frontend.homepage.index', compact('banners', 'featuredCategory'));
    }

    public function quickview($slug)
    {
        $product = Cache::remember($slug, now()->addMinutes(10), function () use ($slug) {
            return $this->product->quickview($slug);
        });
        return view('frontend.modals.quick-view', compact('product'));
    }

    private function flashDeals()
    {

        return Cache::remember('flashDeals', now()->addMinutes(2), function () {

            $deals = $this->flashDeals->getAllDeals();

            $now = Carbon::now();

            if ($deals->isNotEmpty()) {
                return $deals->filter(function ($deal) use ($now) {
                    if ($deal->status != 1 || $deal->type->isEmpty()) {
                        return false;
                    }

                    $startingTime = get_system_date($deal->starting_time);
                    $endTime = null;

                    // Calculate end time based on deadline_type
                    switch ($deal->deadline_type) {
                        case 'day':
                            $endTime = Carbon::parse($startingTime)->addDays($deal->deadline_time);
                            break;
                        case 'hour':
                            $endTime = Carbon::parse($startingTime)->addHours($deal->deadline_time);
                            break;
                        case 'minute':
                            $endTime = Carbon::parse($startingTime)->addMinutes($deal->deadline_time);
                            break;
                        case 'week':
                            $endTime = Carbon::parse($startingTime)->addWeeks($deal->deadline_time);
                            break;
                        case 'month':
                            $endTime = Carbon::parse($startingTime)->addMonths($deal->deadline_time);
                            break;
                        default:
                            break;
                    }

                    $deal->end_time = $endTime ? $endTime->toDateTimeString() : null;

                    return $endTime && $endTime->isFuture();
                })->map(function ($deal) {

                    $dealTypes = $deal->type()->select('id', 'product_id')->get();

                    $productDetails = $dealTypes->map(function ($type) {
                        $product = $type->product()
                            ->with(['details' => function ($query) {
                                $query->select('product_id', 'current_stock', 'number_of_sale');
                            }])
                            ->select('id', 'thumb_image', 'name', 'slug', 'unit_price', 'discount_type', 'discount')
                            ->first();

                        $discountedPrice = $product ? $product->unit_price : 0;

                        if ($product && $product->discount_type && $product->discount > 0) {
                            $discountAmount = $product->discount_type == 'amount'
                                ? $product->discount
                                : ($product->unit_price * ($product->discount / 100));
                            $discountedPrice = $product->unit_price - $discountAmount;
                        }

                        $currentStock = $product && $product->details ? $product->details->current_stock : 0;
                        $numberOfSale = $product && $product->details ? $product->details->number_of_sale : 0;

                        return [
                            'id' => $product ? $product->id : null,
                            'name' => $product ? $product->name : null,
                            'thumb_image' => $product ? $product->thumb_image : null,
                            'slug' => $product ? $product->slug : null,
                            'unit_price' => $product ? format_price(convert_price($product->unit_price)) : null,
                            'discounted_price' => format_price(convert_price($discountedPrice)),
                            'current_stock' => $currentStock,
                            'number_of_sale' => $numberOfSale,
                        ];
                    });


                    $deal->starting_time = get_system_date($deal->starting_time);
                    $deal->product_details = $productDetails;

                    return [
                        'id' => $deal->id,
                        'title' => $deal->title,
                        'slug' => $deal->slug,
                        'image' => $deal->image,
                        'starting_time' => $deal->starting_time,
                        'end_time' => $deal->end_time,
                        'product_details' => $productDetails
                    ];
                });
            }
        });
    }

    public function submitQuestionForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'product' => 'nullable|string',
            'message' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::where('slug', $request->product)->where('status', 1)->first();
        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found']);
        }

        ProductQuestion::create([
            'product_id' => $product->id,
            'user_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null,
            'name' => $request->name,
            'message' => $request->message
        ]);

        return response()->json(['status' => true, 'message' => 'We have received your question and will respond to it shortly.', 'load' => true]);
    }

    public function submitReviewForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'product' => 'nullable|string',
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

        $product = Product::where('slug', $request->product)->where('status', 1)->first();
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
            'user_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null,
            'name' => $request->name,
            'email' => $request->email,
            'rating' => $request->rating,
            'review' => $request->message,
            'files' => $filesName
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

        return response()->json(['status' => true, 'message' => 'Review Submitted Successfully', 'load' => true]);
    }

    public function addToCompareList(Request $request)
    {
        $productId = $request->id;

        $product = Product::where('slug', $productId)->first();
        if(!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ]);
        }

        $compareList = session()->get('compare_list', []);
        if (!in_array($product->id, $compareList)) {
            $compareList[] = $product->id;
        } else {
            return response()->json([
                'status' => false,
                'message' => 'This product is already added to your compare list.',
            ]);
        }

        if (count($compareList) > 3) {
            return response()->json([
                'status' => false,
                'message' => 'You can not add more then 3 product at a time.',
            ]);
        }

        session()->put('compare_list', $compareList);

        $counter = count($compareList);

        return response()->json([
            'status' => true,
            'counter' => $counter,
            'message' => 'Product added to compare list successfully.',
        ]);
    }

    public function addToWishList(Request $request)
    {
        $productId = $request->id;

        if (!Auth::guard('customer')->check()) {
            return response()->json(['status' => false, 'message' => 'You must login or create an account to save products on your wishlist.']);
        }

        $userId = Auth::guard('customer')->user()->id;

        if (WishList::where('user_id', $userId)->where('product_id', $productId)->first()) {
            return response()->json(['status' => false, 'message' => 'This product is already added to your wishlist.']);
        }

        WishList::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return response()->json(['status' => true, 'message' => 'Successfully added to your Wishlist.']);
    }

    public function currencyChange(Request $request)
    {
        $city = City::find($request->global_country_id);

        if ($request->global_currency_id) {
            $currency = Currency::find($request->global_currency_id);

            // For Currency
            $request->session()->put('currency_id', $currency->id);
            $request->session()->put('currency_code', $currency->code);
            $request->session()->put('currency_symbol', $currency->symbol);
            $request->session()->put('currency_exchange_rate', $currency->exchange_rate);
        }

        // for country -> city
        $request->session()->put('user_city_id', $city->id);
        $request->session()->put('user_city', $city->name);
        $request->session()->put('user_city_selected', $city->name);
        // $request->session()->put('country_flag', asset($country->image));

        session()->flash('success', 'City changed to ' . $city->name);
    }

    public function allCategories()
    {
        $categories = Category::withCount('children')
            ->where('status', 1)
            ->whereNull('parent_id')
            ->orderByDesc('children_count')
            ->orderBy('name', 'ASC') 
            ->get();
            
        return view('frontend.categories', compact('categories'));
    }

    public function getAllCategories()
    {
        $categories = Category::with('children')
            ->where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('name', 'ASC')
            ->get();

        return view('frontend.components.category', compact('categories'));
    }

    public function allBrands()
    {
        $brands = $this->brands
            ->getAllBrands()
            ->where('status', 1);

        return view('frontend.brands', compact('brands'));
    }

    public function postNewsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $email = $request->email;

        if (Subscriber::where('email', $email)->first()) {
            return response()->json(['status' => false, 'message' => 'You are already subscribed']);
        }

        Subscriber::create([
            'email' => $email
        ]);

        return response()->json(['status' => true, 'message' => 'Thank you for subscribe']);
    }

    public function couponCheck(Request $request)
    {
        $data['coupon_code'] = $request->coupon;
        return $this->couponRepository->checkCoupon($data);
    }

    public function couponCodes()
    {
        $lifeTimeCoupon = Coupon::where('status', 1)->where('is_sellable', 0)->where('start_date', null)->get();
        $lifeTimeSellableCoupon = Coupon::where('status', 1)->where('is_sellable', 1)->where('start_date', null)->get();
        $limitedTimeFreeCoupon = Coupon::where('status', 1)->where('is_sellable', 0)->where('start_date', '!=', null)->get();
        $limitedTimeSellableCoupon = Coupon::where('status', 1)->where('is_sellable', 1)->where('start_date', '!=', null)->get();

        return view('frontend.coupon_codes', compact('lifeTimeCoupon', 'limitedTimeFreeCoupon', 'limitedTimeSellableCoupon', 'lifeTimeSellableCoupon'));
    }

    public function couponBuy(Request $request)
    {
        $data['coupon_code'] = $request->coupon;
        return $this->couponRepository->buyCoupon($data);
    }
}

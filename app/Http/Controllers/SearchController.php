<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\ProductBudget;
use App\Models\ProductFeature;
use App\Models\ProductPurpose;
use App\Models\ProductScreenSize;

use App\Models\ProductPortability;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\BannerController;
use App\Models\User;
use App\Repositories\Interface\BrandRepositoryInterface;
use App\Repositories\Interface\BannerRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\BrandTypeRepositoryInterface;

class SearchController extends Controller
{
    private $brandTypeRepository;
    private $brandRepository;
    private $bannerRepository;
    private $productRepository;

    public function __construct(
        BrandTypeRepositoryInterface $brandTypeRepository,
        BrandRepositoryInterface $brandRepository,
        BannerRepositoryInterface $bannerRepository,
        ProductRepositoryInterface $productRepository,
    ) {
        $this->brandTypeRepository = $brandTypeRepository;
        $this->brandRepository = $brandRepository;
        $this->bannerRepository = $bannerRepository;
        $this->productRepository = $productRepository;
    }

    public function getLapTopByFinder(Request $request)
    {
        // Retrieve the budget ID from the request and store it in the session
        if($request->budget_id) {
            $budget_id = $request->budget_id;
            Session::put('laptop_finder_budget', $budget_id);

            // Retrieve other parameters from the session if they exist
            $screen_size_id = Session::has('laptop_finder_screen_size_id') ? Session::get('laptop_finder_screen_size_id') : null;
            $portable_id = Session::has('laptop_finder_portable_id') ? Session::get('laptop_finder_portable_id') : null;
            $feature_id_array = Session::has('laptop_finder_features_id') ? Session::get('laptop_finder_features_id') : [];
            $purpose_id_array = Session::has('laptop_finder_purpose_id') ? Session::get('laptop_finder_purpose_id') : [];
        } else if ($request->purposes) {
            $purpose_id_array = $request->purposes;
            Session::put('laptop_finder_purpose_id', $purpose_id_array);

            // Retrieve other parameters from the session if they exist
            $screen_size_id = Session::has('laptop_finder_screen_size_id') ? Session::get('laptop_finder_screen_size_id') : null;
            $portable_id = Session::has('laptop_finder_portable_id') ? Session::get('laptop_finder_portable_id') : null;
            $feature_id_array = Session::has('laptop_finder_features_id') ? Session::get('laptop_finder_features_id') : [];
            $budget_id = Session::has('laptop_finder_budget') ? Session::get('laptop_finder_budget') : [];
        } else if ($request->screen_size) {
            $screen_size_id = $request->screen_size;
            Session::put('laptop_finder_screen_size_id', $screen_size_id);

            // Retrieve other parameters from the session if they exist
            $purpose_id_array = Session::has('laptop_finder_purpose_id') ? Session::get('laptop_finder_purpose_id') : [];
            $portable_id = Session::has('laptop_finder_portable_id') ? Session::get('laptop_finder_portable_id') : null;
            $feature_id_array = Session::has('laptop_finder_features_id') ? Session::get('laptop_finder_features_id') : [];
            $budget_id = Session::has('laptop_finder_budget') ? Session::get('laptop_finder_budget') : [];
        } else if ($request->portability) {
            $portable_id = $request->portability;
            Session::put('laptop_finder_portable_id', $portable_id);

            // Retrieve other parameters from the session if they exist
            $screen_size_id = Session::has('laptop_finder_screen_size_id') ? Session::get('laptop_finder_screen_size_id') : null;
            $purpose_id_array = Session::has('laptop_finder_purpose_id') ? Session::get('laptop_finder_purpose_id') : [];
            $feature_id_array = Session::has('laptop_finder_features_id') ? Session::get('laptop_finder_features_id') : [];
            $budget_id = Session::has('laptop_finder_budget') ? Session::get('laptop_finder_budget') : [];
        } else {
            $feature_id_array = $request->features;
            Session::put('laptop_finder_features_id', $feature_id_array);

            // Retrieve other parameters from the session if they exist
            $screen_size_id = Session::has('laptop_finder_screen_size_id') ? Session::get('laptop_finder_screen_size_id') : null;
            $portable_id = Session::has('laptop_finder_portable_id') ? Session::get('laptop_finder_portable_id') : null;
            $budget_id = Session::has('laptop_finder_budget') ? Session::get('laptop_finder_budget') : [];
            $purpose_id_array = Session::has('laptop_finder_purpose_id') ? Session::get('laptop_finder_purpose_id') : [];
        }

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

        return response()->json(['status' => true, 'counter' => $laptopCounter]);
    }

    public function clearLaptopSearch()
    {
        if (Session::has('laptop_finder_features_id')) {
            Session::forget('laptop_finder_features_id');
        }

        if (Session::has('laptop_finder_screen_size_id')) {
            Session::forget('laptop_finder_screen_size_id');
        }

        if (Session::has('laptop_finder_portable_id')) {
            Session::forget('laptop_finder_portable_id');
        }

        if (Session::has('laptop_finder_budget')) {
            Session::forget('laptop_finder_budget');
        }

        if (Session::has('laptop_finder_purpose_id')) {
            Session::forget('laptop_finder_purpose_id');
        }

        return response()->json(['status' => true, 'message' => 'Parameter cleared successfully.']);

    }

    public function filterProducts(Request $request)
    {
        $query = Product::query();

        // Stock Availability Filter
        if ($request->has('in_stock') && $request->in_stock == 1) {
            $query->where('in_stock', 1);
        }

        if ($request->has('out_of_stock') && $request->out_of_stock == true) {
            $query->where('in_stock', 0);
        }

        if ($request->pre_order) {
            $query->where('stage', 'pre_order');
        }

        if ($request->up_coming) {
            $query->where('stage', 'up_coming');
        }

        // Sorting Filter
        if ($request->sortBy == 'popularity') {
            $query->orderBy('average_rating', 'asc');
        } elseif ($request->sortBy == 'date') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sortBy == 'price') {
            $query->orderBy('unit_price', 'asc');
        } elseif ($request->sortBy == 'price-desc') {
            $query->orderBy('unit_price', 'desc');
        }

        // Filter by brand
        if ($request->has('brands') && !empty($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Filter by specifications
        if ($request->has('specifications') && !empty($request->specifications)) {
            $query->whereHas('specifications', function($specificationQuery) use ($request) {
                $specificationQuery->whereIn('attribute_id', $request->specifications);
            });
        }

        // Data Showing Filter
        if ($request->showData) {
            $products = $query->paginate($request->showData);
        } else {
            $products = $query->paginate(18);
        }

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        // dd(vsprintf(str_replace('?', '%s', $sql), array_map(function ($binding) {
        //     return is_numeric($binding) ? $binding : "'$binding'";
        // }, $bindings)));

        $html = view('frontend.components.product_list', compact('products'))->render();
        return response()->json($html);
    }

    public function ajaxSearch(Request $request)
    {
        $products_query = Product::query();
        $query = $request->search;
        $request->merge(['search_module' => 'ajax_search']);
        // $request->search_module = 'ajax_search';

        $products = $this->productRepository->index($request, null);

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);
        $brands = Brand::where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (sizeof($categories) > 0 || sizeof($products) > 0) {
            return view('frontend.search_content', compact('products', 'brands', 'categories'));
        }
        return '0';
    }

    public function ajaxSearchProduct(Request $request)
    {
        $query = $request->input('search');
        $products = Product::where('status', 1)->where('name', 'LIKE', "%{$query}%")
        ->take(5)
        ->get(['slug', 'name'])
        ->map(function ($product) {
            $product->name = Str::limit($product->name, 50);
            return $product;
        });

        return response()->json($products);
    }

    // for searching by types using brand_id
    public function searchForBrandTypes(Request $request)
    {
        $brandId = $request->brand_id;

        $brand = $this->brandRepository->findBrandById($brandId);
        if(!$brand) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found'
            ]);
        }

        $brandTypes = $this->brandTypeRepository->getAllBrandTypesByBrandId($brandId);

        return response()->json([
            'status' => true,
            'responses' => $brandTypes
        ]);
    }

    // for searching categories
    public function searchByBrands(Request $request)
    {
        $json = [];
        if(!isset($request->searchTerm)){
            $brands = Brand::select('id', 'name', 'logo')->where('status', 1)->get();

            foreach($brands as $brand) {
                $json[] = [
                    'id' => $brand->id,
                    'text' => $brand->name ,
                    'image_url' => asset($brand->logo)
                ];
            }
        } else {
            $search = $request->searchTerm;

            $brands = Brand::select('id', 'name', 'logo')->where('name', $search)->where('status', 1)->get();

            $json = [];
            foreach($brands as $brand) {
                $json[] = [
                    'id' => $brand->id,
                    'text' => $brand->name,
                    'image_url' => asset($brand->logo)
                ];
            }
        }

        return response()->json($json);
    }
    // for searching product
    public function searchByProduct(Request $request)
    {
        $json = [];
        if(!isset($request->searchTerm)){
            $products = Product::select('id', 'name', 'thumb_image')->where('status', 1)->take(10)->get();

            foreach($products as $product) {
                $json[] = [
                    'id' => $product->id,
                    'text' => $product->name ,
                    'image_url' => asset($product->thumb_image)
                ];
            }
        } else {
            $search = $request->searchTerm;

            $products = Product::select('id', 'name', 'thumb_image')->where('name','like', "%$search%")->where('status', 1)->get();

            $json = [];
            foreach($products as $brand) {
                $json[] = [
                    'id' => $brand->id,
                    'text' => $brand->name,
                    'image_url' => asset($brand->thumb_image)
                ];
            }

        }

        return response()->json($json);
    }

    // searching for category by id
    public function searchByCategoryId(Request $request)
    {
        $category_id = $request->category_id;

        if(!$category_id) {
            return [
                'status' => false,
                'message' => 'Category not found'
            ];
        }

        $category = Category::find($category_id);

        return [
            'status' => true,
            'id' => $category->id,
            'text' => $category->name,
            'thumb_image' => asset($category->photo)
        ];
    }

    // searching for product by id
    public function searchByProductId(Request $request)
    {
        $product_id = $request->product_id;

        if(!$product_id) {
            return [
                'status' => false,
                'message' => 'Product not found'
            ];
        }

        $product = Product::find($product_id);

        return [
            'status' => true,
            'id' => $product->id,
            'text' => $product->name,
            'thumb_image' => asset($product->thumb_image)
        ];
    }

    // searching for brand by id
    public function searchByBrandId(Request $request)
    {
        $brand_id = $request->brand_id;

        if(!$brand_id) {
            return [
                'status' => false,
                'message' => 'Brand not found'
            ];
        }

        $brand = Brand::find($brand_id);

        return [
            'status' => true,
            'id' => $brand->id,
            'text' => $brand->name,
            'thumb_image' => asset($brand->logo)
        ];
    }

    // for searching categories
    public function searchByCustomer(Request $request)
    {
        if(!isset($request->searchTerm)){
            $users = User::where('status', 1)->orderBy('name', 'ASC')->get();

            $json = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' ('. $user->email . ')',
                ];
            });
        } else {
            $search = $request->searchTerm;

            $users = User::where('status', 1)->where('name','like', "%$search%")->orWhere('name','like', "%$search%")->orderBy('name', 'ASC')->get();

            $json = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' ('. $user->email . ')',
                ];
            });

        }

        return response()->json($json);
    }

    // for searching categories
    public function searchByCategory(Request $request)
    {
        if(!isset($request->searchTerm)){
            $categories = Category::where('parent_id', null)->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });
        } else {
            $search = $request->searchTerm;

            $categories = Category::where('name','like', "%$search%")->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });

        }

        return response()->json($json);
    }

    public function searchParentCategoryForLaptop(Request $request)
    {
        $selectedCategoryId = $request->category_id;
        $category = Category::find($selectedCategoryId);

        $status = false;
        if ($category && $category->hasParentCategory(get_settings('default_laptop_category'))) {
            $status = true;
        }

        return response()->json($status);
    }

    public function searchByParentCategory(Request $request)
    {
        if(!isset($request->searchTerm)){
            $categories = Category::where('parent_id', null)->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });
        } else {
            $search = $request->searchTerm;

            $categories = Category::where('parent_id', null)->where('name','like', "%$search%")->get();

            $json = $categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                    'image_url' => $category->photo ? asset($category->photo) : asset('pictures/placeholder.jpg')
                ];
            });

        }

        return response()->json($json);
    }

    // for product data
    public function searchForProductDetails(Request $request)
    {
        $productIds = $request->data;

        if ($productIds != null) {
            $product = Product::where('id', $productIds)
                ->with(['stock' => function ($query) {
                    $query->where('in_stock', true)
                        ->where('in_stock', true)->orderBy('stock', 'desc')->limit(1);
                }])
                ->first();

            if ($product) {
                // $result = $products->map(function ($product) {
                    $stock = optional($product->stock->first())->stock ?? 0;

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'thumb_image' => url($product->thumb_image),
                        'unit_price' => round(covert_to_defalut_currency(get_product_price($product)['discounted_price']), 3),
                        'stock' => $stock,
                    ];
                // });

                return response()->json($result, 200);
            }
        } else {
            return response()->json();
        }

        return response()->json([], 200);
    }

    // for product stock
    public function searchForProductStock(Request $request)
    {
        $productId = $request->product_id;

        if($productId != null) {
            $model = Product::with('details')->find($productId);

            $formattedData = collect($model)->merge([
                'unit_price' => number_format(covert_to_defalut_currency($model->unit_price), 2),
                'discount_amount' => $model->discount_type == 'amount' 
                                     ? number_format(covert_to_defalut_currency($model->discount_amount), 2) 
                                     : $model->discount_amount
            ]);

            if($formattedData) {
                return response()->json($formattedData);
            } else {
                return response()->json();
            }
        } else {
            return response()->json();
        }
    }

    // For Brand Source Id
    public function getSourceOptions($source)
    {
        $data = $this->bannerRepository->getSourceOptions($source);

        return response()->json(['source' => $data]);
    }

}

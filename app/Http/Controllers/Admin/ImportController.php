<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\BrandType;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\CPU\Images;
use App\Models\City;
use App\Models\ProductStock;
use App\Models\SpecificationKey;
use App\Models\SpecificationKeyType;
use App\Models\SpecificationKeyTypeAttribute;
use App\Models\ProductSpecification;
use App\Models\StockPurchase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\BrandRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    private $brandRepository;
    private $productRepository;
    private $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->brandRepository = $brandRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function category()
    {   
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.category') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.import.category');
    }

    public function importCategories(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.category') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // if(is_array($rows) && count($rows) > 201) {
        //     return response()->json([
        //         'row' => count($rows),
        //         'status' => false,
        //         'message' => 'Maximum 200 column at a single time',
        //     ]);
        // }

        $importedCategories = [];
        $errors = [];

        $helperController = new HelperController($this->productRepository, $this->categoryRepository, $this->brandRepository);

        foreach ($rows as $key => $row) {
            if ($key == 0) {
                continue;
            }

            if($row[1] == '') {
                continue;
            }

            if(Category::where('name', $row[1])->first()) {
                continue;
            }
            
            $parentId = $row[0] ?? null;
            if ($row[0] !== 'parent') {
                $parentCategory = Category::where('name', $row[0])->first();
                $parentId = $parentCategory->id;
            } else {
                $parentId = null;
            }

            $slug = $row[2];
            $request->merge(['slug' => $slug]);
            $slugExists = $helperController->checkSlug($request);
            
            $data = json_decode($slugExists->getContent(), true);
            if ($data['exists'] == true) {
                $slug = $row[2] . '-'. rand(10000, 1000000);
            }

            $imagePath = null;
            $imageUrl = $row[11] ?? null;
            if ($imageUrl) {
                $imagePath = Images::uploadImageFromUrl($imageUrl, 'categories', $row[11]);
                if (!$imagePath) {
                    $errors[] = "Image upload failed for row $key.";
                    continue;
                }
            }

            Category::create([
                'parent_id'         => $parentId,
                'admin_id'          => Auth::guard('admin')->user()->id,
                'name'              => $row[1],
                'slug'              => $slug,
                'photo'             => $imagePath ?? null,
                'icon'              => "<i class=\"fi-rr-dashboard-monitor\"></i>",
                'description'       => $row[1],
                'header'            => $row[3] ?? $row[1],
                'short_description' => $row[4] ?? $row[1],
                'site_title'        => $row[5] ?? $row[1],
                'meta_title'        => $row[6] ?? $row[1],
                'meta_keyword'      => $row[7] ?? $row[1],
                'meta_description'  => $row[8] ?? $row[1],
                'meta_article_tag'  => null,
                'meta_script_tag'   => null,
                'status'            => $row[9] ?? 0,
                'is_featured'       => $row[10] ?? 0,
            ]);

            $importedCategories[] = $row[1];
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Categories Imported Successfully',
            'imported' => $importedCategories,
            'errors' => $errors,
        ]);
    }

    public function brand()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.brand') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.import.brand');
    }

    public function importBrands(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.brand') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        $errors = [];
        $importedBrands = [];

        // if(is_array($rows) && count($rows) > 201) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Maximum 200 column at a single time',
        //     ]);
        // }

        $helperController = new HelperController($this->productRepository, $this->categoryRepository, $this->brandRepository);

        foreach ($rows as $key => $row) {
            if ($key == 0) {
                continue;
            }

            if(Brand::where('name', $row[0])->first()) {
                continue;
            }

            if($row[0] == '') {
                continue;
            }

            // if(Brand::where('name', $row[0])->first()) {
            //     continue;
            // }
            
            $slug = $row[1];
            $request->merge(['slug' => $slug]);
            $slugExists = $helperController->checkSlug($request);
            
            $data = json_decode($slugExists->getContent(), true);
            
            if (isset($data['exists']) && $data['exists'] == false) {
                $slug = $row[1] . '-'. rand(10000, 1000000);
            }

            $imagePath = null;
            $imageUrl = $row[8] ?? null;
            if ($imageUrl) {
                $imagePath = Images::uploadImageFromUrl($imageUrl, 'brands', $row[8]);
                if (!$imagePath) {
                    $errors[] = "Image upload failed for row $row[0].";
                    continue;
                }
            }

            $brand = Brand::create([
                'name' => $row[0],
                'slug' => $slug,
                'logo' => $imagePath ?? null,
                'description' => $row[1],
                'meta_title' => $row[2] ?? $row[0],
                'meta_keyword' => $row[3] ?? $row[0],
                'meta_description' => $row[4] ?? $row[0],
                'meta_article_tag' => null,
                'meta_script_tag' => null,
                'status' => $row[5] ?? 0,
                'is_featured' => $row[6] ?? 0,
                'created_by' => Auth::guard('admin')->user()->id
            ]);

            if($brand) {
                $componentArray = explode(', ', $row[7]);
                if(is_array($componentArray) && count($componentArray) > 0) {
                    foreach($componentArray as $component) {
                        if($component != '') {
                            BrandType::create([
                                'brand_id' => $brand->id,
                                'name' => $component,
                                'status' => $brand->status,
                                'is_featured' => 0
                            ]);
                        }
                    }
                }
            }
            
            $importedBrands[] = $row[1];
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Brands Imported Successfully',
            'imported' => $importedBrands,
            'errors' => $errors,
        ]);
    }

    public function product()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.product') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.import.product');
    }

    public function importProducts(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.product') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $maxCell = $sheet->getHighestRowAndColumn();
        $rows = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

        $errors = [];

        $helperController = new HelperController($this->productRepository, $this->categoryRepository, $this->brandRepository);

        foreach ($rows as $key => $row) {
            // Ignore the header column
            if ($key == 0) {
                continue;
            }

            if($row[3] == '') {
                continue;
            }

            // check for categoryId
            $categoryId = null;
            if(!$category = Category::select('id', 'name')->where('name', $row[0])->first()) {
                continue;
            }
            $categoryId = $category->id;

            // get the brand id
            $brandId = null;
            if($brand = Brand::where('name', $row[1])->first()) {
                $brandId = $brand->id;
            }

            // get the brand type id
            $brandTypeId = null;
            if($brand && $brandType = $brand->types()->where('name', $row[2])->first()) {
                $brandTypeId = $brandType->id;
            }

            // Ignore if the slug is empty
            if($row[3] == '') {
                $errors[] = "Name for {$row[3]} can not be empty.";
                continue;
            }

            // Ignore if the Product name is already exist
            if(Product::where('name', $row[3])->first()) {
                
                $errors[] = "Product: {$row[3]} is already exist.";
                continue;
            }

            $slug = $row[4];
            $request->merge(['slug' => $slug]);
            $slugExists = $helperController->checkSlug($request);
            
            $data = json_decode($slugExists->getContent(), true);
            
            if (isset($data['exists']) && $data['exists'] == true) {
                $slug = $slug . '-'. rand(10000, 1000000);
            }

            $imagePath = null;
            $imageUrl = $row[19] ?? null;
            if ($imageUrl) {
                $imagePath = Images::uploadImageFromUrl($imageUrl, 'products', $row[2]);
                if (!$imagePath) {
                    $errors[] = "Image upload failed for row $row[3].";
                    continue;
                }
            }

            $stage = $row[27];
            if(strtolower($row[27]) == 'pre order') {
                $stage = 'pre-order';
            } elseif (strtolower($row[27]) == 'upcoming') {
                $stage = 'upcoming';
            } else {
                $stage = 'normal';
            }

            DB::beginTransaction();

            $discountStartDate = $row[24];
            $discountEndDate = $row[25];
            $dateStart = \DateTime::createFromFormat('d/m/Y', $discountStartDate);
            $dateEnd = \DateTime::createFromFormat('d/m/Y', $discountEndDate);

            if ($dateStart) {
                $dateStart = $dateStart->format('Y-m-d H:i:s');
            } else {
                $dateStart = null;
            }

            if ($dateEnd) {
                $dateEnd = $dateEnd->format('Y-m-d H:i:s');
            } else {
                $dateEnd = null;
            }

            $product = Product::create([
                'admin_id'      =>  Auth::guard('admin')->user()->id,
                'category_id'   =>  $categoryId,
                'brand_id'      =>  $brandId ?? null,
                'brand_type_id' =>  $brandTypeId ?? null,
                'product_type'  =>  $row[9] == 'Physical' ? 'physical' : 'digital',
                'name'          =>  $row[3],
                'slug'          =>  $slug,
                'thumb_image'   =>  $imagePath ?? null,
                'sku'           =>  $row[5],
                'status'        =>  $row[26] == 'Active' ? 1 : 0,
                'stage'         =>  $stage,
                'is_featured'   =>  $row[28] == 'Yes' ? 1 : 0,
                'is_discounted' =>  $row[21] == 'Yes' ? 1 : 0,
                'discount_type' =>  strtolower($row[22]) == 'flat' ? 'amount' : 'percentage',
                'discount'      =>  strtolower($row[22]) == 'flat'  ? covert_to_usd($row[23]) : $row[23],
                'discount_start_date'   =>  $dateStart,
                'discount_end_date'     =>  $dateEnd,
                'is_returnable'         =>  $row[32] == 'Yes' ? 1 : 0,
                'return_deadline'       =>  $row[33] ?? null,
                'stock_types'           =>  'globally'
            ]);

            if($product) {

                $details = ProductDetail::create([
                    'product_id'            => $product->id,
                    'video_provider'        => $row[12] ?? null,
                    'current_stock'         => 0,
                    'low_stock_quantity'    => $row[31] ?? 0,
                    'cash_on_delivery'      => strtolower($row[32]) == 'yes' ? 1 : 0,
                    'est_shipping_days'     => $row[33] ?? null,
                    'video_link'            => $row[13] ?? null,
                    'points'                => $row[10] ?? 0,
                    'shipping_cost'         => $row[18] ? covert_to_usd($row[18]) : 0,
                    'number_of_sale' => 0,
                    'average_rating' => 0,
                    'number_of_rating' => 0,
                    'average_purchase_price' => 0,
                    'site_title'            => $row[14] ? $row[14] : $row[3],
                    'meta_title'            => $row[15] ? $row[15] : $row[3],
                    'meta_keyword'          => $row[16] ? $row[16] : null,
                    'meta_description'      => $row[17] ? $row[17] : $row[3],
                ]);

                // Stock Purchase
                if($row[6] != '' && is_int((int) $row[6]) && $row[7] != '' && is_int((int) $row[7])) {

                    $stockPurchase = new StockPurchase;
                    $stockPurchase->product_id = $product->id;
                    $stockPurchase->admin_id = $product->admin_id;
                    $stockPurchase->currency_id = 1;
                    $stockPurchase->sku = $row[5];
                    $stockPurchase->quantity = (int) $row[6];
                    $stockPurchase->unit_price = covert_to_usd((int) $row[7]);
                    $stockPurchase->purchase_unit_price = covert_to_usd((int) $row[8]);
                    $stockPurchase->purchase_total_price = covert_to_usd((int) $row[6] * (int) $row[8]);
                    $stockPurchase->is_sellable = 1;
                    $stockPurchase->save();
                    if ($stockPurchase) {

                        // update product in_stock column
                        if ($stockPurchase->is_sellable == 1) {
                            $product->in_stock = 1;
                            $product->save();
                        }

                        // Add stock data into product_stock table by stock_types
                        $stock = new ProductStock;
                        $stock->product_id = $product->id;
                        $stock->stock_purchase_id  = $stockPurchase->id;
                        $stock->in_stock = 1;
                        $stock->number_of_sale = 0;
                        $stock->stock = (int) $row[6];
                        $stock->save();

                        // update current_stock data on product_details table
                        $details->current_stock = (int) $row[6];
                        $details->save();

                        $product->unit_price = covert_to_usd((int) $row[7]);
                        $product->save();
                    }
                }

                if($row[20]) {
                    $images = explode(',', $row[20]);
                    foreach($images as $image) {
                        if($image != '') {
                            $image = trim($image);
                            $imagePath = Images::uploadImageFromUrl($image, 'products', $row[3]);
                            if (!$imagePath) {
                                $errors[] = "Image upload failed for row $row[3].";
                                continue;
                            } else {
                                ProductImage::create([
                                    'product_id' => $product->id,
                                    'image'     => $imagePath,
                                    'status'    => 1
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
        }

        if(count($errors) > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Some Products are not imported',
                'errors' => $errors
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product Imported Successfully',
        ]);
    }

    public function stock()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.stock') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.import.stock');
    }

    public function importStock(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.stock') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $maxCell = $sheet->getHighestRowAndColumn();
        $rows = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

        $errors = [];

        $helperController = new HelperController($this->productRepository, $this->categoryRepository, $this->brandRepository);

        foreach ($rows as $key => $row) {
            // Ignore the header column
            if ($key == 0) {
                continue;
            }

            if($row[0] == '') {
                continue;
            }

            // check for categoryId
            if(!$product = Product::select('id', 'name')->where('name', $row[0])->first()) {
                continue;
            }

            // get the city
            $cityId = null;
            if(!$city = City::where('name', $row[1])->first()) {
                continue;
            }
            $cityId = $city->id;

            // Ignore if the slug is empty
            if($row[3] == '') {
                $errors[] = "Quantity for {$row[0]} can not be empty.";
                continue;
            }

            DB::beginTransaction();

            $discountStartDate = $row[14];
            $discountEndDate = $row[15];
            $dateStart = \DateTime::createFromFormat('d/m/Y', $discountStartDate);
            $dateEnd = \DateTime::createFromFormat('d/m/Y', $discountEndDate);

            if ($dateStart) {
                $dateStart = $dateStart->format('Y-m-d H:i:s');
            } else {
                $dateStart = null;
            }

            if ($dateEnd) {
                $dateEnd = $dateEnd->format('Y-m-d H:i:s');
            } else {
                $dateEnd = null;
            }

            $stockPurchase = new StockPurchase;
            $stockPurchase->product_id = $product->id;
            $stockPurchase->admin_id = Auth::guard('admin')->user()->id;
            $stockPurchase->currency_id = 1;
            $stockPurchase->sku = $row[2];
            $stockPurchase->quantity = (int) $row[3];
            $stockPurchase->unit_price = covert_to_usd((int) $row[4]);
            $stockPurchase->purchase_unit_price = covert_to_usd((int) $row[6]);
            $stockPurchase->purchase_total_price = covert_to_usd((int) $row[3] * (int) $row[6]);
            $stockPurchase->is_sellable = strtolower($row[8]) == 'yes' ? 1 : 0;
            $stockPurchase->save();
            if ($stockPurchase) {

                // update product in_stock column
                if ($stockPurchase->is_sellable == 1) {
                    $product->in_stock = 1;
                    $product->save();
                }

                // Add stock data into product_stock table by stock_types
                $stock = new ProductStock;
                $stock->product_id = $product->id;
                $stock->stock_purchase_id  = $stockPurchase->id;
                $stock->city_id  = $cityId;
                $stock->in_stock = 1;
                $stock->number_of_sale = $row[10] ?? 0;
                $stock->stock = (int) $row[3];
                $stock->save();

                // update current_stock data on product_details table
                $product->details->current_stock += (int) $row[6];
                $product->details->save();

                $product->unit_price = covert_to_usd((int) $row[4]);
                $product->is_discounted = strtolower($row[11]) == 'yes' ? 1 : 0;
                $product->discount_type = strtolower($row[12]) == 'flat' ? 'amount' : 'percentage';
                $product->discount      = strtolower($row[12]) == 'flat'  ? covert_to_usd($row[4]) : $row[4];
                $product->discount_start_date = $dateStart;
                $product->discount_end_date = $dateEnd;
                $product->stock_types = 'city_wise';
                $product->save();
            }
        }

        if(count($errors) > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Some Products are not imported',
                'errors' => $errors
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Stock Imported Successfully',
        ]);
    }

    public function specification()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.specification') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.import.specification');
    }

    public function importSpecification(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('bulk-import.specification') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        // Increase memory limit for large files
        ini_set('memory_limit', '512M'); // Adjust according to your needs

        // Allow bigger post size and upload size
        ini_set('post_max_size', '100M'); // Adjust according to file size
        ini_set('upload_max_filesize', '100M');

        // Ensure error reporting for debugging
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        try {
            $request->validate([
                'product_id' => 'required',
                'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            ]);
    
            $productId = $request->product_id;
            $product = Product::find($productId);
            if(!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product Not Found'
                ]);
            }
    
            if(!$product->category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product Category Not Found'
                ]);
            }
    
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $maxCell = $sheet->getHighestRowAndColumn();
            $rows = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
    
            $errors = [];
    
            $helperController = new HelperController($this->productRepository, $this->categoryRepository, $this->brandRepository);
    
            $result = [];
            $lastValue = null;
            $lastValueOfIndexOne = null;
    
            foreach ($rows as $item) {
                if ($item[2] === null) {
                    continue;
                }
            
                if ($item[0] === null) {
                    $item[0] = $lastValue;
                } else {
                    $lastValue = $item[0];
                }
    
                if ($item[1] === null) {
                    $item[1] = $lastValueOfIndexOne;
                } else {
                    $lastValueOfIndexOne = $item[1];
                }
            
                $result[] = $item;
            }
            
            foreach ($result as $key => $row) {

                if($row[0] == null) {
                    continue;
                }
                
                // Get The Specification Key
                $specificationKey = SpecificationKey::where(function ($query) use ($product, $row) {
                    $query->where('is_public', 1)
                          ->where('name', $row[0]);
                })->orWhere(function ($query) use ($product, $row) {
                    $query->where('is_public', 0)
                          ->where('name', $row[0])
                          ->where('category_id', $product->category_id);
                })->first();
                if(!$specificationKey) {
                    $specificationKey = SpecificationKey::create([
                        'admin_id'      => Auth::guard('admin')->user()->id,
                        'category_id'   => $product->category_id,
                        'name'          => $row[0],
                        'status'        => 1,
                        'is_public'     => 0,
                        'position'      => 1,
                    ]);
                }

                // Get the Specification Key Type
                $specificationKeyType = SpecificationKeyType::where('specification_key_id', $specificationKey->id)->where('name', $row[1])->first();
                if(!$specificationKeyType) {
                    $specificationKeyType = SpecificationKeyType::create([
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'specification_key_id' => $specificationKey->id,
                        'name' => $row[1],
                        'status' => 1,
                        'position' => 1,
                        'show_on_filter' => 0,
                        'filter_name' => null
                    ]);
                }

                // Get the Specification Key Type Attribute
                $specificationKeyTypeAttribute = SpecificationKeyTypeAttribute::where('key_type_id', $specificationKeyType->id)->where('name', $row[2])->first();
                if(!$specificationKeyTypeAttribute) {
                    $specificationKeyTypeAttribute = SpecificationKeyTypeAttribute::create([
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'key_type_id' => $specificationKeyType->id,
                        'name' => $row[2],
                        'status' => 1,
                    ]);
                }
                
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'key_id' => $specificationKey->id,
                    'type_id' => $specificationKeyType->id,
                    'attribute_id' => $specificationKeyTypeAttribute->id,
                    'key_features' => isset($row[3]) && strtolower($row[3]) == 'featured' ? 1 : 0
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Specification Imported Successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Error: " . $e->getMessage()
            ]);
        }
    }
}

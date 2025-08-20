<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\ProductSpecification;
use App\Models\SpecificationKey;
use App\Models\SpecificationKeyType;
use App\Models\SpecificationKeyTypeAttribute;
use App\Repositories\Interface\BrandTypeRepositoryInterface;
use App\Repositories\Interface\TaxRepositoryInterface;
use App\Repositories\Interface\CityRepositoryInterface;
use App\Repositories\Interface\ZoneRepositoryInterface;
use App\Repositories\Interface\CountryRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use App\Repositories\Interface\ProductSpecificationRepositoryInterface;
use App\Repositories\Interface\LaptopBudgetRepositoryInterface;
use App\Repositories\Interface\LaptopFinderPurposeRepositoryInterface;
use App\Repositories\Interface\LaptopFinderScreenRepositoryInterface;
use App\Repositories\Interface\LaptopFinderPortabilityRepositoryInterface;
use App\Repositories\Interface\LaptopFinderFeaturesRepositoryInterface;

class ProductController extends Controller
{
    protected $categoryRepository;
    protected $productRepository;
    protected $specificationRepository;
    private $taxRepository;
    private $currencyRepository;
    private $zoneRepository;
    private $countryRepository;
    private $cityRepository;
    private $brandTypeRepository;
    private $laptopBudgetRepository;
    private $LaptopFinderPurposeRepository;
    private $laptopFinderScreenRepository;
    private $laptopFinderPortabilityRepository;
    private $laptopFinderFeaturesRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
        TaxRepositoryInterface $taxRepository,
        ProductSpecificationRepositoryInterface $specificationRepository,
        CurrencyRepositoryInterface $currencyRepository,
        ZoneRepositoryInterface $zoneRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository,
        BrandTypeRepositoryInterface $brandTypeRepository,
        LaptopBudgetRepositoryInterface $laptopBudgetRepository,
        LaptopFinderPurposeRepositoryInterface $LaptopFinderPurposeRepository,
        LaptopFinderScreenRepositoryInterface $laptopFinderScreenRepository,
        LaptopFinderPortabilityRepositoryInterface $laptopFinderPortabilityRepository,
        LaptopFinderFeaturesRepositoryInterface $laptopFinderFeaturesRepository,
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->specificationRepository = $specificationRepository;
        $this->taxRepository = $taxRepository;
        $this->currencyRepository = $currencyRepository;
        $this->zoneRepository = $zoneRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->brandTypeRepository = $brandTypeRepository;
        $this->laptopBudgetRepository = $laptopBudgetRepository;
        $this->LaptopFinderPurposeRepository = $LaptopFinderPurposeRepository;
        $this->laptopFinderScreenRepository = $laptopFinderScreenRepository;
        $this->laptopFinderPortabilityRepository = $laptopFinderPortabilityRepository;
        $this->laptopFinderFeaturesRepository = $laptopFinderFeaturesRepository;
    }

    public function storeSpec(Request $request)
    {
        $productId = $request->product_id;
        $keyIdArray = $request->key;
        $typeIdArray = $request->type;
        $attrIdArray = $request->attribute;

        $product = Product::find($productId);
        if(!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        $this->removeSpec($productId);

        if(is_array($keyIdArray) && count($keyIdArray) > 0) {
            foreach($keyIdArray as $keyId) {

                $key = SpecificationKey::find($keyId);
                if($key && isset($typeIdArray[$key->id])) {
                    $type = SpecificationKeyType::find($typeIdArray[$key->id]);
                    if($type && is_array($attrIdArray) && count($attrIdArray) > 0 ) {
                        foreach($attrIdArray as $attrId) {
                            $attribute = SpecificationKeyTypeAttribute::where('id', $attrId)->where('key_type_id', $type->id)->first();
                            if($attribute) {
                                echo 'Key: '. $key->id . ' Type: '. $type->id. ' Attribute: '. $attribute->id . '<br>';
                            }

                        }
                    }
                }
                
            }
        } 

        dd($request->all());
    }

    public function removeSpec($productId)
    {
        return ProductSpecification::where('product_id', $productId)->delete();
    }

    public function specificationControl($productId)
    {
        $product = Product::findOrFail($productId);

        $categoryIdArray = $product->category->getAllCategoryIds();
        $keys = $this->specificationRepository->allKeysIncludingParent($categoryIdArray);

        // list($specifications, $models, $product_id_array) = $this->productRepository->compare($product->id);
        return view('backend.product.specification', compact('product', 'keys'));
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            if($request->category_id != null || $request->brand_id != null) {
                return $this->productRepository->dataTableWithAjaxSearch($request->category_id, $request->brand_id);
            } else {
                return $this->productRepository->dataTable();
            }
        }

        $category_id = $request->category_id;
        $brand_id = $request->brand_id;

        return view('backend.product.index', compact('category_id', 'brand_id'));
    }

    public function create(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if (isset($request->parent_id)) {
            return response()->json(['subs' => $this->categoryRepository->categoriesDropDown($request)]);
        }

        $taxes = $this->taxRepository->getAllActiveTaxes();
        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        $zones = $this->zoneRepository->getAllActiveZones();
        $countries = $this->countryRepository->getAllActiveCountry();
        $cities = $this->cityRepository->getAllActiveCity();
        $laptopBudgets = $this->laptopBudgetRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopPurposes = $this->LaptopFinderPurposeRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopScreenSizes = $this->laptopFinderScreenRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopPortables = $this->laptopFinderPortabilityRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopFeatures = $this->laptopFinderFeaturesRepository->all()->select('id', 'name', 'status')->where('status', 1);

        return view('backend.product.create', compact('taxes', 'laptopFeatures', 'laptopPortables', 'laptopScreenSizes', 'laptopPurposes', 'laptopBudgets', 'currencies', 'zones', 'countries', 'cities'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.store') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productRepository->storeProduct($request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->productRepository->deleteProduct($id);

        return response()->json([
            'status' => true,
            'load' => true,
            'message' => "Product deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productRepository->updateStatus($request, $id);
    }

    public function updateFeatured(Request $request, $id)
    {
        return $this->productRepository->updateFeatured($request, $id);
    }
    public function specification(Request $request)
    {
        if (isset($request->category_id)) {
            $ids = $this->categoryRepository->getParentCategoryIds($request->category_id);
            return response()->json(['keys' => $this->specificationRepository->allKeysIncludingParent($ids)]);
        } elseif ($request->key_id) {
            return response()->json(['types' => $this->specificationRepository->types($request->key_id)]);
        } elseif ($request->type_id) {
            return response()->json(['attributes' => $this->specificationRepository->attributes($request->type_id)]);
        }
        return false;
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->productRepository->getProductById($id);
        
        $brandTypes = null;
        if($model->brand_id) {
            $brandTypes = $this->brandTypeRepository->getAllBrandTypesByBrandId($model->brand_id);
        }
        $taxes = $this->taxRepository->getAllActiveTaxes();
        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        $zones = $this->zoneRepository->getAllActiveZones();
        $countries = $this->countryRepository->getAllActiveCountry();
        $cities = $this->cityRepository->getAllActiveCity();
        

        $laptopBudgets = $this->laptopBudgetRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopPurposes = $this->LaptopFinderPurposeRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopScreenSizes = $this->laptopFinderScreenRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopPortables = $this->laptopFinderPortabilityRepository->all()->select('id', 'name', 'status')->where('status', 1);
        $laptopFeatures = $this->laptopFinderFeaturesRepository->all()->select('id', 'name', 'status')->where('status', 1);

        return view('backend.product.edit', compact('laptopFeatures', 'laptopBudgets', 'laptopScreenSizes', 'laptopPortables', 'laptopPurposes', 'model', 'brandTypes', 'taxes', 'currencies', 'zones', 'countries', 'cities'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productRepository->updateProduct($request, $id);
    }

    public function stock($id)
    {
        $models = $this->productRepository->getProductStockPurchaseDetails($id);

        return view('backend.product.stock', compact('models'));
    }

    public function duplicate(Request $request, $id) 
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.duplicate') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productRepository->duplicateProduct($request, $id);
    }

    public function specificationproducts(Request $request){

        if ($request->ajax()) {
            return $this->productRepository->specificationproductsDatatable();
        }

        return view('backend.product.specification.index');
    }


    public function specificationproductModal($id)
    {
        return $this->productRepository->specificationproductModal($id);
    }
    
    public function specificationProductPage($id)
    {
        return $this->productRepository->specificationProductPage($id);
    }

    public function keyfeature($id)
    {
        return $this->productRepository->keyfeature($id);
    }
    
    public function delete($id) 
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('product.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productRepository->delete($id);
    }

    public function specificationsAdd(Request $request,$id)
    {
        return $this->productRepository->specificationsAdd($request,$id);
    }
}

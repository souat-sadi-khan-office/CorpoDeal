<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\CartRepositoryInterface;
use Illuminate\Support\Facades\Session;

class PcBuilderController extends Controller
{
    private $product;
    private $cartRepository;
    
    public function __construct(
        ProductRepositoryInterface $product,
        CartRepositoryInterface $cartRepository,
    ) {
        $this->product = $product;
        $this->cartRepository = $cartRepository;
    }

    
    public function index()
    {
        $total_cost = 0;
        $total_tdp = 0;
        $item_counter = 0;
        
        if(Session::has('pc_builder_item_cpu')) {
            $total_cost += (Session::get('pc_builder_item_cpu')['row_price'] ?? 0);
            $total_tdp += Session::get('pc_builder_item_cpu')['condition']['default_tdp'];
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_cooler')) {
            $total_cost += (Session::get('pc_builder_item_cooler')['row_price'] ?? 0);
            $total_tdp += Session::get('pc_builder_item_cooler')['condition']['cc_default_tdp'];
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_motherboard')) {
            $total_cost += (Session::get('pc_builder_item_motherboard')['row_price'] ?? 0);
            $total_tdp += Session::get('pc_builder_item_motherboard')['condition']['default_tdp'];
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_gc')) {
            $total_cost += (Session::get('pc_builder_item_gc')['row_price'] ?? 0);
            $total_tdp += Session::get('pc_builder_item_gc')['condition']['gc_default_tdp'];
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_casing')) {
            $total_cost += (Session::get('pc_builder_item_casing')['row_price'] ?? 0);
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_psu')) {
            $total_cost += (Session::get('pc_builder_item_psu')['row_price'] ?? 0);
            $total_tdp += 0;
            $item_counter ++;
        }
        
        if(Session::has('pc_builder_item_monitor')) {
            $total_cost += (Session::get('pc_builder_item_monitor')['row_price'] ?? 0);
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_keyboard')) {
            $total_cost += (Session::get('pc_builder_item_keyboard')['row_price'] ?? 0);
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_mouse')) {
            $total_cost += (Session::get('pc_builder_item_mouse')['row_price'] ?? 0);
            $item_counter ++;
        }
        
        if(Session::has('pc_builder_item_anti_virus')) {
            $total_cost += (Session::get('pc_builder_item_anti_virus')['row_price'] ?? 0);
            $item_counter ++;
        }
        
        if(Session::has('pc_builder_item_headphone')) {
            $total_cost += (Session::get('pc_builder_item_headphone')['row_price'] ?? 0);
            $item_counter ++;
        }
        
        if(Session::has('pc_builder_item_ups')) {
            $total_cost += (Session::get('pc_builder_item_ups')['row_price'] ?? 0);
            $item_counter ++;
        }

        if(Session::has('pc_builder_item_ram')) {
            $ramArray = Session::get('pc_builder_item_ram');
            foreach($ramArray as $ram) {
                $total_cost += ($ram['row_price'] ?? 0);
                $total_tdp += $ram['condition']['ram_default_tdp'] ?? 0;
                $item_counter ++;
            }
        }

        if(Session::has('pc_builder_item_ssd')) {
            $ssdArray = Session::get('pc_builder_item_ssd');
            foreach($ssdArray as $ssd) {
                $total_cost += ($ssd['row_price'] ?? 0);
                $total_tdp += $ssd['condition']['storage_default_tdp'] ?? 0;
                $item_counter ++;
            }
        }

        if(Session::has('pc_builder_item_casing_fan')) {
            $fanArray = Session::get('pc_builder_item_casing_fan');
            foreach($fanArray as $fan) {
                $total_cost += ($fan['row_price'] ?? 0);
                $item_counter ++;
            }
        }

        return view('frontend.pc-builder', compact('total_cost', 'item_counter', 'total_tdp'));
    }

    public function pickItem(Request $request, $item, $productId)
    {
        $productId = decode($productId);
        
        $product = $this->product->getProductById($productId);
        if(!$product) {
            abort(404);
        }
    
        if($product) {
            $model = [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'image' => asset($product->thumb_image),
                'row_price' => $product->unit_price,
                'price' => format_price(convert_price($product->unit_price))
            ];

            if($item == 'ups') {
                Session::put('pc_builder_item_ups', $model);
            } elseif ($item == 'headphone') {
                Session::put('pc_builder_item_headphone', $model);
            } elseif ($item == 'psu') {
                Session::put('pc_builder_item_psu', $model);
            } elseif ($item == 'anti-virus') {
                Session::put('pc_builder_item_anti_virus', $model);
            } elseif ($item == 'mouse') {
                Session::put('pc_builder_item_mouse', $model);
            } elseif ($item == 'keyboard') {
                Session::put('pc_builder_item_keyboard', $model);
            } elseif ($item == 'casing-fan') {

                $fanArray = [];
                if(is_array(Session::get('pc_builder_item_casing_fan'))) {
                    $fanArray = Session::get('pc_builder_item_casing_fan');
                }

                array_push($fanArray, $model);

                Session::put('pc_builder_item_casing_fan', $fanArray);
            } elseif ($item == 'monitor') {
                Session::put('pc_builder_item_monitor', $model);
            } elseif ($item == 'cpu') {
                $model['condition'] = [
                    'cpu_brand' => $product->pc_builder ? $product->pc_builder->cpu_brand : null,
                    'cpu_generation' => $product->pc_builder ? $product->pc_builder->cpu_generation : null,
                    'socket_type' => $product->pc_builder ? $product->pc_builder->socket_type : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                ];

                Session::put('pc_builder_item_cpu', $model);
            } elseif ($item == 'motherboard') {
                $model['condition'] = [
                    'mb_supported_cpu_brand' => $product->pc_builder ? $product->pc_builder->mb_supported_cpu_brand : null,
                    'mb_cpu_generation' => $product->pc_builder ? $product->pc_builder->mb_cpu_generation : null,
                    'mb_supported_socket' => $product->pc_builder ? $product->pc_builder->mb_supported_socket : null,
                    'mb_form_factor' => $product->pc_builder ? $product->pc_builder->mb_form_factor : null,
                    'mb_supported_memory_type' => $product->pc_builder ? $product->pc_builder->mb_supported_memory_type : null,
                    'mb_default_tdp' => $product->pc_builder ? $product->pc_builder->mb_default_tdp : null,
                    'mb_number_of_ram' => $product->pc_builder ? $product->pc_builder->mb_number_of_ram : null,
                    'mb_xmp_support' => $product->pc_builder ? $product->pc_builder->mb_xmp_support : null,
                    'mb_m2_storage_support' => $product->pc_builder ? $product->pc_builder->mb_m2_storage_support : null,
                    'mb_number_of_m2_support' => $product->pc_builder ? $product->pc_builder->mb_number_of_m2_support : null,
                    'mb_sata_storage_support' => $product->pc_builder ? $product->pc_builder->mb_sata_storage_support : null,
                    'mb_lic_support' => $product->pc_builder ? $product->pc_builder->mb_lic_support : null,
                    'mb_pcie_slot' => $product->pc_builder ? $product->pc_builder->mb_pcie_slot : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                    'default_tdp' => $product->pc_builder ? $product->pc_builder->default_tdp : null,
                ];

                Session::put('pc_builder_item_motherboard', $model);
            } elseif ($item == 'ram') {
                $model['condition'] = [
                    'ram_memory_type' => $product->pc_builder ? $product->pc_builder->ram_memory_type : null,
                    'ram_speed' => $product->pc_builder ? $product->pc_builder->ram_speed : null,
                    'ram_xmp_support' => $product->pc_builder ? $product->pc_builder->ram_xmp_support : null,
                    'ram_default_tdp' => $product->pc_builder ? $product->pc_builder->ram_default_tdp : null,
                ];

                $ramArray = [];
                if(is_array(Session::get('pc_builder_item_ram'))) {
                    $ramArray = Session::get('pc_builder_item_ram');
                }

                array_push($ramArray, $model);
                Session::put('pc_builder_item_ram', $ramArray);
            } elseif ($item == 'ssd') {
                $model['condition'] = [
                    'storage_type' => $product->storage_type ? $product->pc_builder->storage_type : null,
                    'storage_m2_support' => $product->pc_builder ? $product->pc_builder->storage_m2_support : null,
                    'storage_sata_support' => $product->pc_builder ? $product->pc_builder->storage_sata_support : null,
                    'storage_default_tdp' => $product->pc_builder ? $product->pc_builder->storage_default_tdp : null,
                ];

                $ssdArray = [];
                if(is_array(Session::get('pc_builder_item_ssd'))) {
                    $ssdArray = Session::get('pc_builder_item_ssd');
                }

                array_push($ssdArray, $model);
                Session::put('pc_builder_item_ssd', $ssdArray);
            } elseif ($item == 'gc') {
                $model['condition'] = [
                    'gc_supported_pcie_slot' => $product->pc_builder ? $product->pc_builder->gc_supported_pcie_slot : null,
                    'gc_supported_form_factor' => $product->pc_builder ? $product->pc_builder->gc_supported_form_factor : null,
                    'gc_default_tdp' => $product->pc_builder ? $product->pc_builder->gc_default_tdp : null,
                ];

                Session::put('pc_builder_item_gc', $model);
            } elseif ($item == 'casing') {
                $model['condition'] = [
                    'casing_form_factor' => $product->pc_builder ? $product->pc_builder->casing_form_factor : null,
                    'casing_psu_installed' => $product->pc_builder ? $product->pc_builder->casing_psu_installed : null,
                    'casing_fan_installed' => $product->pc_builder ? $product->pc_builder->casing_fan_installed : null,
                    'casing_number_of_fan_front' => $product->pc_builder ? $product->pc_builder->casing_number_of_fan_front : null,
                    'casing_number_of_fan_top' => $product->pc_builder ? $product->pc_builder->casing_number_of_fan_top : null,
                    'casing_number_of_fan_back' => $product->pc_builder ? $product->pc_builder->casing_number_of_fan_back : null,
                ];

                Session::put('pc_builder_item_casing', $model);
            } elseif ($item == 'cooler') {
                $model['condition'] = [
                    'cc_default_tdp' => $product->pc_builder ? $product->pc_builder->cc_default_tdp : null,
                ];

                Session::put('pc_builder_item_cooler', $model);
            }
        }

        return redirect()->route('pc-builder')->with('success' , 'Item added to PC Builder successfully');
    }

    public function choose(Request $request, $item)
    {
        switch($item) {
            case 'casing':

                if(!Session::has('pc_builder_item_motherboard')) {
                    return redirect()->route('pc-builder')->with('warning', 'Select motherboard first.');
                }

                $request->merge([
                    'casing_form_factor' => Session::get('pc_builder_item_motherboard')['condition']['mb_form_factor'],
                ]);

                $request->merge(['pc_builder' => 'core', 'comp_type' => 'casing']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'Casing')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'cooler':
                $request->merge(['pc_builder' => 'core', 'comp_type' => 'cpu_cooler']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'Casing')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'gc':
                $request->merge([
                    'mb_form_factor' => Session::get('pc_builder_item_motherboard')['condition']['mb_form_factor'],
                    'gc_supported_pcie_slot' => Session::get('pc_builder_item_motherboard')['condition']['mb_pcie_slot'],
                ]);

                $request->merge(['pc_builder' => 'core', 'comp_type' => 'graphics-card']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'Graphics card')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'cpu':
                $request->merge(['pc_builder' => 'core', 'comp_type' => 'processor']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'processor')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'psu':
                $request->merge(['pc_builder' => 'core', 'comp_type' => 'psu']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'Power Supply Unit')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'ssd':

                $request->merge(['pc_builder' => 'core', 'comp_type' => 'storage']);

                // if cpu is not set
                if(Session::get('pc_builder_item_motherboard') == '') {
                    return redirect()->route('pc-builder')->with('warning' , 'Select Motherboard First.');
                }

                $request->merge([
                    'storage_type' => 'ssd',
                    'storage_m2_support' => Session::get('pc_builder_item_motherboard')['condition']['mb_m2_storage_support'],
                    'mb_number_of_m2_support' => Session::get('pc_builder_item_motherboard')['condition']['mb_number_of_m2_support'],
                ]);

                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];
                $category = Category::where('name', 'Ssd')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'ram':

                $request->merge(['pc_builder' => 'core', 'comp_type' => 'ram']);

                // if cpu is not set
                if(Session::get('pc_builder_item_motherboard') == '') {
                    return redirect()->route('pc-builder')->with('warning' , 'Select Motherboard First.');
                }

                $request->merge([
                    'ram_memory_type' => Session::get('pc_builder_item_motherboard')['condition']['mb_supported_memory_type'],
                    'ram_xmp_support' => Session::get('pc_builder_item_motherboard')['condition']['mb_xmp_support'],
                ]);

                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];
                $category = Category::where('name', 'Ram')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'motherboard':

                $request->merge(['pc_builder' => 'core', 'comp_type' => 'motherboard']);
                

                // if cpu is not set
                if(Session::get('pc_builder_item_cpu') == '') {
                    return redirect()->route('pc-builder')->with('warning' , 'Select Processor First.');
                }

                $request->merge([
                    'cpu_brand' => Session::get('pc_builder_item_cpu')['condition']['cpu_brand'],
                    // 'cpu_generation' => Session::get('pc_builder_item_cpu')['condition']['cpu_generation'],
                    'socket_type' => Session::get('pc_builder_item_cpu')['condition']['socket_type'],
                ]);

                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];
                $category = Category::where('name', 'mother board')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'monitor':

                $request->merge(['pc_builder' => 'peri', 'comp_type' => 'monitor']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'monitor')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'casing-fan':

                // check for number of fan 
                if(Session::get('pc_builder_item_casing') == '') {
                    return redirect()->route('pc-builder')->with('warning' , 'Select Casing First.');
                }
                // $casingInformation = Session::get('pc_builder_item_casing');


                $request->merge(['pc_builder' => 'peri', 'comp_type' => 'casing_fan']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'Casing Fan')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'keyboard':
                $request->merge(['pc_builder' => 'peri', 'comp_type' => 'keyboard']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'keyboard')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'mouse':
                $request->merge(['pc_builder' => 'peri', 'comp_type' => 'mouse']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'mouse')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'anti-virus':
                $request->merge(['pc_builder' => 'peri', 'comp_type' => 'anti-virus']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'Software')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'headphone':
                $request->merge(['pc_builder' => 'peri', 'comp_type' => 'headphone']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'head phone')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'ups':
                $request->merge(['pc_builder' => 'peri', 'comp_type' => 'ups']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'ups')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            case 'cpu':
                $request->merge(['pc_builder' => 'core', 'comp_type' => 'processor']);
                $products = $this->product->pc_builder_items($request);
                $categoryIdArray = [];

                $category = Category::where('name', 'processor')->first();
                if($category) {
                    $categoryIdArray = $category->getAllCategoryIds();
                }

                return view('frontend.pc-builder-items', compact('products', 'categoryIdArray', 'item'));
            break;
            default:

                abort(404);
            break;
        }

        abort(404);
    }

    public function remove($item)
    {
        switch($item) {
            case 'ssd':
                if(request()->get('key') != '') {
                    $key = request()->get('key');
                    if(array_key_exists($key, Session::get('pc_builder_item_ssd'))) {
                        $ramArray = Session::get('pc_builder_item_ssd');
                        unset($ramArray[$key]);
                        Session::put('pc_builder_item_ssd', $ramArray);
                    }
                } else {
                    Session::forget('pc_builder_item_ssd');
                }
            break;
            case 'ram':
                if(request()->get('key') != '') {
                    $key = request()->get('key');
                    if(array_key_exists($key, Session::get('pc_builder_item_ram'))) {
                        $ramArray = Session::get('pc_builder_item_ram');
                        unset($ramArray[$key]);
                        Session::put('pc_builder_item_ram', $ramArray);
                    }
                } else {
                    Session::forget('pc_builder_item_ram');
                }
            break;
            case 'casing-fan':
                if(request()->get('key') != '') {
                    $key = request()->get('key');
                    if(array_key_exists($key, Session::get('pc_builder_item_casing_fan'))) {
                        $fanArray = Session::get('pc_builder_item_casing_fan');
                        unset($fanArray[$key]);
                        Session::put('pc_builder_item_casing_fan', $fanArray);
                    }
                } else {
                    Session::forget('pc_builder_item_casing_fan');
                }
            break;
            case 'motherboard':
                Session::forget('pc_builder_item_motherboard');
            break;
            case 'cpu':
                Session::forget('pc_builder_item_cpu');
            break;
            case 'cooler':
                Session::forget('pc_builder_item_cooler');
            break;
            case 'casing':
                Session::forget('pc_builder_item_casing');
            break;
            case 'monitor':
                Session::forget('pc_builder_item_monitor');
            break;
            case 'keyboard':
                Session::forget('pc_builder_item_keyboard');
            break;
            case 'mouse':
                Session::forget('pc_builder_item_mouse');
            break;
            case 'headphone':
                Session::forget('pc_builder_item_headphone');
            break;
            case 'ups':
                Session::forget('pc_builder_item_ups');
            break;
            case 'anti-virus':
                Session::forget('pc_builder_item_anti_virus');
            break;
            case 'gc':
                Session::forget('pc_builder_item_gc');
            break;
            case 'psu':
                Session::forget('pc_builder_item_psu');
            break;
            default:
                abort(404);
            break;
        }

        return redirect()->route('pc-builder')->with('success' , 'Item removed from PC Builder successfully');
    }

    public function addToCart(Request $request)
    {
        $allCounter = 0;
        // Processor
        if(Session::has('pc_builder_item_cpu')) {
            $cpu = Session::get('pc_builder_item_cpu');

            $allCounter ++;
            
            $request->merge(['slug' => $cpu['slug']]);
            $this->cartRepository->addToCart($request);
        }

        // CPU Cooler
        if(Session::has('pc_builder_item_cooler')) {
            $cpu_cooler = Session::get('pc_builder_item_cooler');
            $allCounter ++;
            
            $request->merge(['slug' => $cpu_cooler['slug']]);
            $this->cartRepository->addToCart($request);
        }
        
        // Motherboard
        if(Session::has('pc_builder_item_motherboard')) {
            $motherboard = Session::get('pc_builder_item_motherboard');
            $allCounter ++;
            
            $request->merge(['slug' => $motherboard['slug']]);
            $this->cartRepository->addToCart($request);
        }

        // Ram
        if(Session::has('pc_builder_item_ram')) {
            $ramArray = Session::get('pc_builder_item_ram');
            
            if(is_array($ramArray) && count($ramArray) > 0) {
                foreach($ramArray as $ram) {
                    $allCounter ++;
                    $request->merge(['slug' => $ram['slug']]);
                    $this->cartRepository->addToCart($request);
                }
            }
        }

        // storage
        if(Session::has('pc_builder_item_ssd')) {
            $ssdArray = Session::get('pc_builder_item_ssd');
            
            if(is_array($ssdArray) && count($ssdArray) > 0) {
                foreach($ssdArray as $ssd) {
                    $allCounter ++;
                    $request->merge(['slug' => $ssd['slug']]);
                    $this->cartRepository->addToCart($request);
                }
            }
           
        }

        // Graphics Card
        if(Session::has('pc_builder_item_gc')) {
            $allCounter ++;
            $gc = Session::get('pc_builder_item_gc');
            
            $request->merge(['slug' => $gc['slug']]);
            $this->cartRepository->addToCart($request);
        }

        // Power Supply
        if(Session::has('pc_builder_item_psu')) {
            $allCounter ++;
            $psu = Session::get('pc_builder_item_psu');
            
            $request->merge(['slug' => $psu['slug']]);
            $this->cartRepository->addToCart($request);
        }

        // casing
        if(Session::has('pc_builder_item_casing')) {
            $allCounter ++;
            $casing = Session::get('pc_builder_item_casing');
            
            $request->merge(['slug' => $casing['slug']]);
            $this->cartRepository->addToCart($request);
        }
        
        // monitor
        if(Session::has('pc_builder_item_monitor')) {
            $allCounter ++;
            $monitor = Session::get('pc_builder_item_monitor');
            
            $request->merge(['slug' => $monitor['slug']]);
            $this->cartRepository->addToCart($request);
        }

        // Casing Fan
        if(Session::has('pc_builder_item_casing_fan')) {
            $fanArray = Session::get('pc_builder_item_casing_fan');
            
            if(is_array($fanArray) && count($fanArray) > 0) {
                foreach($fanArray as $fan) {
                    $allCounter ++;
                    $request->merge(['slug' => $fan['slug']]);
                    $this->cartRepository->addToCart($request);
                }
            }
           
        }

        // keyboard
        if(Session::has('pc_builder_item_keyboard')) {
            $allCounter ++;
            $keyboard = Session::get('pc_builder_item_keyboard');
            
            $request->merge(['slug' => $keyboard['slug']]);
            $this->cartRepository->addToCart($request);
        }

        // mouse
        if(Session::has('pc_builder_item_mouse')) {
            $allCounter ++;
            $mouse = Session::get('pc_builder_item_mouse');
            
            $request->merge(['slug' => $mouse['slug']]);
            $this->cartRepository->addToCart($request);
        }
        
        // anti-virus
        if(Session::has('pc_builder_item_anti_virus')) {
            $allCounter ++;
            $av = Session::get('pc_builder_item_anti_virus');
            
            $request->merge(['slug' => $av['slug']]);
            $this->cartRepository->addToCart($request);
        }
        
        // headphone
        if(Session::has('pc_builder_item_headphone')) {
            $allCounter ++;
            $headphone = Session::get('pc_builder_item_headphone');
            
            $request->merge(['slug' => $headphone['slug']]);
            $this->cartRepository->addToCart($request);
        }
        
        // ups
        if(Session::has('pc_builder_item_ups')) {
            $allCounter ++;
            $ups = Session::get('pc_builder_item_ups');
            
            $request->merge(['slug' => $ups['slug']]);
            $this->cartRepository->addToCart($request);
        }

        if($allCounter == 0) {
            return redirect()->route('pc-builder')->with('warning', 'No item found to add in cart.');
        } else {
            return redirect()->route('pc-builder')->with('success', 'Products Added to Cart Successfully.');
        }

    }

    public function printPc()
    {
        return view('frontend.print_pc');
    }
}

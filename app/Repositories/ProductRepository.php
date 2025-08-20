<?php

namespace App\Repositories;

use App\CPU\Images;
use App\CPU\Helpers;
use App\Models\Product;
use App\Models\ProductTax;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\ProductBudget;
use App\Models\ProductDetail;
use App\Models\StockPurchase;

use App\Models\Category;
use App\Models\FlashDealType;
use App\Models\PcBuilderItem;

use App\Models\ProductFeature;
use App\Models\ProductPurpose;
use App\Models\ProductQuestion;
use Illuminate\Validation\Rule;
use App\Models\SpecificationKey;

use App\Models\ProductScreenSize;
use App\Models\ProductPortability;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\ProductRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class ProductRepository implements ProductRepositoryInterface
{
    public function index($request, $category_id = null, $source = null)
    {
        if ($request == null) {
            return Product::where('status', 1)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request->best_seller)) {

            return Product::where('status', 1)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->orderBY('ratings_count', 'DESC')
                ->take(8)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request->on_sale_product)) {
            return Product::where('status', 1)
                ->where('is_discounted', 1)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->inRandomOrder()
                ->take(9)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request->top_rated_product)) {
            return Product::where('status', 1)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->inRandomOrder()
                ->take(9)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request->is_featured_list)) {
            return Product::where('status', 1)
                ->where('is_featured', 1)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->inRandomOrder()
                ->take(9)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request->featured)) {

            return Product::where('status', 1)
                ->where('is_featured', 1)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->inRandomOrder()
                ->take(6)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request->trending)) {

            return Product::where('status', 1)
                ->where('is_featured', 1)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->inRandomOrder()
                ->take(6)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request->offred)) {

            return Product::where('status', 1)
                ->when(isset($category_id), function ($q) use ($category_id) {

                    $q->where('category_id', $category_id);
                })
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->orderByRaw('CASE
                                WHEN discount_type = "percentage" AND discount > 15 THEN 1
                                WHEN discount_type = "flat" THEN 2
                                ELSE 3
                              END')
                ->orderBY('discount', 'DESC')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request) && $request == 'related_products') {
            return Product::where('status', 1)
                ->where('category_id', $source['category_id'])
                ->orWhere('brand_id', $source['brand_id'])
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->orderByRaw('CASE
                                WHEN discount_type = "percentage" AND discount > 15 THEN 1
                                WHEN discount_type = "flat" THEN 2
                                ELSE 3
                              END')
                ->orderBY('discount', 'DESC')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request) && $request == 'same_category_products') {
            return Product::where('status', 1)
                ->where('category_id', $source['category_id'])
                ->whereNot('id', $source['product_id'])
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->orderByRaw('CASE
                                WHEN discount_type = "percentage" AND discount > 15 THEN 1
                                WHEN discount_type = "flat" THEN 2
                                ELSE 3
                              END')
                ->orderBY('discount', 'DESC')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request) && $request == 'same_brand_products') {
            return Product::where('status', 1)
                ->where('brand_id', $source['brand_id'])
                ->whereNot('id', $source['product_id'])
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->orderByRaw('CASE
                                WHEN discount_type = "percentage" AND discount > 15 THEN 1
                                WHEN discount_type = "flat" THEN 2
                                ELSE 3
                              END')
                ->orderBY('discount', 'DESC')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($request) && $request == 'visited_product_list') {
            return Product::where('status', 1)
                ->whereIn('id', $source)
                ->withCount('ratings')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->orderByRaw('CASE
                                WHEN discount_type = "percentage" AND discount > 15 THEN 1
                                WHEN discount_type = "flat" THEN 2
                                ELSE 3
                              END')
                ->orderBY('discount', 'DESC')
                ->take(5)
                ->get()
                ->map(function ($product) {
                    return $this->mapper($product);
                });
        } elseif (isset($category_id) && count($category_id) > 0) {
            $products = Product::where('status', 1)
                ->whereIn('category_id', $category_id)
                ->withCount('ratings')
                ->with('brand:id,name,slug','details:id,product_id,average_rating')
                ->when(isset($request->in_stock) && $request->in_stock == true && !isset($request->out_of_stock), function ($q) {
                    $q->where('in_stock', 1);
                })
                ->when(isset($request->price_range) && count($request->price_range) == 2&& (isset($request->price_range[0])&&isset($request->price_range[1])), function ($q) use ($request) {
                    $q->whereBetween('unit_price', [convert_price_to_usd($request->price_range[0]), convert_price_to_usd($request->price_range[1])]);

                })
                ->when(isset($request->rating) && count($request->rating) > 0&& (isset($request->rating[0])&&isset($request->rating[1])), function ($q) use ($request) {
                    $q->whereHas('details', function ($query) use ($request) {
                        $query->whereBetween('average_rating', $request->rating);
                    });
                })
                ->when(isset($request->out_of_stock) && $request->out_of_stock == true && !isset($request->in_stock), function ($q) {
                    $q->where('in_stock', 0);
                })
                ->when(isset($request->pre_order) && $request->pre_order == true, function ($q) {
                    $q->where('stage', 'pre-order');
                })
                ->when(isset($request->up_coming) && $request->up_coming == true, function ($q) {
                    $q->where('stage', 'upcoming');
                })
                ->when(isset($request->brands) && count($request->brands) > 0, function ($q) use ($request) {
                    $q->whereHas('brand', function ($query) use ($request) {
                        $query->whereIn('id', $request->brands);
                    });
                })
                ->when(isset($request->specifications) && count($request->specifications) > 0, function ($q) use ($request) {
                    $q->whereHas('specifications', function ($specificationQuery) use ($request) {
                        $specificationQuery->whereIn('attribute_id', $request->specifications);
                    });
                })
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['specifications' => function ($query) {
                    $query->where('key_feature', 1)
                        ->with([
                            'specificationKeyType:id,name,position',
                            'specificationKeyTypeAttribute:id,name,extra'
                        ])->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                        ->orderBy('specification_key_types.position', 'ASC');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->when(isset($request->sortBy) && $request->sortBy != null, function ($q) use ($request) {
                    switch ($request->sortBy) {
                        case 'latest':
                            $q->orderBy('created_at', 'desc');
                            break;
                        case 'popularity':
                            $q->orderBy('ratings_count', 'desc');
                            break;
                        case 'price':
                            $q->orderBy('unit_price', 'asc');
                            break;
                        case 'price-desc':
                            $q->orderBy('unit_price', 'desc');
                            break;
                        default:
                            break;
                    }
                })
                ->when(!isset($request->sortBy), function ($q) {
                    $q->orderBy('ratings_count', 'desc');
                })
                ->paginate(12)
                ->withQueryString();

            $mappedProducts = $products->getCollection()->map(function ($product) {
                return $this->mapper($product);
            });

            return $products->setCollection($mappedProducts);
        } elseif (isset($request['brand_id']) && $request['brand_id'] != null) {
            $products = Product::where('status', 1)
                ->where('brand_id', $request['brand_id'])
                ->withCount('ratings')
                ->with('brand:id,name,slug')
                ->with('brandType:id,name')
                ->when(isset($request->in_stock) && $request->in_stock == true && !isset($request->out_of_stock), function ($q) {
                    $q->where('in_stock', 1);
                })
                ->when(isset($request->out_of_stock) && $request->out_of_stock == true && !isset($request->in_stock), function ($q) {
                    $q->where('in_stock', 0);
                })
                ->when(isset($request->price_range) && count($request->price_range) == 2&& (isset($request->price_range[0])&&isset($request->price_range[1])), function ($q) use ($request) {
                    $q->whereBetween('unit_price', [convert_price_to_usd($request->price_range[0]), convert_price_to_usd($request->price_range[1])]);

                })
                ->when(isset($request->brandTypes) && count($request->brandTypes) > 0, function ($q) use ($request) {
                    $q->whereHas('brandType', function ($query) use ($request) {
                        $query->whereIn('id', $request->brandTypes);
                    });
                })
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['specifications' => function ($query) {
                    $query->where('key_feature', 1)
                        ->with([
                            'specificationKeyType:id,name,position',
                            'specificationKeyTypeAttribute:id,name,extra'
                        ])->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                        ->orderBy('specification_key_types.position', 'ASC');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->when(isset($request->sortBy) && $request->sortBy != null, function ($q) use ($request) {
                    switch ($request->sortBy) {
                        case 'latest':
                            $q->orderBy('created_at', 'desc');
                            break;
                        case 'popularity':
                            $q->orderBy('ratings_count', 'desc');
                            break;
                        case 'price':
                            $q->orderBy('unit_price', 'asc');
                            break;
                        case 'price-desc':
                            $q->orderBy('unit_price', 'desc');
                            break;
                        default:
                            break;
                    }
                })
                ->when(!isset($request->sortBy), function ($q) {
                    $q->orderBy('ratings_count', 'desc');
                })
                ->paginate(18)
                ->withQueryString();

            $mappedProducts = $products->getCollection()->map(function ($product) {
                return $this->mapper($product);
            });

            return $products->setCollection($mappedProducts);
        } elseif (isset($request->search_module) && $request->search_module == 'ajax_search') {
            $products = Product::where('status', 1)
                ->withCount('ratings')
                ->with('brand:id,name,slug')
                ->with('brandType:id,name')

                ->when(isset($request->search), function ($q) use ($request) {
                    $q->where(function ($query) use ($request) {
                        $searchTerm = '%' . $request->search . '%';

                        $query->where('name', 'LIKE', $searchTerm)
                            ->orWhere('sku', 'LIKE', $searchTerm);

                        $query->orWhereHas('details', function ($q) use ($searchTerm) {
                            $q->where('site_title', 'LIKE', $searchTerm)
                                ->orWhere('meta_title', 'LIKE', $searchTerm);
                        });
                    });
                })

                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])

                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])

                ->paginate(3)
                ->withQueryString();

            $mappedProducts = $products->getCollection()->map(function ($product) {
                return $this->mapper($product);
            });

            return $products->setCollection($mappedProducts);
        }
    }

    public function flashDealProduct($id)
    {
        return FlashDealType::where('flash_deal_id', $id)
        ->with(['product' => function ($query) {
            $query->where('status', 1)
                ->withCount('ratings')
                ->with('brand:id,name,slug')
                ->with(['ratings' => function ($query) {
                    $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                        ->groupBy('product_id');
                }])
                ->with(['specifications' => function ($query) {
                    $query->where('key_feature', 1)
                        ->with([
                            'specificationKeyType:id,name,position',
                            'specificationKeyTypeAttribute:id,name,extra'
                        ])->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                        ->orderBy('specification_key_types.position', 'ASC');
                }])
                ->with(['image' => function ($query) {
                    $query->where('status', 1)->select('product_id', 'image');
                }])
                ->paginate(12)
                ->withQueryString();
        }])
        ->get()
        ->map(function ($data) {
            return $this->mapper($data->product);
        });
    }

    public function pc_builder_items($request)
    {
        if ($request->pc_builder == 'peri') {
            return ProductDetail::where('pc_builder_item', true)
                ->where('component_type', $request->pc_builder)
                ->when(isset($request->pc_builder) && $request->pc_builder == 'peri', function ($q) use ($request) {
                    $q->where('peri_component_type', $request->comp_type);
                })
                ->with(['product' => function ($query) use ($request) {
                    $query->where('status', 1)
                        ->withCount('ratings')
                        ->with('brand:id,name,slug')
                        ->when(isset($request->in_stock) && $request->in_stock == true && !isset($request->out_of_stock), function ($q) {
                            $q->where('in_stock', 1);
                        })
                        ->when(isset($request->out_of_stock) && $request->out_of_stock == true && !isset($request->in_stock), function ($q) {
                            $q->where('in_stock', 0);
                        })
                        ->when(isset($request->pre_order) && $request->pre_order == true, function ($q) {
                            $q->where('stage', 'pre-order');
                        })
                        ->when(isset($request->up_coming) && $request->up_coming == true, function ($q) {
                            $q->where('stage', 'upcoming');
                        })
                        ->when(isset($request->brands) && count($request->brands) > 0, function ($q) use ($request) {
                            $q->whereHas('brand', function ($query) use ($request) {
                                $query->whereIn('id', $request->brands);
                            });
                        })
                        ->when(isset($request->specifications) && count($request->specifications) > 0, function ($q) use ($request) {
                            $q->whereHas('specifications', function ($specificationQuery) use ($request) {
                                $specificationQuery->whereIn('attribute_id', $request->specifications);
                            });
                        })
                        ->with(['ratings' => function ($query) {
                            $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                                ->groupBy('product_id');
                        }])
                        ->with(['specifications' => function ($query) {
                            $query->where('key_feature', 1)
                                ->with([
                                    'specificationKeyType:id,name,position',
                                    'specificationKeyTypeAttribute:id,name,extra'
                                ])->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                                ->orderBy('specification_key_types.position', 'ASC');
                        }])
                        ->with(['image' => function ($query) {
                            $query->where('status', 1)->select('product_id', 'image');
                        }])
                        ->when(isset($request->sortBy) && $request->sortBy != null, function ($q) use ($request) {
                            switch ($request->sortBy) {
                                case 'latest':
                                    $q->orderBy('created_at', 'desc');
                                    break;
                                case 'popularity':
                                    $q->orderBy('ratings_count', 'desc');
                                    break;
                                case 'price':
                                    $q->orderBy('unit_price', 'asc');
                                    break;
                                case 'price-desc':
                                    $q->orderBy('unit_price', 'desc');
                                    break;
                                default:
                                    break;
                            }
                        })
                        ->when(!isset($request->sortBy), function ($q) {
                            $q->orderBy('ratings_count', 'desc');
                        })
                        ->paginate(12)
                        ->withQueryString();
                }])
                ->get()
                ->map(function ($data) {
                    return $this->mapper($data->product);
                });
        } else {
            return PcBuilderItem::where('comp_type', $request->comp_type)
                ->whereHas('product', function ($query) {
                    $query->where('status', 1);
                })
                ->with(['details' => function($q) use ($request) {
                    $q->where('pc_builder_item', 1);
                    $q->where('component_type', 'core');
                }])
                ->when(isset($request->cpu_brand) && $request->cpu_brand != '', function ($q) use ($request) {
                    $q->where('mb_supported_cpu_brand', $request->cpu_brand);
                })
                ->when(isset($request->cpu_generation) && $request->cpu_generation != '', function ($q) use ($request) {
                    $q->where('mb_cpu_generation', $request->cpu_generation);
                })
                ->when(isset($request->socket_type) && $request->socket_type != '', function ($q) use ($request) {
                    $q->where('mb_supported_socket', $request->socket_type);
                })
                ->when(isset($request->ram_memory_type) && $request->ram_memory_type != '', function ($q) use ($request) {
                    $q->where('ram_memory_type', $request->ram_memory_type);
                })
                ->when(isset($request->ram_xmp_support) && $request->ram_xmp_support != 0, function ($q) use ($request) {
                    $q->where('ram_xmp_support', $request->ram_xmp_support);
                })
                ->when(isset($request->storage_type) && $request->storage_type != 0, function ($q) use ($request) {
                    $q->where('ram_xmp_support', $request->storage_type);
                })
                ->when(isset($request->storage_m2_support) && $request->storage_m2_support != 0, function ($q) use ($request) {
                    $q->where('storage_m2_support', $request->storage_m2_support);
                })
                ->when(isset($request->mb_form_factor) && $request->mb_form_factor != 0, function ($q) use ($request) {
                    $q->where('gc_supported_form_factor', $request->mb_form_factor);
                })
                ->when(isset($request->gc_supported_pcie_slot) && $request->gc_supported_pcie_slot != 0, function ($q) use ($request) {
                    $q->where('gc_supported_pcie_slot', $request->gc_supported_pcie_slot);
                })
                ->when(isset($request->casing_form_factor) && $request->casing_form_factor != 0, function ($q) use ($request) {
                    $q->where('casing_form_factor', $request->casing_form_factor);
                })
                ->with(['product' =>
                function ($query) use ($request) {
                    $query->where('status', 1)
                        ->withCount('ratings')
                        ->with('brand:id,name,slug')
                        ->with(['details' => function ($details) {
                            $details->where('pc_builder_item', true)
                                ->where('component_type', 'core');
                        }])
                        ->when(isset($request->in_stock) && $request->in_stock == true && !isset($request->out_of_stock), function ($q) {
                            $q->where('in_stock', 1);
                        })
                        ->when(isset($request->out_of_stock) && $request->out_of_stock == true && !isset($request->in_stock), function ($q) {
                            $q->where('in_stock', 0);
                        })
                        ->when(isset($request->pre_order) && $request->pre_order == true, function ($q) {
                            $q->where('stage', 'pre-order');
                        })
                        ->when(isset($request->up_coming) && $request->up_coming == true, function ($q) {
                            $q->where('stage', 'upcoming');
                        })
                        ->when(isset($request->brands) && count($request->brands) > 0, function ($q) use ($request) {
                            $q->whereHas('brand', function ($query) use ($request) {
                                $query->whereIn('id', $request->brands);
                            });
                        })
                        ->when(isset($request->specifications) && count($request->specifications) > 0, function ($q) use ($request) {
                            $q->whereHas('specifications', function ($specificationQuery) use ($request) {
                                $specificationQuery->whereIn('attribute_id', $request->specifications);
                            });
                        })
                        ->with(['ratings' => function ($query) {
                            $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                                ->groupBy('product_id');
                        }])
                        ->with(['specifications' => function ($query) {
                            $query->where('key_feature', 1)
                                ->with([
                                    'specificationKeyType:id,name,position',
                                    'specificationKeyTypeAttribute:id,name,extra'
                                ])->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                                ->orderBy('specification_key_types.position', 'ASC');
                        }])
                        ->with(['image' => function ($query) {
                            $query->where('status', 1)->select('product_id', 'image');
                        }])
                        ->when(isset($request->sortBy) && $request->sortBy != null, function ($q) use ($request) {
                            switch ($request->sortBy) {
                                case 'latest':
                                    $q->orderBy('created_at', 'desc');
                                    break;
                                case 'popularity':
                                    $q->orderBy('ratings_count', 'desc');
                                    break;
                                case 'price':
                                    $q->orderBy('unit_price', 'asc');
                                    break;
                                case 'price-desc':
                                    $q->orderBy('unit_price', 'desc');
                                    break;
                                default:
                                    break;
                            }
                        })
                        ->when(!isset($request->sortBy), function ($q) {
                            $q->orderBy('ratings_count', 'desc');
                        })
                        ->paginate(15)
                        ->withQueryString();
                }])->get()
                ->map(function ($data) {
                    return $this->mapper($data->product);
                });
        }
    }

    public function search($search, $categories, $brands, $sort, $terms = null)
    {
        $products = Product::where('status', 1)
            ->where(function ($query) use ($categories, $brands, $search) {
                if (isset($categories)) {
                    $query->orWhereIn('category_id', $categories);
                }

                if (isset($brands)) {
                    $query->orWhereIn('brand_id', $brands);
                }

                if (isset($search)) {
                    $query->orWhere('name', 'like', '%' . $search . '%');
                }
            })
            ->withCount('ratings')
            ->with('brand:id,name,slug')

            ->with(['ratings' => function ($query) {
                $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                    ->groupBy('product_id');
            }])
            ->with(['specifications' => function ($query) {
                $query->where('key_feature', 1)
                    ->with([
                        'specificationKeyType:id,name,position',
                        'specificationKeyTypeAttribute:id,name,extra'
                    ])->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                    ->orderBy('specification_key_types.position', 'ASC');
            }])
            ->with(['image' => function ($query) {
                $query->where('status', 1)->select('product_id', 'image');
            }])
            ->when(isset($sort) && $sort != null, function ($q) use ($sort) {
                switch ($sort) {
                    case 'latest':
                        $q->orderBy('created_at', 'desc');
                        break;
                    case 'popularity':
                        $q->orderBy('ratings_count', 'desc');
                        break;
                    case 'featured':
                        $q->orderBy('is_featured', 'ASC');
                        break;
                    case 'on-sale':
                        $q->orderBy('is_discounted', 'DESC');
                        break;
                    case 'price':
                        $q->orderBy('unit_price', 'asc');
                        break;
                    case 'price-desc':
                        $q->orderBy('unit_price', 'desc');
                        break;
                    default:
                        break;
                }
            })
            ->when(isset($terms) && $terms == 'laptop_finder', function ($q) use ($terms) {

                $feature_id_array = Session::has('laptop_finder_features_id') ? Session::get('laptop_finder_features_id') : [];
                $screen_size_id = Session::has('laptop_finder_screen_size_id') ? Session::get('laptop_finder_screen_size_id') : null;
                $portable_id = Session::has('laptop_finder_portable_id') ? Session::get('laptop_finder_portable_id') : null;
                $budget_id = Session::has('laptop_finder_budget') ? Session::get('laptop_finder_budget') : [];
                $purpose_id_array = Session::has('laptop_finder_purpose_id') ? Session::get('laptop_finder_purpose_id') : [];

                // Get the default laptop category and its child categories
                $default_laptop_category = Category::find(get_settings('default_laptop_category'));
                $category = new Category;
                $laptopCategoryIds = $category->getChildCategoriesId($default_laptop_category);
                array_push($laptopCategoryIds, $default_laptop_category->id);

                // Initialize the product query
                $q->whereIn('category_id', $laptopCategoryIds);

                // Apply filters if they are not null or empty
                if ($budget_id) {
                    $q->whereHas('budgets', function ($query) use ($budget_id) {
                        $query->where('budget_id', $budget_id);
                    });
                }

                if ($screen_size_id) {
                    $q->whereHas('screenSizes', function ($query) use ($screen_size_id) {
                        $query->where('size_id', $screen_size_id);
                    });
                }

                if ($portable_id) {
                    $q->whereHas('portabilites', function ($query) use ($portable_id) {
                        $query->where('portable_id', $portable_id);
                    });
                }

                if (!empty($feature_id_array)) {
                    $q->whereHas('features', function ($query) use ($feature_id_array) {
                        $query->whereIn('feature_id', $feature_id_array);
                    });
                }

                if (!empty($purpose_id_array)) {
                    $q->whereHas('purposes', function ($query) use ($purpose_id_array) {
                        $query->whereIn('purpose_id', $purpose_id_array);
                    });
                }
            })
            ->when(!isset($sort), function ($q) {
                $q->orderBy('ratings_count', 'desc');
            })
            ->paginate(18)
            ->withQueryString();

        $mappedProducts = $products->getCollection()->map(function ($product) {
            return $this->mapper($product);
        });

        return $products->setCollection($mappedProducts);
    }

    public function isDiscountedProduct($product)
    {
        return $product->discount_type && $product->discount > 0;
    }

    public function discountPrice($product)
    {
        $price = $product->unit_price;
        $isDiscounted = $product->discount_type && $product->discount > 0;
        $discountedPrice = $product->unit_price; // Default to unit price

        if ($isDiscounted) {
            $discountAmount = $product->discount_type == 'amount'
                ? $product->discount
                : ($product->unit_price * ($product->discount / 100));

            $price = $product->unit_price - $discountAmount;
        }

        return $price;
    }


    public function accessMapper($product) {
        return $this->mapper($product);
    }
    private function mapper($product)
    {
        // Determine if the product is discounted
        $isDiscounted = $product->discount_type && $product->discount > 0;
        $discountedPrice = $product->unit_price; // Default to unit price

        if ($isDiscounted) {
            $discountAmount = $product->discount_type == 'amount'
                ? $product->discount
                : ($product->unit_price * ($product->discount / 100));

            $discountedPrice = $product->unit_price - $discountAmount;
        }

        // Get average rating and hover image
        // Get average rating and convert to percentage
        $averageRating = $product->ratings->isNotEmpty() ? $product->ratings->first()->averageRating : null;
        $averageRatingPercentage = $averageRating !== null ? ($averageRating / 5) * 100 : null;
        $hoverImage = $product->image->isNotEmpty() ? $product->image->first()->image : null;

        $stockStatus = 'out_of_stock';
        $inCity = false;
        $stockResponse = getProductStock($product->id, 1);
        if ($stockResponse['status']) {
            $stockStatus = 'in_stock';
        }

        if( isset($stockResponse['in_city']) && $stockResponse['in_city'] == true) {
            $inCity = true;
        }

        if ($product->specifications) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'stage' => $product->stage,
                'stock_status' => $stockStatus,
                'available_in_city' => $inCity,
                'thumb_image' => $product->thumb_image,
                'hover_image' => $hoverImage,
                'unit_price' => $product->unit_price,
                'discounted_price' => $discountedPrice, // Include discounted price
                'discount' => $product->discount,
                'discount_type' => $isDiscounted ? $product->discount_type : null,
                'averageRating' => $averageRatingPercentage,
                'ratingCount' => $product->ratings_count,
                'stock_types' => $product->stock_types,
                'specifications' => $product->specifications->take(4)->map(function ($specifications) {
                    return $this->specificationMapper($specifications);
                })
            ];
        } else {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'available_in_city' => $inCity,
                'thumb_image' => $product->thumb_image,
                'hover_image' => $hoverImage,
                'unit_price' => $product->unit_price,
                'discounted_price' => $discountedPrice, // Include discounted price
                'discount' => $product->discount,
                'discount_type' => $isDiscounted ? $product->discount_type : null,
                'averageRating' => $averageRatingPercentage,
                'ratingCount' => $product->ratings_count,
                'stock_types' => $product->stock_types,
            ];
        }
    }

    public function compare($productId = null)
    {
        $productIdArray = [];
        $models = [];
        $specKeyIds = [];
        $allProductsSpecifications = [];

        if ($productId != null) {
            $productIdArray = [$productId];
        } else if (session()->has('compare_list') && is_array(session()->get('compare_list'))) {
            $productIdArray = session()->get('compare_list');
        }

        if (count($productIdArray) > 0) {
            $specification = ProductSpecification::whereIn('product_id', $productIdArray)->with('product:id,category_id,name', 'specificationKey', 'specificationKeyType', 'specificationKeyTypeAttribute')->get();

            $products = $this->getProductByIds($productIdArray)->map(function ($product) use ($specification, &$models, &$allProductsSpecifications) {
                $summery = [];

                $averageRating = $product->ratings->isNotEmpty() ? $product->ratings->first()->averageRating : 0;
                $averageRatingCount = $product->ratings->isNotEmpty() ? count($product->ratings) : 0;
                $averageRatingPercentage = $averageRating !== null ? ($averageRating / 5) * 100 : 0;

                $specification = $specification->where('product_id', $product->id);

                $mapped = $specification->map(function ($item) {
                    return [
                        'key_id' => $item->key_id ?? null,
                        'specificationKey' => $item->specificationKey ? $item->specificationKey->name : null,
                        'specificationKeyType' => $item->specificationKeyType ? $item->specificationKeyType->name : null,
                        'specificationKeyTypeAttribute' => $item->specificationKeyTypeAttribute ? $item->specificationKeyTypeAttribute->name . ' ' . $item->specificationKeyTypeAttribute->extra : null,
                    ];
                });

                if ($product->specifications->isNotEmpty()) {
                    $summery = $product->specifications->where('key_feature', 1)->map(function ($specification) {
                        return [
                            'type_name' => $specification->specificationKeyType->name,
                            'attr_name' => $specification->specificationKeyTypeAttribute->name,
                        ];
                    });
                }

                $models[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'brand' => $product->brand ? $product->brand->name : null,
                    'brand_slug' => $product->brand ? $product->brand->slug : null,
                    'slug' => $product->slug,
                    'image' => asset($product->thumb_image),
                    'discount_type' => $product->is_discounted,
                    'unit_price'   => $product->unit_price,
                    'discounted_price' => $this->discountPrice($product),
                    'discount' => $product->discount,
                    'stage' => $product->stage,
                    'stock' => getProductStock($product->id)['status'] == true ? 'In Stock' : 'Out of Stock',
                    'average_rating' => $averageRating,
                    'average_rating_percent' => $averageRatingPercentage,
                    'average_rating_count' => $averageRatingCount,
                    'summery' => $summery,
                ];

                $specKeyIds = $product->specifications->unique('key_id')->pluck('key_id')->toArray();

                $sadik = ProductSpecification::with([
                    'specificationKey:id,name',
                    'specificationKeyType:id,name',
                    'specificationKeyTypeAttribute:id,name'
                ])
                    ->whereIn('key_id', $specKeyIds)
                    ->where('product_id', $product->id)
                    ->get()
                    ->groupBy('specificationKey.name')
                    ->map(function ($specifications) use ($product, &$allProductsSpecifications) {
                        return $specifications->groupBy('specificationKeyType.name')
                            ->map(function ($types, $typeName) use ($product, &$allProductsSpecifications, $specifications) {
                                $keyName = $specifications->first()->specificationKey->name;
                                $attributeNames = $types->pluck('specificationKeyTypeAttribute.name');
                                return $allProductsSpecifications[$keyName][$typeName][$product->id] = $attributeNames[0];
                            });
                    });
            });
        }

        return  [$allProductsSpecifications, $models, $productIdArray];
    }

    private function specificationMapper($specification)
    {
        return [
            'type_name' => $specification->specificationKeyType->name,
            'attr_name' => $specification->specificationKeyTypeAttribute->name,
        ];
    }

    public function getAllProducts()
    {
        return Product::orderBy('id', 'DESC')->get();
    }

    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    public function getProductByIds($ids)
    {
        return Product::with('specifications')->whereIn('id', $ids)->get();
    }

    public function getProductStockPurchaseDetails($productId)
    {
        return StockPurchase::where('product_id', $productId)->orderBy('id', 'DESC')->get();
    }

    public function dataTable()
    {
        $models = $this->getAllProducts();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('product_name', function ($model) {
                return '<div class="row"><div class="col-auto">' . Images::show($model->thumb_image) . '</div><div class="col">' . $model->name . '</div></div>';
            })
            ->editColumn('info', function ($model) {
                $numberOfSale = $model->details ? $model->details->number_of_sale : 0;
                $averageRating = $model->details ? ($model->details->average_rating == null ? Helpers::productAverageRating($model->id) : $model->details->average_rating) : 0;
                $numberOfStock = $model->details ? $model->details->current_stock : 0;
                $stockStatus = 'success';

                if ($model->details->current_stock == 0) {
                    $stockStatus = 'danger';
                } elseif ($model->details->current_stock <= $model->details->low_stock_quantity) {
                    $stockStatus = 'warning';
                }

                return '
                    <b>In Stock</b>: <span class="badge bg-'. $stockStatus .' text white">' . $numberOfStock . '</span> <br>
                    <b>Number of Sale</b>: ' . $numberOfSale . ' <br>
                    <b>Base Price</b>:' . get_system_default_currency()->symbol . number_format(covert_to_defalut_currency($model->unit_price), 2) . '<br>
                    <b>Rating</b>: ' . $averageRating;
            })
            ->editColumn('created_by', function ($model) {
                return $model->admin->name;
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (Auth::guard('admin')->user()->hasPermissionTo('product.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.product.status', $model->id) . '" class="form-check-input product-publish" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }

            })
            ->editColumn('stock', function ($model) {
                return $model->details ? $model->details->current_stock : 0;
            })
            ->editColumn('featured', function ($model) {
                $is_featured = $model->is_featured == 1 ? 'checked' : '';

                if (Auth::guard('admin')->user()->hasPermissionTo('product.update')) {
                    return '<div class="form-check form-switch"><input  data-url="' . route('admin.product.featured', $model->id) . '" class="form-check-input featured_'. $model->id .'" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $is_featured . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->is_featured == 1 ? 'warning' : 'success';
                    $status = $model->is_featured == 1 ? 'Featured' : 'Normal';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.product.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'created_by', 'featured', 'info', 'stock', 'product_name'])
            ->make(true);
    }

    public function getSelectedProducts($category_id, $brand_id)
    {
        // Query the product table
        $query = Product::query();

        // Check if category_id is provided
        if (!is_null($category_id)) {
            $query->where('category_id', $category_id);
        }

        // Check if brand_id is provided
        if (!is_null($brand_id)) {
            $query->where('brand_id', $brand_id);
        }

        // Execute the query and return the result
        return $query->get();
    }

    public function dataTableWithAjaxSearch($category_id, $brand_id)
    {

        $models = $this->getSelectedProducts($category_id, $brand_id);
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('product_name', function ($model) {
                return '<div class="row"><div class="col-auto">' . Images::show($model->thumb_image) . '</div><div class="col">' . $model->name . '</div></div>';
            })
            ->editColumn('info', function ($model) {
                $numberOfSale = $model->details ? $model->details->number_of_sale : 0;
                $averageRating = $model->details ? ($model->details->average_rating == null ? Helpers::productAverageRating($model->id) : $model->details->average_rating) : 0;

                return '
                    <b>Number of Sale</b>: ' . $numberOfSale . ' <br>
                    <b>Base Price</b>:' . get_system_default_currency()->symbol . covert_to_defalut_currency($model->unit_price) . '<br>
                    <b>Rating</b>: ' . $averageRating;
            })
            ->editColumn('created_by', function ($model) {
                return $model->admin->name;
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 'active' ? 'checked' : '';
                if (Auth::guard('admin')->user()->hasPermissionTo('product.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.product.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }
            })
            ->editColumn('stock', function ($model) {
                return $model->details ? $model->details->current_stock : 0;
            })
            ->editColumn('featured', function ($model) {
                $is_featured = $model->is_featured == 1 ? 'checked' : '';
                if (Auth::guard('admin')->user()->hasPermissionTo('product.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.product.featured', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $is_featured . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->is_featured == 1 ? 'warning' : 'success';
                    $status = $model->is_featured == 1 ? 'Featured' : 'Normal';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.product.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'created_by', 'featured', 'info', 'stock', 'product_name'])
            ->make(true);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->product_cache_forget();
        return $product->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        $product->status = !$product->status;
        $product->save();

        $this->product_cache_forget();
        return response()->json(['success' => true, 'message' => 'Product status updated successfully.']);
    }

    public function updateFeatured($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        $product->is_featured = !$product->is_featured;
        $product->save();

        Cache::forget('homeProducts_featured');

        return response()->json(['success' => true, 'message' => 'Product featured status updated successfully.']);
    }

    public function storeProduct($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'min_purchase_quantity' => 'required|integer|min:1',
            'images' => 'required|array',
            'video_provider' => 'nullable|string|max:255',
            'video_link' => 'nullable|string',
            'description' => 'nullable|string',
            'unit' => 'required',
            'product_type' => 'required|string|max:255',
            'site_title' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_article_tags' => 'nullable|string|max:255',
            'meta_script_tags' => 'nullable|string|max:255',
            'specification_key' => 'nullable|array',
            'specification_key.*.key_id' => 'required_with:specification_key',
            'specification_key.*.type_id' => 'required_with:specification_key|array',
            'is_discounted' => 'required|boolean',
            'status' => 'required|boolean',
            'stage' => 'required|string',
            'is_featured' => 'required|boolean',
            'is_returnable' => 'required|boolean',
            'low_stock_quantity' => 'required|integer|min:0',
            'minus_stock' => 'nullable|integer|min:0',
            'cash_on_delivery' => 'required|boolean',
            'est_shipping_time' => 'nullable|string|max:255',
            'taxes' => 'required|array',
            'taxes.*' => 'numeric|min:0',
            'tax_types' => 'required|array',
            'tax_types.*' => 'string',
            'shipping_cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = $request->category_id[count($request->category_id) - 1];
        $product->admin_id = Auth::guard('admin')->id();
        $product->brand_id = $request->brand_id ?? 2;
        $product->brand_type_id = $request->brand_type_id;
        $product->product_type = $request->product_type;
        $product->thumb_image = Images::upload('products', $request->thumb_image);
        $product->unit_price = isset($request->unit_price) ? covert_to_usd($request->unit_price) : 0;
        $product->sku = $this->generateSku($request->category_id[count($request->category_id) - 1]);
        $product->status = $request->status;
        $product->stage = $request->stage;
        $product->in_stock = isset($request->in_stock) ? $request->in_stock : 0;
        $product->minus_stock = isset($request->minus_stock) ? $request->minus_stock : 0;
        $product->is_featured = $request->is_featured;
        $product->low_stock = isset($request->low_stock) ? $request->low_stock : 0;
        $product->is_discounted = $request->is_discounted;
        $product->discount_type = $request->discount_type;
        $product->discount = isset($request->discount_type) && $request->discount_type != 'amount' ? $request->discount : covert_to_usd($request->discount);
        $product->discount_start_date = $request->discount_start_date ? date('Y-m-d', strtotime($request->discount_start_date)) : null;
        $product->discount_end_date = $request->discount_end_date ? date('Y-m-d', strtotime($request->discount_end_date)) : null;
        $product->is_returnable = $request->is_returnable;
        $product->return_deadline = $request->return_deadline;
        $product->stock_types = $request->stock_types;
        $product->save();

        if ($product) {
            $details = new ProductDetail();
            $details->product_id = $product->id;
            $details->video_provider = $request->video_provider;
            $details->video_link = $request->video_link;
            $details->description = $request->description;
            $details->current_stock = 0;
            $details->low_stock_quantity = $request->low_stock_quantity;
            $details->cash_on_delivery = $request->cash_on_delivery;
            $details->est_shipping_days = $request->est_shipping_time;
            $details->site_title = $request->site_title;
            $details->meta_title = $request->meta_title;
            $details->meta_keyword = $request->meta_keyword;
            $details->meta_description = $request->meta_description;
            $details->meta_article_tags = $request->meta_article_tags;
            $details->meta_script_tags = $request->meta_script_tags;
            $details->pc_builder_item = $request->add_to_pc_build;
            $details->component_type = $request->component_type;
            $details->peri_component_type = $request->peri_component_type;
            $details->points = $request->points;
            $details->unit = $request->unit;
            $details->shipping_cost = covert_to_usd($request->shipping_cost);
            $details->save();

            // If any taxes array exist
            $taxArray = $request->taxes;
            if (count($taxArray) > 0) {
                $taxIdArray = $request->tax_id;
                $taxTypeArray = $request->tax_types;

                for ($taxCounter = 0; $taxCounter < count($taxArray); $taxCounter++) {
                    $tax = new ProductTax;
                    $tax->product_id = $product->id;
                    $tax->tax_id = $taxIdArray[$taxCounter];
                    $tax->tax = $taxTypeArray[$taxCounter] == 'flat' ? covert_to_usd($taxArray[$taxCounter]) : $taxArray[$taxCounter];
                    $tax->tax_type = $taxTypeArray[$taxCounter] == 'flat' ? 'amount' : 'percent';
                    $tax->save();
                }
            }

            // Stock Purchase
            if ($request->add_stock_now == 'now') {

                $totalGivenQuantity = $request->quantity;
                $totalGetQuantity = 0;
                $stockType = $request->stock_types;
                switch ($stockType) {
                    case 'globally':
                        $totalGetQuantity = $request->globally_stock_amount;
                        break;
                    case 'zone_wise':
                        $totalGetQuantity = array_sum($request->zone_wise_stock_quantity);
                        break;
                    case 'country_wise':
                        $totalGetQuantity = array_sum($request->country_wise_quantity);
                        break;
                    case 'city_wise':
                        $totalGetQuantity = array_sum($request->city_wise_quantity);
                        break;
                }

                if ($totalGivenQuantity != $totalGetQuantity) {
                    return response()->json(['status' => false, 'message' => 'Your total given quantity is: ' . $totalGivenQuantity . ', but we got ' . $totalGetQuantity]);
                }

                $stockPurchase = new StockPurchase;
                $stockPurchase->product_id = $product->id;
                $stockPurchase->admin_id = $product->admin_id;
                $stockPurchase->currency_id = $request->currency_id;
                $stockPurchase->sku = $request->sku;
                $stockPurchase->quantity = $request->quantity;
                $stockPurchase->unit_price = covert_to_usd($request->unit_price);
                $stockPurchase->purchase_unit_price = covert_to_usd($request->purchase_unit_price);
                $stockPurchase->purchase_total_price = covert_to_usd($request->quantity * $request->purchase_unit_price);
                $stockPurchase->is_sellable = $request->is_sellable;
                if (isset($request->file)) {
                    $stockPurchase->file = Images::upload('products.files', $request->file);
                }
                $stockPurchase->save();
                if ($stockPurchase) {

                    // update product in_stock column
                    if ($stockPurchase->is_sellable == 1) {
                        $product->in_stock = 1;
                        $product->save();
                    }

                    // Add stock data into product_stock table by stock_types
                    switch ($request->stock_types) {
                        case 'globally':
                            $stock = new ProductStock;
                            $stock->product_id = $product->id;
                            $stock->stock_purchase_id  = $stockPurchase->id;
                            $stock->in_stock = 1;
                            $stock->number_of_sale = 0;
                            $stock->stock = $request->globally_stock_amount;
                            $stock->save();
                            break;
                        case 'zone_wise':

                            $zoneIdArray = $request->zone_id;
                            $zoneWiseStockQuantityArray = $request->zone_wise_stock_quantity;

                            if (count($zoneIdArray) > 0) {
                                for ($zoneCounter = 0; $zoneCounter < count($zoneIdArray); $zoneCounter++) {
                                    $stock = new ProductStock;
                                    $stock->product_id = $product->id;
                                    $stock->stock_purchase_id  = $stockPurchase->id;
                                    $stock->zone_id  = $zoneIdArray[$zoneCounter];
                                    $stock->in_stock = 1;
                                    $stock->number_of_sale = 0;
                                    $stock->stock = $zoneWiseStockQuantityArray[$zoneCounter];
                                    $stock->save();
                                }
                            }

                            break;
                        case 'country_wise':
                            $countryIdArray = $request->country_id;
                            $countryWiseStockQuantityArray = $request->country_wise_quantity;

                            if (count($countryIdArray) > 0) {
                                for ($countryCounter = 0; $countryCounter < count($countryIdArray); $countryCounter++) {
                                    $stock = new ProductStock;
                                    $stock->product_id = $product->id;
                                    $stock->stock_purchase_id  = $stockPurchase->id;
                                    $stock->country_id  = $countryIdArray[$countryCounter];
                                    $stock->in_stock = 1;
                                    $stock->number_of_sale = 0;
                                    $stock->stock = $countryWiseStockQuantityArray[$countryCounter];
                                    $stock->save();
                                }
                            }
                            break;
                        case 'city_wise':

                            $cityIdArray = $request->city_id;
                            $cityWiseStockQuantityArray = $request->city_wise_quantity;

                            if (count($cityIdArray) > 0) {
                                for ($cityCounter = 0; $cityCounter < count($cityIdArray); $cityCounter++) {
                                    $stock = new ProductStock;
                                    $stock->product_id = $product->id;
                                    $stock->stock_purchase_id  = $stockPurchase->id;
                                    $stock->city_id  = $cityIdArray[$cityCounter];
                                    $stock->in_stock = 1;
                                    $stock->number_of_sale = 0;
                                    $stock->stock = $cityWiseStockQuantityArray[$cityCounter];
                                    $stock->save();
                                }
                            }

                            break;
                    }

                    // update current_stock data on product_details table
                    if ($stockPurchase->is_sellable == 1) {
                        $details->current_stock = $request->quantity;
                        $details->save();
                    }
                }
            }

            if (isset($request->images)) {
                foreach ($request->images as $image) {
                    $product_image = new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->image = Images::upload('products', $image);
                    $product_image->status = 1;
                    $product_image->save();
                }
            }

            // $tax will be Added Later Product_Taxes
            if (isset($request->specification_key)) {

                foreach ($request->specification_key as $specification) {
                    $keyId = $specification['key_id'];
                    $processedTypeIds = [];

                    // Loop through type_id
                    foreach ($specification['type_id'] as $typeId => $value) {

                        if (is_numeric($typeId)) {
                            $tkey = $value;
                        }
                        // Skip non-numeric keys
                        if (!is_numeric($typeId)) {
                            continue;
                        }

                        // Skip if this type_id has already been processed
                        if (in_array($typeId, $processedTypeIds)) {
                            continue;
                        }

                        // Initialize variables
                        $firstAttributeId = null;
                        $featuresExist = false;
                        // Check for attributes
                        if (
                            isset($specification['type_id']['attribute_id'][$value])
                            && is_array($specification['type_id']['attribute_id'][$value])
                            && !empty($specification['type_id']['attribute_id'][$value])
                        ) {

                            // Get the first attribute ID
                            $firstAttributeId = $specification['type_id']['attribute_id'][$value][0]; // First attribute
                        }

                        // Check for features
                        if (isset($specification['type_id']['features'][$value])) {
                            $featuresExist = true; // Set to true if features exist
                        }

                        // Create a ProductSpecification entry only if we have an attribute ID
                        if ($firstAttributeId !== null) {
                            $productSpecification = new ProductSpecification();
                            $productSpecification->product_id = $product->id; // Ensure this is included in the request
                            $productSpecification->key_id = $keyId;
                            $productSpecification->type_id = intval($tkey);
                            $productSpecification->attribute_id = intval($firstAttributeId); // Store the first attribute ID

                            // Set key_feature as boolean
                            $productSpecification->key_feature = $featuresExist ? 1 : 0; // Use ternary for clarity

                            $productSpecification->save(); // Save the entry
                        }

                        // Mark this type_id as processed
                        $processedTypeIds[] = $typeId;
                    }
                }
            }

            if (isset($request->add_to_pc_build) && $request->add_to_pc_build == 1 && $request->component_type == 'core') {
                switch ($request->core_component_type) {
                    case 'casing':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'casing',
                            'casing_form_factor' => $request->casing_form_factor,
                            'casing_psu_installed' => $request->casing_psu_installed,
                            'casing_fan_installed' => $request->casing_fan_installed,
                            'casing_number_of_fan_front' => $request->casing_number_of_fan_front,
                            'casing_number_of_fan_top' => $request->casing_number_of_fan_top,
                            'casing_number_of_fan_back' => $request->casing_number_of_fan_back
                        ]);
                        break;
                    case 'processor':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'processor',
                            'cpu_brand' => $request->cpu_brand,
                            'cpu_generation' => $request->cpu_generation,
                            'socket_type' => $request->socket_type,
                            'default_tdp' => $request->default_tdp ?? 0,
                            'casing_number_of_fan_top' => $request->casing_number_of_fan_top,
                            'casing_number_of_fan_back' => $request->casing_number_of_fan_back
                        ]);
                        break;
                    case 'motherboard':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'motherboard',
                            'mb_supported_cpu_brand' => $request->mb_supported_cpu_brand,
                            'mb_cpu_generation' => $request->mb_cpu_generation,
                            'mb_supported_socket' => $request->mb_supported_socket,
                            'mb_form_factor' => $request->mb_form_factor,
                            'mb_supported_memory_type' => $request->mb_supported_memory_type,
                            'mb_default_tdp' => $request->mb_default_tdp,
                            'mb_number_of_ram' => $request->mb_number_of_ram,
                            'mb_xmp_support' => $request->mb_xmp_support,
                            'mb_m2_storage_support' => $request->mb_m2_storage_support,
                            'mb_number_of_m2_support' => $request->mb_number_of_m2_support,
                            'mb_sata_storage_support' => $request->mb_sata_storage_support,
                            'mb_number_of_sta_support' => $request->mb_number_of_sata_support,
                            'mb_lic_support' => $request->mb_lic_support,
                            'mb_pcie_slot' => $request->mb_pcie_slot
                        ]);
                        break;
                    case 'ram':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'ram',
                            'ram_memory_type' => $request->ram_memory_type,
                            'ram_speed' => $request->ram_speed,
                            'ram_xmp_support' => $request->ram_xmp_support,
                            'ram_default_tdp' => $request->ram_default_tdp
                        ]);
                        break;
                    case 'storage':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'storage',
                            'storage_type' => $request->storage_type,
                            'storage_m2_support' => $request->storage_m2_support,
                            'storage_sata_support' => $request->storage_sata_support,
                            'storage_default_tdp' => $request->storage_default_tdp
                        ]);
                        break;
                    case 'graphics-card':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'graphics-card',
                            'gc_supported_pcie_slot' => $request->gc_supported_pcie_slot,
                            'gc_supported_form_factor' => $request->gc_supported_form_factor,
                            'gc_default_tdp' => $request->gc_default_tdp ?? 0,
                        ]);
                        break;
                    case 'cpu_cooler':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'cpu_cooler',
                            'cc_default_tdp' => $request->cc_default_tdp,
                        ]);
                        break;
                    case 'psu':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'psu',
                        ]);
                        break;
                    default:
                        dd($request->all());
                        break;
                }
            }

            if (isset($request->laptop_budget) && $request->laptop_budget != null) {
                ProductBudget::create([
                    'product_id' => $product->id,
                    'budget_id' => $request->laptop_budget
                ]);
            }

            if(isset($request->laptop_purpose) && is_array($request->laptop_purpose) && count($request->laptop_purpose) > 0) {
                foreach($request->laptop_purpose as $purpose_id) {
                    ProductPurpose::create([
                        'product_id' => $product->id,
                        'purpose_id' => $purpose_id
                    ]);
                }
            }

            if (isset($request->laptop_screen_size) && $request->laptop_screen_size != null) {
                ProductScreenSize::create([
                    'product_id' => $product->id,
                    'size_id' => $request->laptop_screen_size
                ]);
            }

            if(isset($request->laptop_portability) && $request->laptop_portability != null) {
                ProductPortability::create([
                    'product_id' => $product->id,
                    'portable_id' => $request->laptop_portability
                ]);
            }

            if (isset($request->laptop_features) && is_array($request->laptop_features) && count($request->laptop_features) > 0) {
                foreach ($request->laptop_features as $feature_id) {
                    ProductFeature::create([
                        'product_id' => $product->id,
                        'feature_id' => $feature_id
                    ]);
                }
            }

            DB::commit();
            $this->product_cache_forget();
        } else {
            DB::rollBack();
        }

        return response()->json(['status' => true, 'message' => 'Product created successfully.', 'load' => false]);
    }

    public function updateProduct($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($id),
            ],
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'images' => 'array',
            'video_provider' => 'nullable|string|max:255',
            'video_link' => 'nullable|string',
            'description' => 'nullable|string',
            'site_title' => 'nullable|string|max:255',
            'product_type' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_article_tags' => 'nullable|string|max:255',
            'meta_script_tags' => 'nullable|string|max:255',
            'is_discounted' => 'required|boolean',
            'minus_stock' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
            'stage' => 'required|string',
            'is_featured' => 'required|boolean',
            'is_returnable' => 'required|boolean',
            'low_stock_quantity' => 'required|integer|min:0',
            'cash_on_delivery' => 'required|boolean',
            'est_shipping_time' => 'nullable|string|max:255',
            'taxes' => 'array',
            'tax_types' => 'array',
            'unit'      => 'required',
            'shipping_cost' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = $request->category_id[count($request->category_id) - 1];
        $product->brand_id = $request->brand_id;
        $product->brand_type_id = $request->brand_type_id;
        $product->product_type = $request->product_type;
        $product->is_discounted = $request->is_discounted;
        $product->minus_stock = isset($request->minus_stock) ? $request->minus_stock : 0;
        $product->discount_type = $request->discount_type;
        $product->discount = isset($request->discount_type) && $request->discount_type != 'amount' ? $request->discount : covert_to_usd($request->discount);
        $product->discount_start_date = $request->discount_start_date == null ? null : date('Y-m-d', strtotime($request->discount_start_date));
        $product->discount_end_date = $request->discount_end_date == null ? null : date('Y-m-d', strtotime($request->discount_end_date));
        $product->status = $request->status;
        $product->stage = $request->stage;
        $product->is_featured = $request->is_featured;
        $product->is_returnable = $request->is_returnable;
        $product->return_deadline = $request->return_deadline;

        if ($request->thumb_image) {
            $product->thumb_image = Images::upload('products', $request->thumb_image);
        }

        $product->save();

        // if the product is saved
        if ($product) {

            // saved the product details
            $details = ProductDetail::where('product_id', $product->id)->first();
            if ($details) {
                $details->video_provider = $request->video_provider;
                $details->video_link = $request->video_link;
                $details->description = $request->description;
                $details->site_title = $request->site_title;
                $details->meta_title = $request->meta_title;
                $details->meta_keyword = $request->meta_keyword;
                $details->meta_description = $request->meta_description;
                $details->meta_article_tags = $request->meta_article_tags;
                $details->meta_script_tags = $request->meta_script_tags;
                $details->low_stock_quantity = $request->low_stock_quantity;
                $details->cash_on_delivery = $request->cash_on_delivery;
                $details->est_shipping_days = $request->est_shipping_time;
                $details->pc_builder_item = $request->add_to_pc_build;
                $details->component_type = $request->component_type;
                $details->peri_component_type = $request->peri_component_type;
                $details->points = $request->points;
                $details->shipping_cost = covert_to_usd($request->shipping_cost);
                $details->unit = $request->unit;
                $details->save();
            }

            // update the tax details
            if (isset($request->tax_id) && count($request->tax_id) > 0) {
                $taxIdArray = $request->tax_id;
                $taxAmountArray = $request->taxes;
                $taxTypeArray = $request->tax_types;

                for ($taxCounter = 0; $taxCounter < count($taxIdArray); $taxCounter++) {
                    $taxModel = ProductTax::find($taxIdArray[$taxCounter]);
                    $taxModel->tax = $taxTypeArray[$taxCounter] == 'flat' ? covert_to_usd($taxAmountArray[$taxCounter]) : $taxAmountArray[$taxCounter];
                    $taxModel->tax_type = $taxTypeArray[$taxCounter] == 'flat' ? 'amount' : 'percent';
                    $taxModel->save();
                }
            }

            // images
            if (isset($request->images)) {
                $this->removeImage($product->id);

                foreach ($request->images as $image) {
                    $product_image = new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->image = Images::upload('products', $image);
                    $product_image->status = 1;
                    $product_image->save();
                }
            }

            if (isset($request->laptop_budget) && $request->laptop_budget != null) {
                ProductBudget::create([
                    'product_id' => $product->id,
                    'budget_id' => $request->laptop_budget
                ]);
            }

            if (isset($request->laptop_purpose) && is_array($request->laptop_purpose) && count($request->laptop_purpose) > 0) {
                foreach ($request->laptop_purpose as $purpose_id) {
                    ProductPurpose::create([
                        'product_id' => $product->id,
                        'purpose_id' => $purpose_id
                    ]);
                }
            }

            if (isset($request->laptop_screen_size) && $request->laptop_screen_size != null) {
                ProductScreenSize::create([
                    'product_id' => $product->id,
                    'size_id' => $request->laptop_screen_size
                ]);
            }

            if (isset($request->laptop_portability) && $request->laptop_portability != null) {
                ProductPortability::create([
                    'product_id' => $product->id,
                    'portable_id' => $request->laptop_portability
                ]);
            }

            if (isset($request->laptop_features) && is_array($request->laptop_features) && count($request->laptop_features) > 0) {
                foreach ($request->laptop_features as $feature_id) {
                    ProductFeature::create([
                        'product_id' => $product->id,
                        'feature_id' => $feature_id
                    ]);
                }
            }

            if (isset($request->add_to_pc_build) && $request->add_to_pc_build == 1 && $request->component_type == 'core') {
                switch ($request->core_component_type) {
                    case 'casing':
                        PcBuilderItem::updateOrCreate(
                            ['product_id' => $product->id],
                            [
                                'comp_type' => 'casing',
                                'casing_form_factor' => $request->casing_form_factor,
                                'casing_psu_installed' => $request->casing_psu_installed,
                                'casing_fan_installed' => $request->casing_fan_installed,
                                'casing_number_of_fan_front' => $request->casing_number_of_fan_front,
                                'casing_number_of_fan_top' => $request->casing_number_of_fan_top,
                                'casing_number_of_fan_back' => $request->casing_number_of_fan_back
                            ]
                        );

                        break;
                    case 'processor':
                        PcBuilderItem::updateOrCreate(
                            ['product_id' => $product->id],
                            [
                                'comp_type' => 'processor',
                                'cpu_brand' => $request->cpu_brand,
                                'cpu_generation' => $request->cpu_generation,
                                'socket_type' => $request->socket_type,
                                'default_tdp' => $request->default_tdp,
                                'casing_number_of_fan_top' => $request->casing_number_of_fan_top,
                                'casing_number_of_fan_back' => $request->casing_number_of_fan_back
                            ]
                        );

                        break;
                    case 'motherboard':
                        PcBuilderItem::updateOrCreate(
                            ['product_id' => $product->id],
                            [
                                'comp_type' => 'motherboard',
                                'mb_supported_cpu_brand' => $request->mb_supported_cpu_brand,
                                'mb_cpu_generation' => $request->mb_cpu_generation,
                                'mb_supported_socket' => $request->mb_supported_socket,
                                'mb_form_factor' => $request->mb_form_factor,
                                'mb_supported_memory_type' => $request->mb_supported_memory_type,
                                'mb_default_tdp' => $request->mb_default_tdp,
                                'mb_number_of_ram' => $request->mb_number_of_ram,
                                'mb_xmp_support' => $request->mb_xmp_support,
                                'mb_m2_storage_support' => $request->mb_m2_storage_support,
                                'mb_number_of_m2_support' => $request->mb_number_of_m2_support,
                                'mb_sata_storage_support' => $request->mb_sata_storage_support,
                                'mb_number_of_sta_support' => $request->mb_number_of_sata_support,
                                'mb_lic_support' => $request->mb_lic_support,
                                'mb_pcie_slot' => $request->mb_pcie_slot
                            ]
                        );

                        break;
                    case 'ram':
                        PcBuilderItem::updateOrCreate(
                            ['product_id' => $product->id],
                            [
                                'comp_type' => 'ram',
                                'ram_memory_type' => $request->ram_memory_type,
                                'ram_speed' => $request->ram_speed,
                                'ram_xmp_support' => $request->ram_xmp_support,
                                'ram_default_tdp' => $request->ram_default_tdp
                            ]
                        );
                        break;
                    case 'storage':
                        PcBuilderItem::updateOrCreate(
                            ['product_id' => $product->id],
                            [
                                'comp_type' => 'storage',
                                'storage_type' => $request->storage_type,
                                'storage_m2_support' => $request->storage_m2_support,
                                'storage_sata_support' => $request->storage_sata_support,
                                'storage_default_tdp' => $request->storage_default_tdp
                            ]
                        );
                        break;
                    case 'graphics-card':

                        PcBuilderItem::updateOrCreate(
                            ['product_id' => $product->id],
                            [
                                'comp_type' => 'graphics-card',
                                'gc_supported_pcie_slot' => $request->gc_supported_pcie_slot,
                                'gc_supported_form_factor' => $request->gc_supported_form_factor,
                                'gc_default_tdp' => $request->gc_default_tdp,
                            ]
                        );
                        break;
                    case 'cpu_cooler':
                        PcBuilderItem::updateOrCreate(
                            ['product_id' => $product->id],
                            [
                                'comp_type' => 'cpu_cooler',
                                'cc_default_tdp' => $request->cc_default_tdp,
                            ]
                        );
                        break;
                    case 'psu':
                        PcBuilderItem::create([
                            'product_id' => $product->id,
                            'comp_type' => 'psu',
                        ]);
                        break;
                    default:
                        dd($request->all());
                        break;
                }
            }
        }

        return response()->json(['status' => true, 'message' => 'Product updated successfully.', 'goto' => route('admin.product.index')]);
    }

    private function removeImage($productId)
    {
        return ProductImage::where('product_id', $productId)->delete();
    }

    public function duplicateProduct($request, $id)
    {

        // Find the original product
        $originalProduct = Product::with(['details', 'taxes', 'purchase', 'stock', 'image', 'specifications'])->find($id);

        // Check if the product was found
        if (!$originalProduct) {
            return response()->json(['status' => false, 'message' => 'Product not found'], 404);
        }

        DB::beginTransaction();

        // Duplicate the product
        $newProduct = Product::create([
            'admin_id' => Auth::guard('admin')->id(),
            'category_id' => $originalProduct->category_id,
            'brand_id' => $originalProduct->brand_id,
            'brand_type_id' => $originalProduct->brand_type_id,
            'name' => $originalProduct->name,
            'slug' => $originalProduct->slug . '-' . rand(100, 1000),
            'skq' => $this->generateSku($originalProduct->category_id),
            'unit_price' => $originalProduct->unit_price,
            'status' => $originalProduct->status,
            'in_stock' => $originalProduct->in_stock,
            'is_featured' => $originalProduct->is_featured,
            'low_stock' => $originalProduct->low_stock,
            'is_discounted' => $originalProduct->is_discounted,
            'discount_type' => $originalProduct->discount_type,
            'discount' => $originalProduct->discount,
            'discount_start_date' => $originalProduct->discount_start_date,
            'discount_end_date' => $originalProduct->discount_end_date,
            'is_returnable' => $originalProduct->is_returnable,
            'return_deadline' => $originalProduct->return_deadline,
            'stock_types' => $originalProduct->stock_types,
            'thumb_image' => $originalProduct->thumb_image
        ]);

        if ($newProduct) {

            // Duplicate ProductDetail
            if ($originalProduct->details) {
                ProductDetail::create([
                    'product_id' => $newProduct->id,
                    'video_provider' => $originalProduct->details->video_provider,
                    'video_link' => $originalProduct->details->video_link,
                    'description' => $originalProduct->details->description,
                    'current_stock' => 0,
                    'low_stock_quantity' => 0,
                    'cash_on_delivery' => $originalProduct->details->cash_on_delivery,
                    'est_shipping_days' => $originalProduct->details->est_shipping_days,
                    'number_of_sale' => 0,
                    'average_rating' => 0,
                    'number_of_rating' => 0,
                    'average_purchase_price' => 0,
                    'site_title' => $originalProduct->details->site_title,
                    'meta_title' => $originalProduct->details->meta_title,
                    'meta_keyword' => $originalProduct->details->meta_keyword,
                    'meta_description' => $originalProduct->details->meta_description,
                    'meta_article_tags' => $originalProduct->details->meta_article_tags,
                    'meta_script_tags' => $originalProduct->details->meta_script_tags
                ]);
            }

            if ($originalProduct->image && $request->product_images) {
                foreach ($originalProduct->image as $image) {
                    $newImage = new ProductImage();
                    $newImage->product_id = $newProduct->id;
                    $newImage->image = $this->copyImage($image->image);
                    $newImage->status = $image->status;
                    $newImage->save();
                }
            }

            // Duplicate ProductSpecification
            if ($originalProduct->specifications && $request->product_specifications) {
                foreach ($originalProduct->specifications as $specification) {
                    ProductSpecification::create([
                        'product_id' => $newProduct->id,
                        'key_id' => $specification->key_id,
                        'type_id' => $specification->type_id,
                        'attribute_id' => $specification->attribute_id,
                        'key_feature' => $specification->key_feature
                    ]);
                }
            }

            // Duplicate ProductTax
            if ($originalProduct->taxes && $request->product_taxes) {
                foreach ($originalProduct->taxes as $tax) {
                    ProductTax::create([
                        'product_id' => $newProduct->id,
                        'tax_id' => $tax->id,
                        'tax' => $tax->tax,
                        'tax_type' => $tax->tax_type,
                    ]);
                }
            }

            // if($request->stock_purchase) {
            //     // Duplicate StockPurchase
            //     if($originalProduct->purchase) {
            //         foreach ($originalProduct->purchase as $purchase) {

            //             $newStockPurchase = StockPurchase::create([
            //                 'product_id' => $newProduct->id,
            //                 'admin_id' => Auth::guard('admin')->id(),
            //                 'currency_id' => $purchase->currency_id,
            //                 'sku' => $purchase->sku,
            //                 'quantity' => $purchase->quantity,
            //                 'unit_price' => $purchase->unit_price,
            //                 'purchase_unit_price' => $purchase->purchase_unit_price,
            //                 'purchase_total_price' => $purchase->purchase_total_price,
            //                 'file' => $purchase->file,
            //                 'is_sellable' => $purchase->is_sellable,
            //             ]);

            //             // Duplicate ProductStock
            //             if($newStockPurchase && $purchase->stocks) {
            //                 $new_stock_purchase_id = $newStockPurchase->id;
            //                 foreach($purchase->stocks as $stock) {
            //                     if($newStockPurchase->id) {
            //                         ProductStock::create([
            //                             'product_id' => $newProduct->id,
            //                             'stock_purchase_id' => $new_stock_purchase_id,
            //                             'zone_id' => $stock->zone_id,
            //                             'country_id' => $stock->country_id,
            //                             'city_id' => $stock->city_id,
            //                             'in_stock' => $stock->in_stock,
            //                             'number_of_sale' => $stock->number_of_sale,
            //                             'stock' => $stock->stock,
            //                         ]);
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // }

            DB::commit();
            $this->product_cache_forget();
        } else {
            DB::rollback();
        }

        return response()->json(['status' => true, 'message' => 'Product duplication successfully.', 'load' => true]);
    }

    private function generateSku($CiD)
    {
        $rand = rand(1000, 99999);

        if ($CiD > 99) {
            return $CiD . strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 3)) . rand(1000, 9999);
        } elseif ($rand > 9999) {
            return $CiD . strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 3)) . $rand;
        } else {
            return $CiD . $rand . strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 3));
        }
    }

    private function copyImage($imagePath)
    {
        $extension = File::extension($imagePath);

        $newFilename = Str::random(10) . "-copy." . $extension;

        $newFilePath = 'storage/images/products/' . $newFilename;

        if (File::exists($imagePath)) {
            File::copy($imagePath, $newFilePath);
        } else {
            return null;
        }

        return $newFilePath;
    }

    public function quickview($slug)
    {
        $product = Product::where('slug', $slug)->with([
            'details:id,product_id,description,current_stock,low_stock_quantity,cash_on_delivery,number_of_sale',
            'category:id,name,slug',
            'brand:id,name,slug',
            'image' => function ($query) {
                $query->select('id', 'product_id', 'image')->where('status', 1);
            },
            'specifications' => function ($query) {
                $query->where('key_feature', 1)
                    ->with([
                        'specificationKeyType:id,name,position',
                        'specificationKeyTypeAttribute:id,name,extra'
                    ])->join('specification_key_types', 'product_specifications.type_id', '=', 'specification_key_types.id')
                    ->orderBy('specification_key_types.position', 'ASC');
            },
            'ratings' => function ($query) {
                $query->select('product_id', DB::raw('AVG(rating) as averageRating'))
                    ->groupBy('product_id');
            }
        ])->withCount('ratings')->first(['id', 'category_id', 'brand_id', 'name', 'thumb_image', 'sku', 'slug', 'unit_price', 'is_returnable', 'return_deadline', 'is_discounted', 'discount', 'discount_type']);


        $discountedPrice = $product->unit_price;
        if ($product->is_discounted && $product->discount > 0) {
            $discountAmount = $product->discount_type == 'amount'
                ? $product->discount
                : ($product->unit_price * ($product->discount / 100));

            $discountedPrice -= $discountAmount;
        }

        $averageRating = $product->ratings->isNotEmpty() ? $product->ratings->first()->averageRating : null;
        $averageRatingPercentage = $averageRating !== null ? ($averageRating / 5) * 100 : 0;

        $productDetails = [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'category_name' => $product->category->name,
            'category_slug' => $product->category->slug,
            'brand_id' => $product->brand_id,
            'brand_name' => $product->brand->name,
            'brand_slug' => $product->brand->slug,
            'name' => $product->name,
            'thumb_image' => $product->thumb_image,
            'sku' => $product->sku,
            'slug' => $product->slug,
            'description' => $product->details->description ?? '',
            'price' => $product->unit_price,
            'return_deadline' => $product->is_returnable ? $product->return_deadline : 0,
            'ratings_count' => $product->ratings_count,
            'average_rating' => $averageRatingPercentage,
            'discount' => $product->is_discounted ? $product->discount : 0,
            'discount_type' => $product->discount_type,
            'discounted_price' => $discountedPrice,
            'current_stock' => $product->details->current_stock ?? 0,
            'is_low_stock' => isset($product->details) && $product->details->current_stock <= $product->details->low_stock_quantity,
            'is_COD_available' => $product->details->cash_on_delivery ?? false,
            'total_sold' => $product->details->number_of_sale ?? 0,
            'images' => $product->image,
            'key_features' => []
        ];

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

        return $productDetails;
    }



    public function userQuotes()
    {
        return ProductQuestion::where('user_id', Auth::guard('customer')->id())->with('product', 'answer')->latest()->paginate(5);
    }

    private function product_cache_forget()
    {
        Cache::forget('homeProducts_');
        Cache::forget('on_sale_products_');
        Cache::forget('on_sale_products_');
        Cache::forget('trending_');
        Cache::forget('featured_products_');
        Cache::forget('top_rated_product');
        Cache::forget('trending_');
        Cache::forget('newProducts');
        Cache::forget('homeProducts_best_seller');
        Cache::forget('homeProducts_featured');
        Cache::forget('homeProducts_offred');
    }

    public function specificationproducts()
    {
        $data = Product::withCount('specifications')
            ->with('admin:id,name')
            ->orderBy('specifications_count', 'desc')
            ->latest()
            ->get();
        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'thumb_image' => $item->thumb_image,
                'specifications_count' => $item->specifications_count,
                'status' => $item->status,
                'is_featured' => $item->is_featured,
                'created_by' => $item->admin ? $item->admin->name : null,
            ];
        });
    }

    public function specificationproductsDatatable()
    {
        $models = $this->specificationproducts();
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('name', function ($model) {
                return '<div class="row"><div class="col-auto">' . Images::show($model['thumb_image']) . '</div><div class="col">' . $model['name'] . '</div></div>';
            })
            ->editColumn('created_by', function ($model) {
                return $model['created_by'];
            })
            ->editColumn('status', function ($model) {
                $checked = $model['status'] == 'active' ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.product.status', $model['id']) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model['id'] . '" ' . $checked . ' data-id="' . $model['id'] . '"></div>';
            })
            ->editColumn('is_featured', function ($model) {
                $is_featured = $model['is_featured'] == 1 ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.product.featured', $model['id']) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model['id'] . '" ' . $is_featured . ' data-id="' . $model['id'] . '"></div>';
            })
            ->editColumn('specifications_count', function ($model) {
                return "<div class='w-100 text-center'>
                            <span class='badge bg-dark rounded-pill' style='padding: 10px 20px;'>
                                " . $model['specifications_count'] . "
                            </span>
                        </div>";
            })
            ->addColumn('action', function ($model) {
                return view('backend.product.specification.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'created_by', 'is_featured', 'name', 'specifications_count'])
            ->make(true);
    }

    public function specificationproductModal($id)
    {
        $data = ProductSpecification::where('product_id', $id)->with('product:id,category_id,name', 'specificationKey', 'specificationKeyType', 'specificationKeyTypeAttribute')->get();
        if ($data->isEmpty()) {
            $product = Product::find($id);
            $product_name = isset($product) ? $product->name : null;
            $category_id = isset($product) ? $product->category_id : null;
        } else {

            $product_name = isset($data) ? $data[0]->product->name : null;
            $category_id = isset($data) ? $data[0]->product->category_id : null;
        }
        $product_id = $id;


        $mapped = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'key_id' => isset($item->key_id) ? $item->key_id : null,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'specificationKey' => $item->specificationKey ? $item->specificationKey->name : null,
                'specificationKeyType' => $item->specificationKeyType ? $item->specificationKeyType->name : null,
                'specificationKeyTypeAttribute' => $item->specificationKeyTypeAttribute ? $item->specificationKeyTypeAttribute->name . ' ' . $item->specificationKeyTypeAttribute->extra : null,
                'key_feature' => $item->key_feature,
            ];
        });
        $models = $mapped->groupBy('key_id');

        return view('backend.product.specification.modal', compact('models', 'product_name', 'category_id', 'product_id'));
    }

    public function specificationProductPage($id)
    {
        $data = ProductSpecification::where('product_id', $id)->with('product:id,category_id,name', 'specificationKey', 'specificationKeyType', 'specificationKeyTypeAttribute')->get();

        $product = Product::find($id);

        $keys = SpecificationKey::where('status', 1)->where('category_id', $product->category_id)->orWhere('is_public', 1)->orderBy('position', 'ASC')->get();

        $mapped = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'key_id' => $item->key_id ?? null,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'specificationKeyId' => $item->specificationKey ? $item->specificationKey->id : null,
                'specificationKey' => $item->specificationKey ? $item->specificationKey->name : null,
                'specificationKeyTypeId' => $item->specificationKeyType ? $item->specificationKeyType->id : null,
                'specificationKeyType' => $item->specificationKeyType ? $item->specificationKeyType->name : null,
                'specificationKeyTypeAttributeId' => $item->specificationKeyTypeAttribute ? $item->specificationKeyTypeAttribute->id : null,
                'specificationKeyTypeAttribute' => $item->specificationKeyTypeAttribute ? $item->specificationKeyTypeAttribute->name . ' ' . $item->specificationKeyTypeAttribute->extra : null,
                'key_feature' => $item->key_feature,
            ];
        });

        $models = $mapped->groupBy('key_id');
        $product_name = $product->name;
        $category_id = $product->category_id;
        $product_id = $product->id;

        return view('backend.product.specification.page', compact('models', 'keys', 'product_name', 'category_id', 'product_id'));
    }

    public function specificationProduct($id)
    {
        $data = ProductSpecification::where('product_id', $id)->with('product:id,category_id,name', 'specificationKey', 'specificationKeyType', 'specificationKeyTypeAttribute')->get();
        if ($data->isEmpty()) {
            $product = Product::find($id);
            $product_name = isset($product) ? $product->name : null;
            $category_id = isset($product) ? $product->category_id : null;
        } else {

            $product_name = isset($data) ? $data[0]->product->name : null;
            $category_id = isset($data) ? $data[0]->product->category_id : null;
        }
        $product_id = $id;


        $mapped = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'key_id' => $item->key_id ?? null,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'specificationKey' => $item->specificationKey ? $item->specificationKey->name : null,
                'specificationKeyType' => $item->specificationKeyType ? $item->specificationKeyType->name : null,
                'specificationKeyTypeAttribute' => $item->specificationKeyTypeAttribute ? $item->specificationKeyTypeAttribute->name . ' ' . $item->specificationKeyTypeAttribute->extra : null,
                'key_feature' => $item->key_feature,
            ];
        });

        return $mapped->groupBy('key_id');
    }

    public function specificationKeyFeaturedProduct($id)
    {
        $data = ProductSpecification::where('product_id', $id)->where('key_feature', 1)->with('product:id,category_id,name', 'specificationKey', 'specificationKeyType', 'specificationKeyTypeAttribute')->get();
        if ($data->isEmpty()) {
            $product = Product::find($id);
            $product_name = isset($product) ? $product->name : null;
            $category_id = isset($product) ? $product->category_id : null;
        } else {

            $product_name = isset($data) ? $data[0]->product->name : null;
            $category_id = isset($data) ? $data[0]->product->category_id : null;
        }
        $product_id = $id;


        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'key' => $item->specificationKeyType ? $item->specificationKeyType->name : null,
                'attribute' => $item->specificationKeyTypeAttribute ? $item->specificationKeyTypeAttribute->name . ' ' . $item->specificationKeyTypeAttribute->extra : null,
            ];
        });
    }

    public function specificationsAdd($request, $id)
    {

        if (isset($request->specification_key)) {

            foreach ($request->specification_key as $specification) {
                $keyId = $specification['key_id'];
                $processedTypeIds = []; // Track unique type_ids

                // Loop through type_id
                foreach ($specification['type_id'] as $typeId => $value) {

                    if (is_numeric($typeId)) {
                        $tkey = $value;
                    }
                    if (!is_numeric($typeId)) {
                        continue; // Skip features and attributes keys
                    }

                    // Skip if this type_id has already been processed
                    if (in_array($typeId, $processedTypeIds)) {
                        continue;
                    }

                    // Initialize variables
                    $firstAttributeId = null;
                    $featuresExist = false;

                    // Check for attributes
                    if (
                        isset($specification['type_id']['attribute_id'][$value])
                        && is_array($specification['type_id']['attribute_id'][$value])
                        && !empty($specification['type_id']['attribute_id'][$value])
                    ) {

                        // Get the first attribute ID
                        $firstAttributeId = $specification['type_id']['attribute_id'][$value][0]; // First attribute
                    }

                    // Check for features
                    if (isset($specification['type_id']['features'][$value])) {
                        $featuresExist = true; // Set to true if features exist
                    }

                    // Create a ProductSpecification entry only if we have an attribute ID
                    if ($firstAttributeId !== null) {
                        $productSpecification = new ProductSpecification();
                        $productSpecification->product_id = $id;
                        $productSpecification->key_id = $keyId;
                        $productSpecification->type_id = intval($tkey);
                        $productSpecification->attribute_id = intval($firstAttributeId);

                        $productSpecification->key_feature = $featuresExist ? 1 : 0;

                        $productSpecification->save();
                    }

                    // Mark this type_id as processed
                    $processedTypeIds[] = $typeId;
                }
            }
            return response()->json(['status' => true, 'load' => true, 'message' => 'Product Specifications Created successfully.']);
        }
        return response()->json(['status' => false, 'message' => 'No Specificatiions Posted.']);
    }

    public function delete($id)
    {
        $specification = ProductSpecification::findOrFail($id);
        $specification->delete();
        return response()->json(['status' => true, 'message' => 'Product Specification Deleted successfully.']);
    }

    public function keyfeature($id)
    {

        $specification = ProductSpecification::find($id);

        if (!$specification) {
            return response()->json(['success' => false, 'message' => 'Product Specification not found.'], 404);
        }

        $specification->key_feature = !$specification->key_feature;
        $specification->save();

        return response()->json(['success' => true, 'message' => 'Product Specification featured status updated successfully.']);
    }
}

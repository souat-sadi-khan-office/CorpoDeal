<?php 

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Repositories\Interface\ProductRepositoryInterface;

class SearchApiController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function ajaxSearch(Request $request)
    {
        $query = $request->input('search');

        $request->merge(['search_module' => 'ajax_search']);

        $products = $this->productRepository->index($request, null);

        $categories = Category::where('name', 'like', '%' . $query . '%')->take(3)->get();
        $brands = Brand::where('name', 'like', '%' . $query . '%')->take(3)->get();

        return response()->json([
            'status'     => true,
            'products'   => $products,
            'categories' => $categories,
            'brands'     => $brands,
        ]);
    }
}

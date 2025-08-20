<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\BrandRepositoryInterface;

class BrandController extends Controller
{
    private $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->brandRepository->dataTable();
        }

        return view('backend.brands.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.brands.create');
    }

    public function store(BrandRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->brandRepository->createBrand($request);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->brandRepository->findBrandById($id);
        return view('backend.brands.edit', compact('model'));
    }

    public function update(BrandRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->brandRepository->updateBrand($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->brandRepository->deleteBrand($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Brand deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->brandRepository->updateStatus($request, $id);
    }
    
    public function updateFeatured(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->brandRepository->updateFeatured($request, $id);
    }
}

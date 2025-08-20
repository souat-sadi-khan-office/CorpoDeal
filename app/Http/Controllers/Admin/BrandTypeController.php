<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\BrandTypeRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\BrandTypeRepositoryInterface;

class BrandTypeController extends Controller
{
    private $brandTypeRepository;

    public function __construct(BrandTypeRepositoryInterface $brandTypeRepository)
    {
        $this->brandTypeRepository = $brandTypeRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }
                
        if ($request->ajax()) {

            return $this->brandTypeRepository->dataTable();
        }

        return view('backend.brand-type.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $brands = Brand::where('status', 1)->orderBy('name', 'ASC')->get();
        return view('backend.brand-type.create', compact('brands'));
    }

    public function store(BrandTypeRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->brandTypeRepository->createBrandType($request);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $brands = Brand::where('status', 1)->orderBy('name', 'ASC')->get();
        $model = $this->brandTypeRepository->findBrandTypeById($id);
        return view('backend.brand-type.edit', compact('model', 'brands'));
    }

    public function update(BrandTypeRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->brandTypeRepository->updateBrandType($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->brandTypeRepository->deleteBrandType($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Brand deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->brandTypeRepository->updateStatus($request, $id);
    }

    public function updateFeatured(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('brand-type.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->brandTypeRepository->updateFeatured($request, $id);
    }
}

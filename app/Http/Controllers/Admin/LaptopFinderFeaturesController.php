<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LaptopFinderFeaturesRequest;
use App\Repositories\Interface\LaptopFinderFeaturesRepositoryInterface;

class LaptopFinderFeaturesController extends Controller
{
    private $laptopFinderFeaturesRepository;

    public function __construct(
        LaptopFinderFeaturesRepositoryInterface $laptopFinderFeaturesRepository,
    ) {
        $this->laptopFinderFeaturesRepository = $laptopFinderFeaturesRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.features') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->laptopFinderFeaturesRepository->dataTable();
        }

        return view('backend.laptop-finder.features.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.features') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.laptop-finder.features.create');
    }

    public function store(LaptopFinderFeaturesRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.features') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->laptopFinderFeaturesRepository->create($request);
    }

    public function show()
    {
        
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.features') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->laptopFinderFeaturesRepository->find($id);
        return view('backend.laptop-finder.features.edit', compact('model'));
    }

    public function update(LaptopFinderFeaturesRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.features') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopFinderFeaturesRepository->update($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.features') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->laptopFinderFeaturesRepository->delete($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Record deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.features') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopFinderFeaturesRepository->updateStatus($request, $id);
    }
}

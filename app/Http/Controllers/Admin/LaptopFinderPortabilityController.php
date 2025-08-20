<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LaptopFinderPortabilityRequest;
use App\Repositories\Interface\LaptopFinderPortabilityRepositoryInterface;

class LaptopFinderPortabilityController extends Controller
{
    private $laptopFinderPortabilityRepository;

    public function __construct(
        LaptopFinderPortabilityRepositoryInterface $laptopFinderPortabilityRepository,
    ) {
        $this->laptopFinderPortabilityRepository = $laptopFinderPortabilityRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.portability') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->laptopFinderPortabilityRepository->dataTable();
        }

        return view('backend.laptop-finder.portable.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.portability') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.laptop-finder.portable.create');
    }

    public function store(LaptopFinderPortabilityRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.portability') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->laptopFinderPortabilityRepository->create($request);
    }

    public function show()
    {
        
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.portability') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->laptopFinderPortabilityRepository->find($id);
        return view('backend.laptop-finder.portable.edit', compact('model'));
    }

    public function update(LaptopFinderPortabilityRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.portability') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopFinderPortabilityRepository->update($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.portability') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->laptopFinderPortabilityRepository->delete($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Record deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.portability') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopFinderPortabilityRepository->updateStatus($request, $id);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LaptopFinderScreenRequest;
use App\Repositories\Interface\LaptopFinderScreenRepositoryInterface;

class LaptopFinderScreenController extends Controller
{
    private $laptopFinderScreenRepository;

    public function __construct(
        LaptopFinderScreenRepositoryInterface $laptopFinderScreenRepository,
    ) {
        $this->laptopFinderScreenRepository = $laptopFinderScreenRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }
        
        if ($request->ajax()) {

            return $this->laptopFinderScreenRepository->dataTable();
        }

        return view('backend.laptop-finder.screen.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.laptop-finder.screen.create');
    }

    public function store(LaptopFinderScreenRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopFinderScreenRepository->create($request);
    }

    public function show()
    {
        
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->laptopFinderScreenRepository->find($id);
        return view('backend.laptop-finder.screen.edit', compact('model'));
    }

    public function update(LaptopFinderScreenRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopFinderScreenRepository->update($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->laptopFinderScreenRepository->delete($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Record deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopFinderScreenRepository->updateStatus($request, $id);
    }
}

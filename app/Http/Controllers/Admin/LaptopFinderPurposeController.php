<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LaptopFinderPurposeRequest;
use App\Repositories\Interface\LaptopFinderPurposeRepositoryInterface;

class LaptopFinderPurposeController extends Controller
{
    private $LaptopFinderPurposeRepository;

    public function __construct(
        LaptopFinderPurposeRepositoryInterface $LaptopFinderPurposeRepository,
    ) {
        $this->LaptopFinderPurposeRepository = $LaptopFinderPurposeRepository;
    }

    public function index(Request $request)
    {

        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->LaptopFinderPurposeRepository->dataTable();
        }

        return view('backend.laptop-finder.purpose.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.laptop-finder.purpose.create');
    }

    public function store(LaptopFinderPurposeRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->LaptopFinderPurposeRepository->create($request);
    }

    public function show()
    {
        
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->LaptopFinderPurposeRepository->find($id);
        return view('backend.laptop-finder.purpose.edit', compact('model'));
    }

    public function update(LaptopFinderPurposeRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->LaptopFinderPurposeRepository->update($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->LaptopFinderPurposeRepository->delete($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Record deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->LaptopFinderPurposeRepository->updateStatus($request, $id);
    }
}

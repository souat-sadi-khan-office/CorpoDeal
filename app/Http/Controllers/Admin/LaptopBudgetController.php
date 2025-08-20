<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LapTopFinderBudgetRequest;
use App\Repositories\Interface\LaptopBudgetRepositoryInterface;

class LaptopBudgetController extends Controller
{
    private $laptopBudgetRepository;

    public function __construct(
        LaptopBudgetRepositoryInterface $laptopBudgetRepository,
    ) {
        $this->laptopBudgetRepository = $laptopBudgetRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.budget') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->laptopBudgetRepository->dataTable();
        }

        return view('backend.laptop-finder.budget.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.budget') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.laptop-finder.budget.create');
    }

    public function store(LapTopFinderBudgetRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.budget') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->laptopBudgetRepository->create($request);
    }

    public function show()
    {
        
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.budget') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->laptopBudgetRepository->find($id);
        return view('backend.laptop-finder.budget.edit', compact('model'));
    }

    public function update(LapTopFinderBudgetRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.budget') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopBudgetRepository->update($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.budget') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->laptopBudgetRepository->delete($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Record deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('laptop-finder.budget') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->laptopBudgetRepository->updateStatus($request, $id);
    }
}

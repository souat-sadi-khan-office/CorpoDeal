<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PageRequest;
use App\Repositories\Interface\PageRepositoryInterface;

class PageController extends Controller
{
    private $pagedRepository;

    public function __construct(PageRepositoryInterface $pagedRepository)
    {
        $this->pagedRepository = $pagedRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.page-management') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->pagedRepository->dataTable();
        }

        return view('backend.page.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.page-management') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $pages = $this->pagedRepository->getAllPages()->where('status', 1);

        return view('backend.page.create', compact('pages'));
    }

    public function store(PageRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.page-management') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->pagedRepository->createPage($request);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.page-management') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->pagedRepository->findPageById($id);
        $pages = $this->pagedRepository->getAllPages()->where('status', 1);
        return view('backend.page.edit', compact('model', 'pages'));
    }

    public function update(PageRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.page-management') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->pagedRepository->updatePage($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.page-management') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->pagedRepository->deletePage($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Page deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.page-management') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->pagedRepository->updateStatus($request, $id);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\CategoryBannerRepositoryInterface;

class CategoryBannerController extends Controller
{
    private $repo;

    public function __construct(CategoryBannerRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->repo->dataTable();
        }

        return view('backend.category-picture.index');
    }

    public function create()
    {
        return view('backend.category-picture.create');
    }

    public function store(Request $request)
    {
        return $this->repo->store($request);
    }

    public function edit($id)
    {
        $model = $this->repo->find($id);
        return view('backend.category-picture.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        return $this->repo->update($id, $request);
    }

    public function destroy($id)
    {
        $this->repo->destroy($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Record deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        return $this->repo->updateStatus($request, $id);
    }
}

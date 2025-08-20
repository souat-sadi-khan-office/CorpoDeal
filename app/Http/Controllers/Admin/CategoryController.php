<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\CPU\Helpers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\ProductSpecificationRepositoryInterface;

class CategoryController extends Controller
{
   protected $categoryRepository;
   protected $key;

   public function __construct(
      CategoryRepositoryInterface $categoryRepository,
      ProductSpecificationRepositoryInterface $key    
   ) {
      $this->key = $key;
      $this->categoryRepository = $categoryRepository;
   }

   public function categoryKeys($categoryId)
   {
      $category = $this->categoryRepository->getCategoryById($categoryId);
      if(!$category) {
         return redirect()->back();
      }

      $publicKeys = $this->key->getOnlyPublicKey();
      $ids = $this->categoryRepository->getParentCategoryIds($categoryId);
      $keys = $this->key->allKeysIncludingParent($ids);
      
      return view('backend.category.keys', compact('category', 'publicKeys', 'keys'));
   }

   public function store(Request $request)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.create') === false) {
         return response()->json([
            'status' => false, 
            'goto' => route('admin.dashboard'),
            'message' => "You don\'t have permission"
         ]);
      }

      return $this->categoryRepository->store($request);
   }

   public function addform()
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.create') === false) {
         return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
      }

      return view('backend.category.add');
   }

   public function addformsub()
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.create') === false) {
         return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
      }
      $categories = $this->categoryRepository->categoriesDropDown(null);
      return view('backend.category.sub.add', compact('categories'));
   }

   public function updateisFeatured(Request $request, $id)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.update') === false) {
         return response()->json([
            'status' => false, 
            'goto' => route('admin.dashboard'),
            'message' => "You don\'t have permission"
         ]);
      }

      return $this->categoryRepository->updateisFeatured($request, $id);
   }

   public function updatestatus(Request $request, $id)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.update') === false) {
         return response()->json([
            'status' => false, 
            'goto' => route('admin.dashboard'),
            'message' => "You don\'t have permission"
         ]);
      }

      return $this->categoryRepository->updatestatus($request, $id);
   }
   public function update(Request $request, $id)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.update') === false) {
         return response()->json([
            'status' => false, 
            'goto' => route('admin.dashboard'),
            'message' => "You don\'t have permission"
         ]);
      }

      return $this->categoryRepository->update($request, $id);
   }

   public function edit(Request $request, $id)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.update') === false) {
         return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
      }

      $category = $this->categoryRepository->edit($id);
      if ($request->has('sub')) {
         $parents = $this->categoryRepository->categoriesDropDown(null);
         return view('backend.category.sub.edit', compact('category', 'parents'));
      }
      return view('backend.category.edit', compact('category'));
   }


   public function index(Request $request)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.view') === false) {
         return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
      }

      $models = $this->categoryRepository->index();
      $view = $this->categoryRepository->view($models);

      if ($request->ajax()) {
         return $view;
      }
      return view('backend.category.index');
   }

   public function indexsub(Request $request)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.view') === false) {
         return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
      }

      $models = $this->categoryRepository->index2();
      $view = $this->categoryRepository->view($models);

      if ($request->ajax()) {
         return $view;
      }
      return view('backend.category.sub.index');
   }

   public function delete($id)
   {
      if (auth()->guard('admin')->user()->hasPermissionTo('category.delete') === false) {
         return response()->json([
            'status' => false, 
            'goto' => route('admin.dashboard'),
            'message' => "You don\'t have permission"
         ]);
      }

      $this->categoryRepository->delete($id);

      return response()->json([
         'status' => true, 
         'load' => true,
         'message' => "Deleted successfully"
      ]);
   }
}
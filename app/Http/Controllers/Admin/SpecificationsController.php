<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SpecificationKey;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\ProductSpecificationRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SpecificationsController extends Controller
{

    private $productSpecificationRepository;

    public function __construct(ProductSpecificationRepositoryInterface $productSpecificationRepository)
    {
        $this->productSpecificationRepository = $productSpecificationRepository;
    }

    public function update(Request $request, $categoryId) 
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|array',
            'position' => 'required|array',
            'status' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }

        if(count($request->id) > 0) {
            foreach ($request->id as $i => $id) {
                if ($id) {
                    SpecificationKey::updateOrCreate(
                        ['id' => $id],
                        [
                            'name' => $request->name[$i], 
                            'status' => $request->status[$i],
                            'position' => $request->position[$i]
                        ]
                    );
                } else {
                    SpecificationKey::create([
                        'name' => $request->name[$i],
                        'status' => $request->status[$i],
                        'position' => $request->position[$i],
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'category_id' => $categoryId
                    ]);
                }
            }
        }

        $remove_ids = $request->remove_ids;
        if($remove_ids != '') {
            $idArray = explode(',', $remove_ids);
            if(count($idArray) > 0) {
                foreach($idArray as $idItem) {
                    $spec = SpecificationKey::find($idItem);
                    if($spec) {
                        $spec->delete();
                    }
                }
            }
        }

        return response()->json(['success' => true, 'status' => true, 'load' => true, 'message' => 'Keys updated successfully.']);
    }

    public function keyTypes($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        // Get the key
        $key = $this->productSpecificationRepository->getTypeById($id);
        if(!$key) {
            return redirect()->back();
        }

        $types = $this->productSpecificationRepository->types($id);
        
        return view('backend.category.specificationKeys.types.custom', compact('key', 'types'));
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $categories = $this->productSpecificationRepository->index();
        $categories = $categories->filter(function ($category) {
            return $category->specification_keys_count > 0; // Check the count
        });
        $view = $this->productSpecificationRepository->indexview($categories);
        if ($request->ajax()) {
            return $view;
        }
        return view('backend.category.specificationKeys.index');
    }
    public function publickeys()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $models=SpecificationKey::where('is_public',1)->with('admin')->orderBy('position')->paginate(20);

        return view('backend.category.specificationKeys.publicKeys',compact('models'));
    }
    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $categories = $this->productSpecificationRepository->index();
        return view('backend.category.specificationKeys.create',compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->store($request);
    }

    public function show(Request $request,$id)
    {
        $models=SpecificationKey::where('category_id',$id)->with('admin')->orderBy('position')->get();

        return view('backend.category.specificationKeys.keysModal',compact('models'));
    }

    public function updatestatus($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->updatestatus($id);
    }
   
    public function updateIsPublic($id){
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->updateIsPublic($id);
    }

    public function updateposition(Request $request,$id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->updateposition($request,$id);
    }
   
    public function delete($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-key.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->delete($id);
    }
}
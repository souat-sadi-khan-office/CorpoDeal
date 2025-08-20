<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SpecificationKeyTypeAttribute;
use App\Repositories\Interface\ProductSpecificationRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SpecificationAttributes extends SpecificationsTypes
{
    private $productSpecificationRepository;

    public function __construct(ProductSpecificationRepositoryInterface $productSpecificationRepository)
    {
        $this->productSpecificationRepository = $productSpecificationRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $keys = $this->productSpecificationRepository->attributeindex();
       
        $view = $this->productSpecificationRepository->attributeindexview($keys);

        if ($request->ajax()) {
            return $view;
        }
        return view('backend.category.specificationKeys.types.attributes.index');
    }

    public function updateAttributes(Request $request, $keyId) 
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|array',
            'extra' => 'nullable|array',
            'status' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }

        if(count($request->id) > 0) {
            foreach ($request->id as $i => $id) {
                if ($id) {
                    SpecificationKeyTypeAttribute::updateOrCreate(
                        ['id' => $id],
                        [
                            'name' => $request->name[$i], 
                            'status' => $request->status[$i],
                            'extra' => $request->extra[$i],
                        ]
                    );
                } else {
                    SpecificationKeyTypeAttribute::create([
                        'name' => $request->name[$i],
                        'status' => $request->status[$i],
                        'extra' => $request->extra[$i],
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'key_type_id' => $keyId,
                    ]);
                }
            }
        }

        $remove_ids = $request->remove_ids;
        if($remove_ids != '') {
            $idArray = explode(',', $remove_ids);
            if(count($idArray) > 0) {
                foreach($idArray as $idItem) {
                    $spec = SpecificationKeyTypeAttribute::find($idItem);
                    if($spec) {
                        $spec->delete();
                    }
                }
            }
        }

        return response()->json(['success' => true, 'status' => true, 'load' => true, 'message' => 'Attributes updated successfully.']);
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $keys = $this->productSpecificationRepository->attributeindex();
        
        return view('backend.category.specificationKeys.types.attributes.create',compact('keys'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->attributesstore($request);
    }

    public function show(Request $request,$id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $models = SpecificationKeyTypeAttribute::where('key_type_id',$id)->with('admin')->get();
        return view('backend.category.specificationKeys.types.attributes.attributesModal',compact('models'));
    }
    
    public function single($id)
    {
        return SpecificationKeyTypeAttribute::select('extra')->find($id);
    }

    public function updatestatus($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->attributeupdatestatus($id);
    }

    public function update(Request $request,$id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->attributeupdate($request,$id);
    }

    public function delete($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-type-attribute.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->attributedelete($id);
    }
}

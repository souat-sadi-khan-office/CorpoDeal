<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SpecificationKey;
use App\Http\Controllers\Controller;
use App\Models\SpecificationKeyType;
use App\Repositories\Interface\ProductSpecificationRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SpecificationsTypes extends SpecificationsController
{

    private $productSpecificationRepository;

    public function __construct(ProductSpecificationRepositoryInterface $productSpecificationRepository)
    {
        $this->productSpecificationRepository = $productSpecificationRepository;
    }

    public function update(Request $request, $keyId) 
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.update') === false) {
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
            'show_on_filter' => 'required|array',
            'filter_name' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }

        if(count($request->id) > 0) {
            foreach ($request->id as $i => $id) {
                if ($id) {
                    SpecificationKeyType::updateOrCreate(
                        ['id' => $id],
                        [
                            'name' => $request->name[$i], 
                            'status' => $request->status[$i],
                            'position' => $request->position[$i],
                            'show_on_filter' => $request->show_on_filter[$i],
                            'filter_name' => $request->filter_name[$i]
                        ]
                    );
                } else {
                    SpecificationKeyType::create([
                        'name' => $request->name[$i],
                        'status' => $request->status[$i],
                        'position' => $request->position[$i],
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'specification_key_id' => $keyId,
                        'show_on_filter' => $request->show_on_filter[$i],
                        'filter_name' => $request->filter_name[$i]
                    ]);
                }
            }
        }

        $remove_ids = $request->remove_ids;
        if($remove_ids != '') {
            $idArray = explode(',', $remove_ids);
            if(count($idArray) > 0) {
                foreach($idArray as $idItem) {
                    $spec = SpecificationKeyType::find($idItem);
                    if($spec) {
                        $spec->delete();
                    }
                }
            }
        }

        return response()->json(['success' => true, 'status' => true, 'load' => true, 'message' => 'Types updated successfully.']);
    }

    public function typeAttributes($id)
    {
        // Get the key
        $type = $this->productSpecificationRepository->getAttributesById($id);
        if(!$type) {
            return redirect()->back();
        }

        $attributes = $this->productSpecificationRepository->attributes($id);
        
        return view('backend.category.specificationKeys.types.attributes.custom', compact('attributes', 'type'));
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $keys = $this->productSpecificationRepository->typesindex();
       
        $view = $this->productSpecificationRepository->typesindexview($keys);
        // dd($view);
        if ($request->ajax()) {
            return $view;
        }
        return view('backend.category.specificationKeys.types.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $keys = $this->productSpecificationRepository->typesindex();
        return view('backend.category.specificationKeys.types.create',compact('keys'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->typesstore($request);
    }

    public function show(Request $request,$id)
    {
        $models=SpecificationKeyType::where('specification_key_id',$id)->with('admin')->orderBy('position')->get();
        return view('backend.category.specificationKeys.types.typesModal',compact('models'));
    }

    public function updatestatus($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->typesupdatestatus($id);
    }

    public function filterstatus($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->typesfilterstatus($id);
    }

    public function updateposition(Request $request,$id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->typesupdateposition($request,$id);
    }

    public function delete($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('specification-types.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->productSpecificationRepository->typesdelete($id);
    }
}


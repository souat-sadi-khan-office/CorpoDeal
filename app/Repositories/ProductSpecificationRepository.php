<?php

namespace App\Repositories;

use Exception;
use App\CPU\Images;
use App\CPU\Helpers;
use App\Http\Controllers\Admin\SpecificationsTypes;
use App\Models\Category;
use App\Models\SpecificationKey;
use App\Models\ProductSpecification;
use App\Models\SpecificationKeyType;
use App\Models\SpecificationKeyTypeAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\ProductSpecificationRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class ProductSpecificationRepository implements ProductSpecificationRepositoryInterface
{
    public function index()
    {
        return Category::select('id', 'name', 'photo', 'status', 'parent_id')
            ->withCount('specificationKeys')
            ->where('status', 1)
            ->orderBy('specification_keys_count', 'desc')
            ->get();
    }

    public function getTypeById($id)
    {
        return SpecificationKey::find($id);
    }

    public function getAttributesById($id)
    {
        return SpecificationKeyType::find($id);
    }

    public function store($data)
    {

        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'position' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }
        try {

            $model = SpecificationKey::create([
                'name' => $data->name,
                'admin_id' => Auth::guard('admin')->id(),
                'category_id' => $data->category_id,
                'position' => $data->position,
                'status' => isset($data->is_active),
                'is_public' => isset($data->is_public_input),
            ]);

            return response()->json(['id' => $model->id, 'name' => $model->name, 'message' => 'Specification Key Created successfully!', 'status' => true, 'key' => true, 'load' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    public function indexview($models)
    {
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('photo', function ($model) {
                return '<img src="' . asset($model->photo) . '" alt="" height="50px">';
            })
            ->editColumn('specification_keys_count', function ($model) {
                return "<div class='w-100 text-center'>
                            <span class='badge bg-dark rounded-pill' style='padding: 10px 20px;'>
                                " . $model->specification_keys_count . "
                            </span>
                        </div>";
            })

            ->editColumn('parent_id', function ($mode) {
                // return $mode->parent_id;
                return $mode->parent_id != null ? Helpers::categoryParent($mode->parent_id) : 'Primary Category';
            })
            ->addColumn('action', function ($model) {
                return view('backend.category.specificationKeys.action', compact('model'));
            })->rawColumns(['action', 'photo', 'parent_id', 'specification_keys_count'])
            ->make(true);
    }

    public function show($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('admin_id', function ($model) {
                return $model->admin->name;
            })
            ->addColumn('action', function ($model) {
                return view('backend.category.specificationKeys.action', compact('model'));
            })->rawColumns(['action'])
            ->make(true);
    }

    public function updatestatus($id)
    {

        $key = SpecificationKey::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Key not found.'], 404);
        }

        $key->status = !$key->status;
        $key->save();

        return response()->json(['success' => true, 'message' => 'Key status updated successfully.']);
    }
    public function updateIsPublic($id)
    {

        $key = SpecificationKey::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Key not found.'], 404);
        }

        $key->is_public = !$key->is_public;
        $key->save();

        return response()->json(['success' => true, 'message' => 'Key Public status updated successfully.']);
    }

    public function updateposition($request, $id)
    {
        $request->validate([
            'position' => 'required|integer', // Ensure it's a boolean value
            'name' => 'required', // Ensure it's a boolean value
        ]);

        $key = SpecificationKey::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Key not found.'], 404);
        }

        $key->position = $request->input('position');
        $key->name = $request->input('name');
        $key->save();

        return response()->json(['status' => true, 'stay' => true, 'message' => 'Key Position updated successfully.']);
    }

    public function delete($id)
    {
        $SpecificationKey = SpecificationKey::findOrFail($id);
        $SpecificationKey->delete();
        return response()->json(['status' => true, 'stay' => true, 'message' => 'Key Deleted successfully.']);
    }





    // Specification Key Types Functions


    public function typesindex()
    {
        $data = SpecificationKey::withCount('types')
            ->with('category:id,name')
            ->with('admin:id,name')
            ->where('status', 1)
            ->orderBy('position', 'desc')
            ->orderBy('types_count', 'desc')
            ->get();

        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'position' => $item->position,
                'types_count' => $item->types_count,
                'category_name' => $item->category ? $item->category->name : null,
                'created_by' => $item->admin ? $item->admin->name : null,
            ];
        });
    }

    public function typesstore($data)
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'specification_key_id' => 'required|integer',
            'position' => 'required|integer',
        ]);

        // Conditionally add the 'filter_name' rule
        $validator->sometimes('filter_name', 'required|string|max:255', function ($input) {
            return isset($input->is_show_on_filter);
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }
        try {

            $type = SpecificationKeyType::create([
                'name' => $data->name,
                'admin_id' => Auth::guard('admin')->id(),
                'specification_key_id' => $data->specification_key_id,
                'position' => $data->position,
                'is_show_on_filter' => isset($data->is_show_on_filter),
                'filter_name' => isset($data->is_show_on_filter) ? $data->filter_name : null,
                'status' => isset($data->is_active),
            ]);

            return response()->json([
                'message' => 'Specification Type Created successfully!',
                'status' => true,
                'type' => true,
                'id' => $type->id,
                'name' => $data->name
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    public function typesindexview($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()

            ->editColumn('types_count', function ($model) {
                return "<div class='w-100 text-center'>
                            <span class='badge bg-dark rounded-pill' style='padding: 10px 20px;'>
                                " . $model['types_count'] . "
                            </span>
                        </div>";
            })
            ->addColumn('action', function ($model) {
                return view('backend.category.specificationKeys.types.action', compact('model'));
            })->rawColumns(['action', 'types_count'])
            ->make(true);
    }

    public function typesshow($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('admin_id', function ($model) {
                return $model->admin->name;
            })
            ->addColumn('action', function ($model) {
                return view('backend.category.specificationKeys.types.action', compact('model'));
            })->rawColumns(['action'])
            ->make(true);
    }

    public function typesupdatestatus($id)
    {

        $key = SpecificationKeyType::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Type not found.'], 404);
        }

        $key->status = !$key->status;
        $key->save();

        return response()->json(['success' => true, 'message' => 'Type status updated successfully.']);
    }

    public function typesfilterstatus($id)
    {

        $key = SpecificationKeyType::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Type not found.'], 404);
        }

        $key->show_on_filter = !$key->show_on_filter;
        $key->save();

        return response()->json(['success' => true, 'message' => 'Type Show on Filter status updated successfully.']);
    }

    public function typesupdateposition($request, $id)
    {
        $request->validate([
            'position' => 'required|integer', // Ensure it's a boolean value
            'name' => 'required', // Ensure it's a boolean value
            'filter_name' => 'nullable', // Ensure it's a boolean value
        ]);

        $key = SpecificationKeyType::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Type not found.'], 404);
        }

        $key->position = $request->input('position');
        $key->name = $request->input('name');
        $key->filter_name = $request->input('filter_name');
        $key->save();

        return response()->json(['status' => true, 'stay' => true, 'message' => 'Type Position & Filter Name updated successfully.']);
    }

    public function typesdelete($id)
    {
        $SpecificationKey = SpecificationKeyType::findOrFail($id);
        $SpecificationKey->delete();
        return response()->json(['status' => true, 'stay' => true, 'message' => 'Type Deleted successfully.']);

    }




    // Specification Key Type Attributes Functions


    public function attributeindex()
    {
        $data = SpecificationKeyType::withCount('attributes')
            ->with('admin:id,name')
            ->where('status', 1)
            ->orderBy('attributes_count', 'desc')
            ->get();

        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'filter_name' => $item->filter_name,
                'attributes_count' => $item->attributes_count,
                'created_by' => $item->admin ? $item->admin->name : null,
            ];
        });
    }

    public function attributesstore($data)
    {
        $validator = Validator::make($data->all(), [
            'key_type_id' => 'required|integer',
            'name' => 'array|required|max:255',
            'extra' => 'array|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        } try {

            $nameArray = $data['name'];

            if(count($nameArray) > 0) {
                for($i = 0; $i < count($nameArray); $i++) {
                    $attr = SpecificationKeyTypeAttribute::create([
                        'name' => $nameArray[$i] ? $nameArray[$i] : '',
                        'admin_id' => Auth::guard('admin')->id(),
                        'key_type_id' => intval($data->key_type_id),
                        'extra' => isset($data['extra'][$i]) ? $data['extra'][$i] : '',
                        'status' => isset($data->is_active),
                    ]);
                }
            }

            // SpecificationKeyTypeAttribute::create([
            //     'name' => $data->name,
            //     'admin_id' => Auth::guard('admin')->id(),
            //     'key_type_id' => intval($data->key_type_id),
            //     'extra' => $data->extra,
            //     'status' => isset($data->is_active),
            // ]);

            return response()->json([
                'message' => 'Specification Type Attribute Created successfully!',
                'status' => true,
                'load' => true,
                'attributes' => true,
                'id' => $attr->id,
                'name' => $attr->name,
                'content' => $attr->extra
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    public function getKeysByCategoryId($categoryId)
    {
        return SpecificationKey::where('category_id', $categoryId)->orderBy('position', 'ASC')->get();
    }

    public function attributeindexview($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()

            ->editColumn('attributes_count', function ($model) {
                return "<div class='w-100 text-center'>
                             <span class='badge bg-dark rounded-pill' style='padding: 10px 20px;'>
                                 " . $model['attributes_count'] . "
                             </span>
                         </div>";
            })
            ->addColumn('action', function ($model) {
                return view('backend.category.specificationKeys.types.attributes.action', compact('model'));
            })->rawColumns(['action', 'attributes_count'])
            ->make(true);
    }

    public function attributeshow($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('admin_id', function ($model) {
                return $model->admin->name;
            })
            ->addColumn('action', function ($model) {
                return view('backend.category.specificationKeys.types.attributes.action', compact('model'));
            })->rawColumns(['action'])
            ->make(true);
    }

    public function attributeupdatestatus($id)
    {

        $key = SpecificationKeyTypeAttribute::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Attribute not found.'], 404);
        }

        $key->status = !$key->status;
        $key->save();

        return response()->json(['success' => true, 'message' => 'Attribute status updated successfully.']);
    }

    public function attributeupdate($request, $id)
    {
        $request->validate([
            'name' => 'required',
            'extra' => 'nullable',
        ]);

        $key = SpecificationKeyTypeAttribute::find($id);

        if (!$key) {
            return response()->json(['success' => false, 'message' => 'Attribute not found.'], 404);
        }

        $key->name = $request->input('name');
        $key->extra = $request->input('extra');
        $key->save();

        return response()->json(['status' => true, 'stay' => true, 'message' => 'Attribute updated successfully.']);
    }


    public function attributedelete($id)
    {
        $SpecificationKey = SpecificationKeyTypeAttribute::findOrFail($id);
        $SpecificationKey->delete();

        return response()->json(['status' => true, 'stay' => true, 'message' => 'Attributes Deleted successfully.']);
    }

    public function getOnlyPublicKey()
    {
        return SpecificationKey::where('is_public', true)
            ->select('id', 'name', 'is_public')
            ->get();
    }

    public function allKeysIncludingParent($ids)
    {
        $keys = SpecificationKey::whereIn('category_id', $ids)->get();

        $publicKeys = SpecificationKey::where('is_public', true)
            ->select('id', 'name', 'is_public','status')
            ->get();

        return $keys->merge($publicKeys)->unique('id');
    }

    public function keys($id)
    {
        $keys = SpecificationKey::where('category_id', $id)
            ->select('id', 'name', 'is_public')
            ->get();

        if ($keys->isEmpty()) {
            $category = Category::find($id);

            if ($category && $category->parent) {
                return $this->keys($category->parent_id);
            }
        }

        $publicKeys = SpecificationKey::where('is_public', true)
            ->select('id', 'name', 'is_public')
            ->get();

        return $keys->merge($publicKeys)->unique('id');
    }


    public function types($id)
    {
        return SpecificationKeyType::where('specification_key_id', $id)->get();
    }
    public function attributes($id)
    {
        return SpecificationKeyTypeAttribute::where('key_type_id', $id)->get();
    }
}

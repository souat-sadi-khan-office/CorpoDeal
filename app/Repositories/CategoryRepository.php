<?php

namespace App\Repositories;

use Exception;
use DataTables;
use App\CPU\Images;
use App\CPU\Helpers;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function index()
    {

        return Category::with('parent:id,name')
            ->where('parent_id', null)
            ->select('id', 'slug', 'name', 'photo', 'icon', 'admin_id', 'status', 'is_featured', 'parent_id')
            ->get();
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function index2()
    {
        return Category::with('parent:id,name')
            ->whereHas('parent')
            ->select('id', 'slug', 'name', 'photo', 'icon', 'admin_id', 'status', 'is_featured', 'parent_id')
            ->get();
    }

    public function edit($id)
    {
        return Category::find($id);
    }

    public function store($data)
    {

        $validator = $this->validate($data);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }
        try {

            $category = Category::create([
                'name' => $data->name,
                'admin_id' => Auth::guard('admin')->id(),
                'parent_id' => $data->parent_id,
                'slug' => $data->slug,
                'icon' => Helpers::icon($data->icon),
                'header' => $data->header,
                'short_description' => $data->short_description,
                'site_title' => $data->site_title,
                'description' => $data->description,
                'meta_title' => $data->meta_title,
                'meta_keyword' => $data->meta_keyword,
                'meta_description' => $data->meta_description,
                'meta_article_tag' => $data->meta_article_tag,
                'meta_script_tag' => $data->meta_script_tag,
                'status' => isset($data->status),
                'is_featured' => isset($data->is_featured),
                'photo' => $data->photo ? Images::upload('categories', $data->photo) : null,
            ]);
            Cache::forget('categories_count_dashboard');

            return response()->json(['message' => 'Created successfully!', 'status' => true, 'load' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    public function update($data, $id)
    {
        $validator = $this->validate($data, $id);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }
        try {
            $category = Category::where('id', $id)->first();

            $photoPath = $category->photo;

            if ($data->photo) {
                if ($photoPath) {
                    Images::delete($photoPath);
                }
                $photoPath = Images::upload('categories', $data->photo);
            }

            $category->update([
                'name' => $data->name,
                'admin_id' => Auth::guard('admin')->id(),
                'parent_id' => $data->parent_id,
                'slug' => $data->slug,
                'icon' => Helpers::icon($data->icon),
                'header' => $data->header,
                'short_description' => $data->short_description,
                'site_title' => $data->site_title,
                'description' => $data->description,
                'meta_title' => $data->meta_title,
                'meta_keyword' => $data->meta_keyword,
                'meta_description' => $data->meta_description,
                'meta_article_tag' => $data->meta_article_tag,
                'meta_script_tag' => $data->meta_script_tag,
                'status' => $data->has('status') ? 1 : 0,
                'is_featured' => $data->has('is_featured') ? 1 : 0,
                'photo' => $photoPath,
            ]);
            Cache::forget('categories_count_dashboard');

            return response()->json(['message' => 'Updated successfully!', 'status' => true, 'goto' => isset($data->sub) ? route('admin.category.index.sub') : route('admin.category.index')]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => false]);
        }
    }

    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)->where('status', 1)->first();
    }

    public function checkSlugExists(string $slug): bool
    {
        return Category::where('slug', $slug)->exists();
    }
    public function categoriesDropDown($request)
    {
        return isset($request->parent_id) ? Category::where('parent_id', $request->parent_id)->select('id', 'name', 'photo')->get() : Category::select('id', 'name', 'photo')->get();
    }

    public function updateisFeatured($request, $id)
    {
        $request->validate([
            'is_featured' => 'required|boolean', // Ensure it's a boolean value
        ]);

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found.'], 404);
        }

        $category->is_featured = $request->input('is_featured');
        $category->save();

        return response()->json(['success' => true, 'message' => 'Category status updated successfully.']);
    }
    public function updatestatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean', // Ensure it's a boolean value
        ]);

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found.'], 404);
        }

        $category->status = $request->input('status');
        $category->save();
        Cache::forget('categories_count_dashboard');

        return response()->json(['success' => true, 'message' => 'Category status updated successfully.']);
    }

    private function validate($data, $id = null)
    {
        return Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($id),
            ],
            'icon' => 'required|string',
            'header' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'site_title' => 'required|string|max:255',
            'description' => 'required|string',
            'meta_title' => 'required|string|max:255',
            'meta_keyword' => 'required|string',
            'meta_description' => 'required|string',
            'meta_article_tag' => 'nullable|string',
            'meta_script_tag' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);
    }

    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function view($models)
    {
        return Datatables::of($models)
            ->addIndexColumn()

            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (Auth::guard('admin')->user()->hasPermissionTo('category.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.category.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                } else {
                    $bg = $model->status == 1 ? 'warning' : 'success';
                    $status = $model->status == 1 ? 'Featured' : 'Normal';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }   
            })
            ->editColumn('photo', function ($model) {
                return Images::show($model->photo);;
            })
            ->editColumn('icon', function ($model) {
                return $model->icon;
            })
            ->editColumn('admin_id', function ($model) {
                return Helpers::adminName($model->admin_id);
            })
            ->editColumn('parent_id', function ($mode) {
                // return $mode->parent_id;
                return $mode->parent_id != null ? Helpers::categoryParent($mode->parent_id) : '0';
            })
            ->editColumn('is_featured', function ($model) {
                $checked = $model->is_featured == 1 ? 'checked' : '';

                if (Auth::guard('admin')->user()->hasPermissionTo('category.update')) {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.category.is_featured', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="is_featured" id="is_featured_' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"> </div>';

                } else {
                    $bg = $model->is_featured == 1 ? 'warning' : 'success';
                    $status = $model->is_featured == 1 ? 'Featured' : 'Normal';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                }   

            })
            ->addColumn('action', function ($model) {
                return view('backend.category.action', compact('model'));
            })->rawColumns(['action', 'photo', 'status', 'icon', 'admin_id', 'is_featured', 'parent_id'])
            ->make(true);
    }

    public function getParentCategoryIds($categoryId)
    {
        return Category::findOrFail($categoryId)->allParentCategories();
    }

    public function delete($id)
    {
        Cache::forget('categories_count_dashboard');

        $Category = Category::findOrFail($id);
        return $Category->delete();
    }
}

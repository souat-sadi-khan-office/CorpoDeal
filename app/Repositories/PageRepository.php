<?php

namespace App\Repositories;

use DataTables;
use App\CPU\Images;
use App\Models\Page;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\PageRepositoryInterface;

class PageRepository implements PageRepositoryInterface
{
    public function getAllPages()
    {
        return Page::all();
    }

    public function dataTable()
    {
        $models = $this->getAllpages();
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('parent', function($model) {
                return $model->parent_id != null ? $model->parent->title : 'Parent Page';
            })
            ->editColumn('url', function ($model) {
                return '<a href="'. URL::to($model->slug) .'" target="_blank">'. URL::to($model->slug) .'</a>';
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.page.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.page.action', compact('model'));
            })
            ->rawColumns(['action', 'parent', 'status', 'url'])
            ->make(true);
    }

    public function findPageById($id)
    {
        return Page::findOrFail($id);
    }

    public function createPage($data)
    {
        $page = Page::create([
            'parent_id' => $data->parent_id,
            'title' => $data->name,
            'slug' => $data->slug,
            'status' => $data->status,
            'content' => $data->description,
            'meta_title' => $data->meta_title,
            'meta_keyword' => $data->meta_keyword,
            'meta_description' => $data->meta_description,
            'meta_article_tag' => $data->meta_article_tag,
            'meta_script_tag' => $data->meta_script_tag,
            'show_on_navbar' => $data->show_on_navbar,
            'meta_image' => $data->meta_image ? Images::upload('pages', $data->meta_image) : null,
        ]);

        $json = ['status' => true, 'goto' => route('admin.page.index'), 'message' => 'Page created successfully'];
        return response()->json($json);
    }

    public function updatepage($id, $data)
    {
        $page = Page::findOrFail($id);
        $page->parent_id = $data->parent_id;
        $page->title = $data->name;
        $page->slug = $data->slug;
        $page->status = $data->status;
        $page->content = $data->description;
        $page->meta_title = $data->meta_title;
        $page->meta_keyword = $data->meta_keyword;
        $page->meta_description = $data->meta_description;
        $page->meta_article_tag = $data->meta_article_tag;
        $page->meta_script_tag = $data->meta_script_tag;
        $page->show_on_navbar = $data->show_on_navbar;

        if($data->meta_image) {
            $page->meta_image = Images::upload('pages', $data->meta_image);
        }

        $page->update();

        return response()->json(['status' => true, 'goto' => route('admin.page.index'), 'message' => 'Page updated successfully.']);
    }

    public function deletePage($id)
    {
        $page = Page::findOrFail($id);
        return $page->delete();
    }

    public function updateStatus($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $page = Page::find($id);

        if (!$page) {
            return response()->json(['success' => false, 'message' => 'Page not found.'], 404);
        }

        $page->status = $request->input('status');
        $page->save();

        return response()->json(['success' => true, 'message' => 'Page status updated successfully.']);
    }
}
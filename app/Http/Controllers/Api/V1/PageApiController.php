<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class PageApiController extends Controller
{
    public function getBySlug(string $slug): JsonResponse
    {
        $page = Page::with(['parent:id,title,slug', 'children:id,title,slug,parent_id'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'content' => $page->content,
                'meta_title' => $page->meta_title,
                'meta_keyword' => $page->meta_keyword,
                'meta_description' => $page->meta_description,
                'meta_article_tag' => $page->meta_article_tag,
                'meta_script_tag' => $page->meta_script_tag,
                'meta_image' => $page->meta_image,
                'parent' => $page->parent,
                'children' => $page->children,
            ]
        ]);
    }
}

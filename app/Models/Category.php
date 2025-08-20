<?php

namespace App\Models;

use App\Traits\IstiyakTraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,IstiyakTraitLog;

    protected $fillable = [
        'parent_id',
        'admin_id',
        'name',
        'slug',
        'photo',
        'icon',
        'header',
        'short_description',
        'description',
        'site_title',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'meta_article_tag',
        'meta_script_tag',
        'status',
        'is_featured',
    ];

    public function hasParentCategory($categoryId)
    {
        if ($this->id == $categoryId) {
            return true;
        }

        if ($this->parent_id) {
            $parentCategory = Category::find($this->parent_id);
            if ($parentCategory) {
                return $parentCategory->hasParentCategory($categoryId);
            }
        }

        return false;
    }


    public function allParentCategories()
    {
        $parents = [];

        $currentCategory = $this;
        while ($currentCategory->parent_id != null) {
            $currentCategory = $currentCategory->parent;
            $parents[] = $currentCategory->id;
        }

        $parents[] = $this->id;

        return $parents;
    }


    // Self-relation for parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relation with Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Relation for subcategories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function banners()
    {
        return $this->hasMany(CategoryPicture::class);
    }

    public static function getCategoriesWithChildren($parentId = null, $isFirstParent = true)
    {
        // If it's the first parent, apply is_featured filter
        $query = self::with('children')
            ->where('parent_id', $parentId)
            ->where('status', 1);

        // Apply is_featured condition only for the first parent
        if ($isFirstParent) {
            $query->where('is_featured', 1);
            $query->take(10);
        }

        $categories = $query->get();

        foreach ($categories as $category) {
            // Recursively fetch children, but set isFirstParent to false
            $category->children = self::getCategoriesWithChildren($category->id, false);
        }

        return $categories;
    }

    // Relation for keys
    public function key()
    {
        return $this->hasMany(SpecificationKey::class);
    }

    // Relation with product
    public function product()
    {
        return $this->hasMany(Product::class);
    }

    // Relation with banner
    public function banner()
    {
        return $this->hasMany(Banner::class);
    }

    public function specificationKeys()
    {
        return $this->hasMany(SpecificationKey::class, 'category_id');
    }

    public function getAllCategoryIds()
    {
        $categoryIds = [];
        $category = $this;

        // Add the current category ID
        $categoryIds[] = $category->id;

        // Get all parent category IDs
        while ($category->parent) {
            $categoryIds[] = $category->parent->id;
            $category = $category->parent;
        }

        // Get all child category IDs
        $childCategories = $this->getChildCategories($this);
        foreach ($childCategories as $child) {
            $categoryIds[] = $child->id;
        }

        return $categoryIds;
    }

    public function getChildCategories($category)
    {
        $childCategories = [];

        foreach ($category->children as $child) {
            $childCategories[] = $child;
            $childCategories = array_merge($childCategories, $this->getChildCategories($child));
        }

        return $childCategories;
    }

    public function getChildCategoriesId($category)
    {
        $childCategories = [];

        foreach ($category->children as $child) {
            $childCategories[] = $child->id;
            $childCategories = array_merge($childCategories, $this->getChildCategories($child));
        }

        return $childCategories;
    }

}

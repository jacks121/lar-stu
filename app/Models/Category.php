<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $guarded = [];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getAllCategories()
    {
        return Cache::remember('categories', 60, function () {
            return $this->with('images')->get();
        });
    }

    public function getCategoryTree()
    {
        return Cache::remember('category_tree', 60, function () {
            $rootCategories = $this->with('images')->where('parent_id', 0)->get();
            foreach ($rootCategories as $category) {
                $this->findSubcategories($category);
            }
            return $rootCategories;
        });
    }

    private function findSubcategories($category)
    {
        $subcategories = $this->with('images')->where('parent_id', $category->id)->get();
        $category->setRelation('subcategories', $subcategories);
        
        foreach ($subcategories as $subcategory) {
            $this->findSubcategories($subcategory);
        }
    }

    public function getCategoriesByIds(array $ids)
    {
        $key = 'categories:' . implode(',', $ids);

        return Cache::remember($key, 60, function () use ($ids) {
            return $this->with('images')->whereIn('id', $ids)->get();
        });
    }

    public function getBreadcrumbs(int $categoryId)
    {
        $breadcrumbs = [];
        $category = $this->getCategoryByID($categoryId);
        
        if (!$category) {
            return $breadcrumbs;
        }

        $breadcrumbs[] = ['name' => $category->name, 'url' => $category->url];
        
        while ($category->parent_id != 0) {
            $category = $this->getCategoryByID($category->parent_id);
            
            if (!$category) {
                return $breadcrumbs;
            }
            
            array_unshift($breadcrumbs, ['name' => $category->name, 'url' => $category->url]);
        }

        return $breadcrumbs;
    }

    public function getCategoryByID(int $categoryId)
    {
        $key = 'category:' . $categoryId;

        return Cache::remember($key, 60, function () use ($categoryId) {
            return $this->with('images')->find($categoryId);
        });
    }
}

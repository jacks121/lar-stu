<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'detail' => 'json',
    ];
    
    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getLatestProducts(int $limit) {
        return $this->with('images')->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function getTopSellers(int $limit) {
        return $this->with('images')->orderBy('sales', 'desc')->limit($limit)->get();
    }

    public function indexToElasticsearch()
    {
        // 获取所有商品及其相关的属性
        $products = $this->with('productAttributes')->with('categories')->with('images')->with('reviews')->get();
        
        // 连接 Elasticsearch
        $elasticsearch = app('elasticsearch');
        
        // 遍历每个商品，构建文档并索引到 Elasticsearch
        foreach ($products as $product) {
            $document = $product->toArray();

            // 将商品文档索引到 Elasticsearch
            $elasticsearch->index([
                'index' => 'products',
                'type' => '_doc',
                'id' => $product->id,
                'body' => $document,
            ]);
        }
    }
}

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
}

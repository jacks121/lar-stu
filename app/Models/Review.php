<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getReviewsWithImagesByPageAndCount($page, $pageSize)
    {
        $cacheKey = 'reviews_' . $page;

        $reviews = Cache::rememberForever($cacheKey, function () use ($page, $pageSize) {
            return $this->with('images')
                ->skip(($page - 1) * $pageSize)
                ->take($pageSize)
                ->get();
        });

        $total = $this->count();

        return [
            'reviews' => $reviews,
            'total' => $total
        ];
    }
}

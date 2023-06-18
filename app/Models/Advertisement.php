<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

class Advertisement extends Model
{
    protected $guarded = [];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getAdvertisementByCode(string $code): ?Advertisement
    {
        $redisKey = "advertisement:$code";
        
        return Cache::remember($redisKey, 60, function () use ($code) {
            return $this->with('images')->where('code', $code)->first();
        });
    }
}

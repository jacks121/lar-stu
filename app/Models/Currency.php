<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    protected $guarded = [];

    public function getAllCurrencies()
    {
        return Cache::remember('all_currencies', 60, function () {
            return self::all();
        });
    }
}


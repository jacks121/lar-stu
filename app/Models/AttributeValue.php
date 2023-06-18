<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeValue extends Model
{
    protected $guarded = [];

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, 'value_id');
    }
}

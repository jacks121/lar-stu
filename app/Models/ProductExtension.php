<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductExtension extends Model
{
    // 假设您的产品扩展表名为 "product_extensions"
    protected $table = 'product_extensions';

    // 声明与 Product 模型的一对一关系
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

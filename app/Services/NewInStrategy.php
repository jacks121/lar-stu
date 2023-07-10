<?php

namespace App\Services;

use App\Models\Product;

class NewInStrategy
{
    protected $product;
    protected $rule;

    public function __construct(Product $product, array $rule)
    {
        $this->product = $product;
        $this->rule = $rule;
    }

    public function getProducts()
    {
        return $this->product->getLatestProducts($this->rule[0]['value']);
    }
}
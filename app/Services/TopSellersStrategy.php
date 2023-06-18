<?php

namespace App\Services;

use App\Models\Product;

class TopSellersStrategy
{
    protected $product;
    protected $limit;

    public function __construct(Product $product, int $limit)
    {
        $this->product = $product;
        $this->limit = $limit;
    }

    public function getProducts()
    {
        return $this->product->getTopSellers($this->limit);
    }
}
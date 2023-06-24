<?php

namespace App\Services;

use App\Services\NewInStrategy;
use App\Services\TopSellersStrategy;
use App\Models\Product;

class StrategyFactory
{
    public function createStrategy(string $collectionType, $rule) {
        switch ($collectionType) {
            case "new":
                return new NewInStrategy(new Product, $rule['limit']);
            case "sales":
                return new TopSellersStrategy(new Product, $rule['limit']);
            default:
                throw new \Exception("Unknown strategy code: $collectionType");
        }
    }
}
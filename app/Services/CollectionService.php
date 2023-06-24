<?php

namespace App\Services;

use App\Models\Collection;

class CollectionService
{
    protected $strategyFactory;

    public function __construct(StrategyFactory $strategyFactory)
    {
        $this->strategyFactory = $strategyFactory;
    }

    public function getProductsByCollectionCode(string $code)
    {
        $collection = Collection::where('code', $code)->first();

        if (!$collection) {
            throw new \Exception("Collection not found");
        }
 
        $strategy = $this->strategyFactory->createStrategy($collection->type, $collection->rule);

        return $strategy->getProducts();
    }
}
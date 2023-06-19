<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use App\Models\Advertisement;
use App\Services\CollectionService;

class IndexController extends Controller
{
    protected $advertisement;
    protected $collectionService;

    public function __construct(
        Advertisement $advertisement,
        CollectionService $collectionService
    ) {
        $this->advertisement = $advertisement;
        $this->collectionService = $collectionService;
    }

    public function index()
    {
        $agent = new Agent();

        $advertisement = [
            'banner' => $this->advertisement->getAdvertisementByCode('banner'),
            'categoryBanner' => $this->advertisement->getAdvertisementByCode('category_banner'),
        ];

        $newinProducts = $this->collectionService->getProductsByCollectionCode('newin');
        $topProducts = $this->collectionService->getProductsByCollectionCode('top');

        $viewData = [
            'advertisement' => $advertisement,
            'newinProducts' => $newinProducts,
            'topProducts' => $topProducts,
        ];

        $viewName = $agent->isDesktop() ? 'pc.index' : 'mobile.index';

        return view($viewName, $viewData);
    }
}

<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Advertisement;
use App\Services\CollectionService;   // 引入服务

class IndexController extends Controller
{
    protected $category;
    protected $currency;
    protected $advertisement;
    protected $collectionService;   // 定义服务属性

    public function __construct(
        Category $category,
        Currency $currency,
        Advertisement $advertisement,
        CollectionService $collectionService    // 在构造函数中注入服务
    ) {
        $this->category = $category;
        $this->currency = $currency;
        $this->advertisement = $advertisement;
        $this->collectionService = $collectionService;   // 初始化服务属性
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agent = new Agent();

        // 使用模型
        $tree = $this->category->getCategoryTree();
        $currencies = $this->currency->getAllCurrencies();
        $advertisement = [
            'banner' => $this->advertisement->getAdvertisementByCode('banner'),
            'categoryBanner' => $this->advertisement->getAdvertisementByCode('category_banner'),
        ];

        // 使用服务获取产品
        $newinProducts = $this->collectionService->getProductsByCollectionCode('newin');
        $topProducts = $this->collectionService->getProductsByCollectionCode('top');
      
        $viewData = [
            'categories' => $tree,
            'currencies' => $currencies,
            'advertisement' => $advertisement,
            'newinProducts' => $newinProducts,
            'topProducts' => $topProducts,
        ];

        if ($agent->isDesktop()) {
            return view('pc.index', $viewData);
        } else {
            return view('mobile.index', $viewData);
        }
    }
}

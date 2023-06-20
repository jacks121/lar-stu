<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    protected $category;

    protected $product;

    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $path = '/' . $request->path();
        // 获取所有的分类信息
        $categories = $this->category->getAllCategories(); // 使用注入的 Category 模型对象

        // 遍历分类信息，查找匹配的 URL
        $categoryID = null;
        foreach ($categories as $category) {
            if ($category->url === $path) {
                $categoryID = $category->id;
                break;
            }
        }

        // 如果找到匹配的分类 URL，则调用 show 方法并传递分类 ID
        if (!is_null($categoryID)) {
            return $this->show($categoryID, $request);
        }

        // 处理其他逻辑，例如返回 404 Not Found
        return response()->json(['error' => 'Page not found'], 404);
    }

    public function show($categoryID, Request $request)
    {
        // 验证请求参数
        $validated = $request->validate([
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1|max:100',
            'sortField' => 'string|nullable',
            'sortOrder' => 'in:asc,desc|nullable',
            'priceMin' => 'integer|min:0',
            'priceMax' => 'integer|min:0',
            'details' => 'array',
        ]);

        // 构建搜索参数
        $params = array_merge(['category_id' => $categoryID], $validated);

        // 调用搜索方法获取商品列表
        $result = $this->product->searchProducts($params);
        // $this->product->indexToElasticsearch();
        // 将搜索结果绑定到模板
        return view('pc.category', [
            'products' => $result['products'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'totalHits' => $result['totalHits'],
        ]);
    }
}

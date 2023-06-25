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
        // $this->product->indexToElasticsearch();die;
        // 验证请求参数
        $validated = $request->validate([
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:1|max:100',
            'sortField' => 'string|nullable',
            'sortOrder' => 'in:asc,desc|nullable',
            'price' => 'string|nullable',
            'details' => 'array',
        ]);

        $details = $request->get('details',[]);

        foreach ($details as $key => $value) {
            if (!is_string($key) || !is_string($value)) {
                return response()->json(['error' => 'Invalid details format'], 400);
            }

            $values = explode(',', $value);
            foreach ($values as $v) {
                if (empty($v)) {
                    return response()->json(['error' => 'Invalid details format'], 400);
                }
            }
        }

        // 初始化默认参数
        $page = $validated['page'] ?? 1;
        $perPage = $validated['perPage'] ?? 10;
        $sortField = $validated['sortField'] ?? null;
        $sortOrder = $validated['sortOrder'] ?? null;
        $priceRange = null;

        // 处理价格范围
        if (isset($validated['price']) && $validated['price']) {
            $priceRange = explode('-', $validated['price']);
            // 检查价格范围是否有效
            if (count($priceRange) !== 2 || !is_numeric($priceRange[0]) || !is_numeric($priceRange[1])) {
                // 返回错误响应或执行其他逻辑
            }
        }

        // 构建搜索参数
        $params = [
            'category_id' => $categoryID,
            'page' => $page,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'priceMin' => $priceRange ? $priceRange[0] : null,
            'priceMax' => $priceRange ? $priceRange[1] : null,
            'details' => $details,
        ];
        // 调用搜索方法获取商品列表
        $result = $this->product->searchProducts($params);
        // 将搜索结果绑定到模板
        return view('pc.category', [
            'products' => $result['products'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'totalHits' => $result['totalHits'],
            'filterList' => $result['filterList'],
        ]);
    }
}

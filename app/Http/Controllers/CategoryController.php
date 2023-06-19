<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    protected $category;

    protected $product;

    public function __construct(Category $category,Product $product)
    {
        $this->category = $category;
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $path = '/'.$request->path();
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
            return $this->show($categoryID);
        }

        // 处理其他逻辑，例如返回 404 Not Found
        return response()->json(['error' => 'Page not found'], 404);
    }

    public function show($categoryID)
    {
        $this->product->indexToElasticsearch();
        return view('pc.category');
    }
}

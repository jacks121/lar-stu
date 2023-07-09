<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        // 获取请求参数
        $sortField = $request->query('sortField', 'default_field'); // 默认排序字段
        $sortOrder = $request->query('sortOrder', 'asc'); // 默认排序顺序
        $perPage = $request->query('perPage', 6); // 每页显示数量
        $page = $request->query('page', 1); // 当前页数
        $keyword = $request->query('q','');
        // 构建排序参数
        $sort = [];
        if ($sortField !== 'default_field') {
            $sort[] = [$sortField => ['order' => $sortOrder]];
        }

        // 调用search方法进行搜索
        $result = $this->product->search($keyword, $page, $perPage, $sort);

        return view('pc.search', [
            'products' => $result['products'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'totalHits' => $result['totalHits'],
        ]);
    }
}

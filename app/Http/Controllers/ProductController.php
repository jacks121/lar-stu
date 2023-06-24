<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index($id)
    {
        $product = $this->product->getProductData($id);
        // $this->product->indexToElasticsearch();
        // dd($product);
        return view('pc.product', [
            'product' => $product
        ]);
    }
}

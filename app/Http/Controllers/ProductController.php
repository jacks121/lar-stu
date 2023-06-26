<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCart;

class ProductController extends Controller
{
    protected $product;

    protected $cart;

    public function __construct(Product $product, ShoppingCart $cart)
    {
        $this->product = $product;
        $this->cart = $cart;
    }

    public function index($id)
    {
        // $this->product->indexToElasticsearch();die;
        $product = $this->product->getProductData($id);

        return view('pc.product', [
            'product' => $product,
        ]);
    }
}

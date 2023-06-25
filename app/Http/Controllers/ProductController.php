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
        $product = $this->product->getProductData($id);
        // $this->product->indexToElasticsearch();
        // dd($product);
        return view('pc.product', [
            'product' => $product,
        ]);
    }

    public function addToCart()
    {
        $params = request()->all();
        $this->cart->addToCart($params);
  
        $jsonString = '{
            "status": true,
            "name": "Stunring Art Deco 9ct Round Cut Engagement Ring",
            "image": "https://cdn.stunring.com/media/catalog/product/cache/da020853bb395d32ac7fedcd71118744/1/1/1121101_4.jpg",
            "main_image": "https://cdn.stunring.com/media/catalog/product/cache/229bf4f5a8345b7337106ce37c4c2fe6/1/1/1121101_4.jpg",
            "promotion": "<div style=\"font-size: 16px;line-height: 30px;\">\r\n<div style=\"text-align: center;\">\r\nBuy More, Save More\r\n</div>\r\n<div>Sitewide $25 Off  CODE：S25\r\n<div>\r\n<div>Buy 1 Get 1 Free   CODE：FREE\r\n</div>\r\n<div>Free Gift for Order $199+\r\n</div>\r\nCheckout now!",
            "id": "2399",
            "sku": "1121101",
            "currency": "USD",
            "price": "<span class=\"price\">$120.00</span>",
            "price_int": 12000,
            "price_no_frame": 120,
            "additionData": null
        }';

        return response()->json(json_decode($jsonString));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingCart;

class CartController extends Controller
{
    private $cart;

    public function __construct(ShoppingCart $cart)
    {
        $this->cart = $cart;
    }

    public function show(Request $request)
    {
        return view('pc.cart', [
            'cartList' => $this->cart->cartList(),
            'orderSummary' => $this->cart->getCartDataWithSummary(),
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

    /**
     * 从购物车中删除商品。
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete()
    {
        // 从请求中获取商品的唯一标识符
        $uniqueId = request()->input('uenc', 0);

        // 从购物车中移除指定的商品项
        $this->cart->removeFromCart($uniqueId);

        // 返回到上一个页面
        return back();
    }

    /**
     * 更新购物车中商品的数量。
     *
     * @return array 包含成功信息的关联数组
     */
    public function updateItemQty(): array
    {
        // 从请求中获取购物车商品信息
        $cartItems = request()->input('cart');

        // 调用购物车对象的方法来批量调整商品数量
        $this->cart->adjustItemQuantities($cartItems);

        return ['success' => true]; // 返回成功信息
    }

    public function getCartData(Request $request)
    {
        if ($request->ajax()) {
            // 获取购物车数据
            $cartDataWithSummary = $this->cart->getCartDataWithSummary();

            // 创建购物车列表的 HTML
            $cartListHtml = view('pc.cart.mini_cart_items', ['cartDataWithSummary' => $cartDataWithSummary])->render();

            // 返回购物车数据
            return response()->json([
                'cartListHtml' => $cartListHtml,
                'cartCount' => $cartDataWithSummary['count'],
                'cartSubtotal' => $cartDataWithSummary['subtotal'],
            ]);
        }

        // 如果不是 AJAX 请求，返回 404 错误
        abort(404);
    }
}

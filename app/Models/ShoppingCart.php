<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;

class ShoppingCart
{
    private Session $session;
    private Product $product;

    public function __construct(Session $session, Product $product)
    {
        $this->session = $session;
        $this->product = $product;
    }

    /**
     * 将商品添加到购物车中。
     *
     * @param int $productId 商品ID
     * @param array $options 商品选项
     * @param int $qty 数量
     * @return void
     * @throws ModelNotFoundException
     */
    public function addToCart(int $productId, array $options, int $qty): void
    {
        $product = $this->product->findOrFail($productId);

        $cartItem = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'options' => $options,
            'qty' => $qty
        ];

        $cartData = $this->getCartData();
        $cartData[] = $cartItem;
        $this->updateCartData($cartData);
    }

    /**
     * 获取购物车中的商品列表。
     *
     * @return array 商品列表
     */
    public function cartList(): array
    {
        return $this->getCartData();
    }

    /**
     * 从购物车中移除指定的商品。
     *
     * @param int $productId 商品ID
     * @return void
     */
    public function removeFromCart(int $productId): void
    {
        $cartData = $this->getCartData();
        $cartData = array_filter($cartData, fn($item) => $item['id'] !== $productId);
        $this->updateCartData($cartData);
    }

    /**
     * 清空购物车，移除所有商品。
     *
     * @return void
     */
    public function cartClear(): void
    {
        $this->updateCartData([]);
    }

    /**
     * 获取购物车数据。
     *
     * @return array 购物车数据
     */
    private function getCartData(): array
    {
        return $this->session->get('cart_data', []);
    }

    /**
     * 更新购物车数据。
     *
     * @param array $cartData 购物车数据
     * @return void
     */
    private function updateCartData(array $cartData): void
    {
        $this->session->put('cart_data', $cartData);
    }
}

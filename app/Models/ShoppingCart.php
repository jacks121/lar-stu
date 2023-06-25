<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use InvalidArgumentException;
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
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    public function addToCart(array $params): void
    {
        $this->validateParams($params);
        $product = $this->product->getProductData($params['product_id']);

        $price = $this->calculatePrice($product, $params['options']);
        $imageUrl = $this->getImageUrl($product);

        $cartItem = $this->buildCartItem($product, $params, $price, $imageUrl);
        $this->updateCart($cartItem);
    }

    /**
     * 验证参数是否有效。
     *
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateParams(array $params): void
    {
        if (!isset($params['product_id'], $params['options'], $params['qty'])) {
            throw new InvalidArgumentException("Invalid parameters. 'product_id', 'options', and 'qty' are required.");
        }
    }

    /**
     * 获取商品的图片URL。
     *
     * @param array $product
     * @return string|null
     */
    private function getImageUrl(array $product): ?string
    {
        return collect($product['images'])->first()['image_url'] ?? null;
    }

    /**
     * 构建购物车项。
     *
     * @param array $product
     * @param array $params
     * @param float $price
     * @param string|null $imageUrl
     * @return array
     */
    private function buildCartItem(array $product, array $params, float $price, ?string $imageUrl): array
    {
        return [
            'id' => $product['id'],
            'name' => $product['product_name'],
            'image_url' => $imageUrl,
            'price' => $price,
            'options' => $params['options'],
            'qty' => $params['qty'],
        ];
    }

    /**
     * 更新购物车数据。
     *
     * @param array $newCartItem
     * @return void
     */
    private function updateCart(array $newCartItem): void
    {
        $cartData = $this->getCartData();
    
        foreach ($cartData as &$cartItem) {
            if ($cartItem['id'] === $newCartItem['id'] && $cartItem['options'] === $newCartItem['options']) {
                $cartItem['qty'] += $newCartItem['qty'];
                $this->updateCartData($cartData);
                return;
            }
        }
 
        // If the loop completes without returning, the item is not in the cart.
        $cartData[] = $newCartItem;
        $this->updateCartData($cartData);
    }

    /**
     * 计算商品价格。
     *
     * @param array $product
     * @param array $options
     * @return float
     */
    private function calculatePrice(array $product, array $options): float
    {
        $priceAdjustment = collect($product['product_attributes'])
            ->whereIn('attribute', array_keys($options))
            ->whereIn('value_id', array_values($options))
            ->sum('price_adjustment');

        $price = $product['current_price'];
        if (data_get($options, 'characters', false)) {
            $price += 15;
        }
        $price += $priceAdjustment;

        return $price;
    }

    /**
     * 获取购物车中的商品列表。
     *
     * @return array
     */
    public function cartList(): array
    {
        return $this->getCartData();
    }

    /**
     * 从购物车中移除指定的商品。
     *
     * @param int $productId
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
     * @return array
     */
    private function getCartData(): array
    {
        return $this->session->get('cart_data', []);
    }

    /**
     * 更新购物车数据。
     *
     * @param array $cartData
     * @return void
     */
    private function updateCartData(array $cartData): void
    {
        $this->session->put('cart_data', $cartData);
    }

    /**
     * 调整购物车中的商品数量。
     *
     * @param int $productId
     * @param array $options
     * @param int $quantity
     * @return void
     */
    public function adjustItemQuantity(int $productId, array $options, int $quantity): void
    {
        $cartData = $this->getCartData();

        foreach ($cartData as &$cartItem) {
            if ($cartItem['id'] === $productId && $cartItem['options'] === $options) {
                $cartItem['qty'] += $quantity;

                if ($cartItem['qty'] <= 0) {
                    $this->removeItemFromCart($productId, $options);
                } else {
                    $this->updateCartData($cartData);
                }

                return;
            }
        }

        throw new InvalidArgumentException("Item with product ID {$productId} and options " . json_encode($options) . " not found in cart.");
    }

    /**
     * 从购物车中移除指定的商品项。
     *
     * @param int $productId
     * @param array $options
     * @return void
     */
    public function removeItemFromCart(int $productId, array $options): void
    {
        $cartData = $this->getCartData();
        $cartData = array_filter($cartData, fn($item) => !($item['id'] === $productId && $item['options'] === $options));

        $this->updateCartData($cartData);
    }

}

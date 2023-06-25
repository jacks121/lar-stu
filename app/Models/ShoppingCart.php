<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use App\Exceptions\ShoppingCartException;
use App\Models\Product;
use InvalidArgumentException;

class ShoppingCart
{
    const PRICE_PER_CHARACTER = 15;
    const TAX_RATE = 0.1;

    private Session $session;
    private Product $product;

    public function __construct(Session $session, Product $product)
    {
        $this->session = $session;
        $this->product = $product;
    }

    /**
     * 获取购物车数据，包括小计、税费和订单总额。
     *
     * @return array
     */
    public function getCartDataWithSummary(): array
    {
        $cartData = $this->getCartData();
        $subtotal = 0;

        foreach ($cartData as $cartItem) {
            $subtotal += $cartItem['subtotal'] ?? 0;
        }

        $tax = $subtotal * self::TAX_RATE;
        $total = $subtotal + $tax;

        return [
            'items' => $cartData, // 购物车数据项
            'subtotal' => $subtotal, // 小计金额
            'tax' => $tax, // 税费金额
            'total' => $total, // 订单总额
        ];
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
     * @throws ShoppingCartException
     */
    private function validateParams(array $params): void
    {
        $requiredParams = ['product_id', 'options', 'qty'];

        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new ShoppingCartException("Invalid parameters. '{$param}' is required.");
            }
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
        $subtotal = $price * $params['qty'];
        return [
            'unique_id' => md5($product['id'] . serialize($params['options'])),
            'id' => $product['id'],
            'name' => $product['product_name'],
            'image_url' => $imageUrl,
            'price' => $price,
            'options' => $params['options'],
            'qty' => $params['qty'],
            'subtotal' => $subtotal,
            'original_price' => $product['original_price'],
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

        $foundKey = null;
        foreach ($cartData as $key => &$item) {
            if ($this->isCartItemEqual($item, $newCartItem)) {
                $foundKey = $key;
                break;
            }
        }

        if ($foundKey !== null) {
            $cartData[$foundKey]['qty'] += $newCartItem['qty'];
            $cartData[$foundKey]['subtotal'] += $newCartItem['subtotal']; // 更新小计
        } else {
            $cartData[] = $newCartItem;
        }

        $this->updateCartData($cartData);
    }

    /**
     * 检查两个购物车项是否相等。
     *
     * @param array $item1
     * @param array $item2
     * @return bool
     */
    private function isCartItemEqual(array $item1, array $item2): bool
    {
        return $item1['id'] === $item2['id'] && $item1['options'] === $item2['options'];
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
        $optionValueIds = array_values($options);

        $matchingAttributes = collect($product['product_attributes'])
            ->whereIn('attribute', array_keys($options))
            ->whereIn('value_id', $optionValueIds);

        $priceAdjustment = $matchingAttributes->sum('price_adjustment');

        $price = $product['current_price'];

        if (isset($options['characters'])) {
            $price += self::PRICE_PER_CHARACTER;
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
    public function removeFromCart(string $uniqueId): void
    {
        $cartData = $this->getCartData();

        $cartData = array_filter($cartData, function ($item) use ($uniqueId) {
            return $item['unique_id'] !== $uniqueId;
        });

        $this->updateCartData($cartData);
    }

    /**
     * 清空购物车，移除所有商品。
     *
     * @return void
     */
    public function cartClear(): void
    {
        $this->session->forget('cart_data');
        $this->session->save();
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
     * 调整购物车中商品的数量。
     *
     * @param array $updates 包含唯一标识符和对应数量的关联数组
     * @return void
     * @throws InvalidArgumentException 如果在购物车中找不到指定的唯一标识符对应的商品
     */
    public function adjustItemQuantities(array $updates): void
    {
        $cartData = $this->getCartData();

        // 构建关联数组以加快查找
        $cartItems = [];
        foreach ($cartData as &$cartItem) {
            $cartItems[$cartItem['unique_id']] = &$cartItem;
        }

        foreach ($updates as $uniqueId => $quantity) {
            if (isset($cartItems[$uniqueId])) {
                $cartItems[$uniqueId]['qty'] = $quantity['qty']; // 将数量转换为整数

                if ($cartItems[$uniqueId]['qty'] <= 0) {
                    $this->removeFromCart($uniqueId);
                } else {
                    // 调用重新计算小计的方法
                    $cartItems[$uniqueId]['subtotal'] = $cartItems[$uniqueId]['price'] * $cartItems[$uniqueId]['qty'];
                }
            } else {
                throw new InvalidArgumentException("Item with unique ID {$uniqueId} not found in cart.");
            }
        }

        $this->updateCartData($cartData);
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

        $cartData = array_filter($cartData, function ($item) use ($productId, $options) {
            return !$this->isCartItemEqual($item, ['id' => $productId, 'options' => $options]);
        });

        $this->updateCartData($cartData);
    }
}

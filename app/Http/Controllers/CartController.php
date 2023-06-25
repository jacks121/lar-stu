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
        dd($this->cart->cartList(), 123);
    }
}

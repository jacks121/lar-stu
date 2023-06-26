<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ShoppingCart;

class MiniCart extends Component
{
    public $cart;

    /**
     * Create a new component instance.
     */
    public function __construct(ShoppingCart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('pc.minicart',[
            'cartDataWithSummary' => $this->cart->getCartDataWithSummary()
        ]);
    }
}

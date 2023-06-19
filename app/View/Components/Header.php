<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;
use App\Models\Currency;

class Header extends Component
{
    public $categories;
    public $currencies;

    public function __construct(Category $category, Currency $currency)
    {
        $this->categories = $category->getCategoryTree();
        $this->currencies = $currency->getAllCurrencies();
    }

    public function render()
    {
        return view('components.header');
    }
}

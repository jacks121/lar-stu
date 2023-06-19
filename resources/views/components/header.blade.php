<header class="page-header border-b border-gray-300">
    <div class="header-promo">
        <div id="promo-accordion" class="promo-accordion text-white bg-black text-center"
            data-mage-init='{
"accordion":{
    "active": [],
    "collapsible": true,
    "openedState": "active",
    "icons": {"header": "fa fa-angle-down ml-3 order-1 md:mb-0 mb-2", "activeHeader": "fa fa-angle-up ml-3 order-1 md:mb-0 mb-2"}
}}'>
            <div data-role="collapsible" class="flex justify-center items-end md:items-center">
                <div data-role="trigger" class="py-1 md:py-2 text-base md:text-xl cursor-pointer w-max ">
                    <div class="line-title">
                        <span class="block md:inline-block">Wedding Season Sale!</span> <span>Buy 2 Get 1
                            Free(Code: FREE)</span> <span class="text-sm underline">Details</span>
                    </div>
                    <div class="close-title underline md:text-lg font-semibold">CLOSE </div>
                </div>
            </div>
            <div data-role="content" class="hidden tracking-wider pb-2 text-base md:text-lg">
                <p>Wedding Season Sale!</p>
                <p class="mt-1">Buy 2 Get 1 Free<span class="block text-sm">Use Code: <span
                            class="bg-white text-black px-1 ml-1">FREE</span> </span>
                </p>
                <p class="mt-1">Buy 1 Get 1 50% Off<span class="block text-sm">Use Code: <span
                            class="bg-white text-black px-1 ml-1">WS50</span> </span>
                </p>
                <p class="mt-1">Any Order 10% Off<span class="block text-sm">Use Code: <span
                            class="bg-white text-black px-1 ml-1">WS10</span> </span>
                </p>
            </div>
        </div>
    </div>
    <div class="panel wrapper">
        <div class="panel header flex justify-between items-center">
            <div class="flex justify-start">
                <div class="switcher switcher-currency" id="switcher-currency">
                    <strong class="label switcher-label"><span>Currency</span></strong>
                    <div class="actions dropdown options switcher-options">
                        <div class="action toggle switcher-trigger" id="switcher-currency-trigger"
                            data-mage-init='{"dropdown":{}}' data-toggle="dropdown"
                            data-trigger-keypress-button="true">
                            <strong class="currency currency-USD">
                                <span>USD</span>
                            </strong>
                        </div>
                        <ul class="dropdown switcher-dropdown" data-target="dropdown">
                            @foreach ($currencies as $currency)
                                <li class="switcher-option">
                                    <a class="currency currency-{{ $currency['code'] }}" href="#"
                                        data-post='{"action":"https:\/\/www.stunring.com\/directory\/currency\/switch\/","data":{"currency":"{{ $currency['code'] }}","uenc":"aHR0cHM6Ly93d3cuc3R1bnJpbmcuY29tLw,,"}}'>{{ $currency['code'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
                <div class="email-content">
                    <a href="mailto:service@stunring.com" rel="nofollow"><span
                            class="text-xl fa fa-envelope"></span> service@stunring.com</a>
                </div>
                <div class="fb-like  pl-2 pt-1">
                    <iframe
                        src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FStunring&amp;width=90&amp;layout=button_count&amp;action=like&amp;size=small&amp;share=false&amp;height=21&amp;appId=1836365439867688"
                        width="90" height="21" style="border:none;overflow:hidden" scrolling="no"
                        frameborder="0" allowfullscreen="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                </div>
            </div>
            <div class="flex justify-end">
                <div class="block block-search">
                    <div class="block block-title"><strong>Search</strong></div>
                    <div class="block block-content">
                        <form class="form minisearch" id="search_mini_form"
                            action="https://www.stunring.com/catalogsearch/result/" method="get">
                            <div class="field search">
                                <label class="label" for="search" data-role="minisearch-label">
                                    <span>Search</span>
                                </label>
                                <div class="control">
                                    <input id="search" type="text" name="q" value=""
                                        placeholder="Search&#x20;entire&#x20;store&#x20;here..."
                                        class="input-text" maxlength="128" role="combobox"
                                        aria-haspopup="false" aria-autocomplete="both" autocomplete="off"
                                        aria-expanded="false" />
                                    <div id="search_autocomplete" class="search-autocomplete"></div>
                                </div>
                            </div>
                            <div class="actions">
                                <button type="submit" title="Search" class="action search"
                                    aria-label="Search">
                                    <span>Search</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <script type="text/javascript">
                    require(['jquery'], function($) {
                        $('#search_mini_form .label').click(function(event) {
                            event.preventDefault(); // 获取目标按钮的jQuery对象\n
                            $('form[id="search_mini_form"]').submit();
                        });
                    });
                </script>
                <ul class="header links px-7">
                    <li class="authorization-link">
                        <a
                            href="https://www.stunring.com/customer/account/login/referer/aHR0cHM6Ly93d3cuc3R1bnJpbmcuY29tLw%2C%2C/">
                            <i class="fa fa-user text-xl" aria-hidden="true"></i>
                            Login / Register </a>
                    </li>

                </ul>
                <div class="wishlist px-4" data-bind="scope: 'wishlist'">
                    <a href="https://www.stunring.com/wishlist/">
                        <i class="fa fa-heart text-xl"></i>
                        <!-- ko if: wishlist().counter -->
                        <span data-bind="text: wishlist().counter" class="counter qty"></span>
                        <!-- /ko -->
                    </a>
                </div>
                <script type="text/x-magento-init">
{
"*": {
    "Magento_Ui/js/core/app": {
        "components": {
            "wishlist": {
                "component": "Magento_Wishlist/js/view/wishlist"
            }
        }
    }
}
}

</script>

                <div data-block="minicart" class="minicart-wrapper">
                    <a class="action showcart" href="https://www.stunring.com/checkout/cart/"
                        data-bind="scope: 'minicart_content'">
                        <span class="text">My Cart</span>
                        <span class="counter qty empty"
                            data-bind="css: { empty: !!getCartParam('summary_count') == false && !isLoading() }, blockLoader: isLoading">
                            <span class="counter-number">
                                <!-- ko text: getCartParam('summary_count') -->
                                <!-- /ko -->
                            </span>
                            <span class="counter-label">
                                <!-- ko if: getCartParam('summary_count') -->
                                <!-- ko text: getCartParam('summary_count') -->
                                <!-- /ko -->
                                <!-- ko i18n: 'items' -->
                                <!-- /ko -->
                                <!-- /ko -->
                            </span>
                        </span>
                    </a>
                    <div class="block block-minicart" data-role="dropdownDialog"
                        data-mage-init='{"dropdownDialog":{
        "appendTo":"[data-block=minicart]",
        "triggerTarget":".showcart",
        "timeout": "2000",
        "closeOnMouseLeave": false,
        "closeOnEscape": true,
        "triggerClass":"active",
        "parentClass":"active",
        "buttons":[]}}'>
                        <div id="minicart-content-wrapper" data-bind="scope: 'minicart_content'">
                            <!-- ko template: getTemplate() -->
                            <!-- /ko -->
                        </div>
                    </div>
                    <script>
                        window.checkout = {
                            "shoppingCartUrl": "https:\/\/www.stunring.com\/checkout\/cart\/",
                            "checkoutUrl": "https:\/\/www.stunring.com\/checkout\/",
                            "updateItemQtyUrl": "https:\/\/www.stunring.com\/checkout\/sidebar\/updateItemQty\/",
                            "removeItemUrl": "https:\/\/www.stunring.com\/checkout\/sidebar\/removeItem\/",
                            "imageTemplate": "Magento_Catalog\/product\/image_with_borders",
                            "baseUrl": "https:\/\/www.stunring.com\/",
                            "minicartMaxItemsVisible": 5,
                            "websiteId": "1",
                            "maxItemsToDisplay": 10,
                            "storeId": "1",
                            "storeGroupId": "1",
                            "customerLoginUrl": "https:\/\/www.stunring.com\/customer\/account\/login\/referer\/aHR0cHM6Ly93d3cuc3R1bnJpbmcuY29tLw%2C%2C\/",
                            "isRedirectRequired": false,
                            "autocomplete": "off",
                            "captcha": {
                                "user_login": {
                                    "isCaseSensitive": false,
                                    "imageHeight": 50,
                                    "imageSrc": "",
                                    "refreshUrl": "https:\/\/www.stunring.com\/captcha\/refresh\/",
                                    "isRequired": false,
                                    "timestamp": 1684986901
                                }
                            }
                        };
                    </script>
                    <script type="text/x-magento-init">
{
"[data-block='minicart']": {
    "Magento_Ui/js/core/app": {"components":{"minicart_content":{"children":{"subtotal.container":{"children":{"subtotal":{"children":{"subtotal.totals":{"config":{"display_cart_subtotal_incl_tax":0,"display_cart_subtotal_excl_tax":1,"template":"Magento_Tax\/checkout\/minicart\/subtotal\/totals"},"children":{"subtotal.totals.msrp":{"component":"Magento_Msrp\/js\/view\/checkout\/minicart\/subtotal\/totals","config":{"displayArea":"minicart-subtotal-hidden","template":"Magento_Msrp\/checkout\/minicart\/subtotal\/totals"}}},"component":"Magento_Tax\/js\/view\/checkout\/minicart\/subtotal\/totals"}},"component":"uiComponent","config":{"template":"Magento_Checkout\/minicart\/subtotal"}}},"component":"uiComponent","config":{"displayArea":"subtotalContainer"}},"item.renderer":{"component":"uiComponent","config":{"displayArea":"defaultRenderer","template":"Magento_Checkout\/minicart\/item\/default"},"children":{"item.image":{"component":"Magento_Catalog\/js\/view\/image","config":{"template":"Magento_Catalog\/product\/image","displayArea":"itemImage"}},"checkout.cart.item.price.sidebar":{"component":"uiComponent","config":{"template":"Magento_Checkout\/minicart\/item\/price","displayArea":"priceSidebar"}}}},"extra_info":{"component":"uiComponent","config":{"displayArea":"extraInfo"}},"promotion":{"component":"uiComponent","config":{"displayArea":"promotion"}}},"config":{"itemRenderer":{"default":"defaultRenderer","simple":"defaultRenderer","virtual":"defaultRenderer"},"template":"Magento_Checkout\/minicart\/content"},"component":"Magento_Checkout\/js\/view\/minicart"}},"types":[]}        },
"*": {
    "Magento_Ui/js/block-loader": "https\u003A\u002F\u002Fwww.stunring.com\u002Fstatic\u002Fversion1681280332\u002Ffrontend\u002FSwetelove\u002Fdesktop\u002Fen_US\u002Fimages\u002Floader\u002D1.gif"
}
}
</script>
                </div>


            </div>
        </div>
    </div>
    <div class="header content flex justify-center items-center"><span data-action="toggle-nav"
            class="action nav-toggle"><span>Toggle Nav</span></span>
        <a class="logo" href="index.html" title="" aria-label="store logo">
            <img src="/static/version1681280332/frontend/Swetelove/desktop/en_US/images/logo.png"
                title="" alt="" />
        </a>
        <div class="sections nav-sections">
            <div class="section-items nav-sections-items" data-mage-init='{"tabs":{"openedState":"active"}}'>
                <div class="section-item-title nav-sections-item-title" data-role="collapsible">
                    <a class="nav-sections-item-switch" data-toggle="switch" href="#store.menu">
                        Menu </a>
                </div>
                <div class="section-item-content nav-sections-item-content" id="store.menu"
                    data-role="content">

                    <nav class="navigation" data-action="navigation">
                        <ul
                            data-mage-init='{"menu":{"responsive":true, "expanded":true, "position":{"my":"left top","at":"left bottom"}}}'>
                            <li class="level0 nav-1 category-item first level-top"><a
                                    href="https://www.stunring.com/early-black-friday-sale.html"
                                    class="level-top"><span>Wedding Season Sale</span></a></li>
                            <li class="level0 nav-2 category-item level-top"><a
                                    href="https://www.stunring.com/top-sellers.html"
                                    class="level-top"><span>Top Sellers</span></a></li>
                            <li class="level0 nav-3 category-item level-top"><a
                                    href="https://www.stunring.com/what-s-new.html"
                                    class="level-top"><span>What&#039;s New</span></a></li>
                            @foreach ($categories as $category)
                                <li class="level0 nav-{{ $category['id'] }} category-item level-top parent">
                                    <a href="{{ $category['url'] }}"
                                        class="level-top"><span>{{ $category['category_name'] }}</span></a>
                                    @if (!empty($category['subcategories']))
                                        <ul class="level0 submenu">
                                            @foreach ($category['subcategories'] as $subcategory)
                                                <li
                                                    class="level1 nav-{{ $subcategory['id'] }} category-item{{ $loop->first ? ' first' : '' }}{{ $loop->last ? ' last' : '' }}">
                                                    <a
                                                        href="{{ $subcategory['url'] }}"><span>{{ $subcategory['category_name'] }}</span></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach


                        </ul>
                    </nav>
                </div>
                <div class="section-item-title nav-sections-item-title" data-role="collapsible">
                    <a class="nav-sections-item-switch" data-toggle="switch" href="#store.links">
                        Account </a>
                </div>
                <div class="section-item-content nav-sections-item-content" id="store.links"
                    data-role="content">
                    <!-- Account links -->
                </div>
            </div>
        </div>
    </div>
</header>
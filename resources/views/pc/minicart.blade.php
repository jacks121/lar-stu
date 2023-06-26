@php
    $cartList = $cartDataWithSummary['items'];
@endphp
<div data-block="minicart" class="minicart-wrapper active">
    <a class="action showcart active">
        @if ($cartDataWithSummary['count'] > 0)
            <span class="counter qty">
                <span class="counter-number">
                    {{ $cartDataWithSummary['count'] }}
                </span>
            </span>
        @endif
    </a>
    <div class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-front mage-dropdown-dialog" tabindex="-1"
        role="dialog" aria-describedby="ui-id-1" style="display: none;">
        <div class="block block-minicart ui-dialog-content ui-widget-content" data-role="dropdownDialog" id="ui-id-1"
            style="display: block;">
            <div id="minicart-content-wrapper" data-bind="scope: 'minicart_content'">
                @if (!$cartList)
                    <div class="block-content">
                        <button type="button" id="btn-minicart-close" class="action close" title="Close">
                            <span data-bind="i18n: 'Close'">Close</span>
                        </button>
                        <strong class="subtitle empty">You have no items in your shopping cart.</strong>
                        <div class="subtotal flex items-center justify-center">
                            <span class="label">
                                <span>Subtotal</span>
                            </span>
                            <div class="amount price-container">
                                <span class="price-wrapper">0</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="block-content">
                        <button type="button" id="btn-minicart-close" class="action close" title="Close">
                            <span data-bind="i18n: 'Close'">Close</span>
                        </button>
                        <div data-action="scroll" class="minicart-items-wrapper"
                            style="max-height: 454px; overflow-y: auto;">
                            <ol id="mini-cart" class="minicart-items">
                                @foreach ($cartList as $item)
                                    <li class="item product product-item" data-role="product-item"
                                        data-collapsible="true">
                                        <div class="product flex items-start justify-between">
                                            <a tabindex="-1"
                                                class="product-item-photo w-max mt-2 border border-gray-400"
                                                href="{{ $item['image_url'] }}" title="{{ $item['name'] }}">
                                                <span class="product-image-container" style="width: 80px;">
                                                    <span class="product-image-wrapper" style="padding-bottom: 100%;">
                                                        <img class="product-image-photo" src="{{ $item['image_url'] }}"
                                                            alt="{{ $item['name'] }}"
                                                            style="width: 80px; height: 80px;">
                                                    </span>
                                                </span>
                                            </a>
                                            <div class="product-item-details w-2/3">
                                                <strong class="product-item-name">
                                                    <a data-bind="attr: {href: product_url}, html: product_name"
                                                        href="{{ $item['image_url'] }}">{{ $item['name'] }}</a>
                                                </strong>
                                                @if (!empty($item['options']) && array_filter($item['options']))
                                                    <div class="product options" role="tablist" data-collapsible="true">
                                                        <span data-role="title" class="toggle" role="tab"
                                                            aria-selected="false" aria-expanded="false" tabindex="0">
                                                            <span>See Details</span>
                                                        </span>

                                                        <div data-role="content" class="content" role="tabpanel"
                                                            aria-hidden="true" style="display: none;">
                                                            <strong class="subtitle">
                                                                <span>Options Details</span>
                                                            </strong>
                                                            <dl class="product options list">
                                                                @foreach ($item['options'] as $option => $value)
                                                                    @if (!empty($value))
                                                                        <dt class="label">
                                                                            {{ $option }}
                                                                        </dt>
                                                                        <dd class="values">
                                                                            <span
                                                                                data-bind="text: option.value">{{ $value }}</span>
                                                                        </dd>
                                                                    @endif
                                                                @endforeach
                                                            </dl>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="product-item-pricing flex text-sm mt-2">
                                                    <div class="qty">
                                                        <span data-bind="text:qty">{{ $item['qty'] }}</span>
                                                        <span>X</span>
                                                    </div>
                                                    <div class="price-container">
                                                        <span class="price-wrapper" data-bind="html: price"> <span
                                                                class="price-wrapper price-excluding-tax"
                                                                data-label="Excl. Tax">
                                                                <span class="price">${{ $item['price'] }}</span>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="product actions">
                                                    <div class="secondary">
                                                        <a href="#" title="Remove item" class="action delete"
                                                            data-post='{"action":"{!! route('cart.delete') !!}","data":{"id":"96147","_token":"{{ csrf_token() }}","uenc":"{{ $item['unique_id'] }}","confirmation":true,"confirmationMessage":"Are you sure to remove the item?"}}'>
                                                            <span>Remove item</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                        <div class="subtotal flex items-center justify-center">
                            <span class="label">
                                <span>Subtotal</span>
                            </span>
                            <div class="amount price-container">
                                <span class="price-wrapper"><span
                                        class="price">${{ $cartDataWithSummary['subtotal'] }}</span></span>
                            </div>
                        </div>
                        <div class="actions">
                            <div class="primary">
                                <a class="action viewcart" href="{{ route('cart.show') }}">
                                    <i class="fa fa-lock"></i>
                                    <span data-bind="i18n: 'View Cart'">View Cart</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    require(['jquery'], function($) {
        // 等待文档加载完成
        $(document).ready(function() {
            // 获取相关元素
            var showcartLink = $(".action.showcart");
            var dialogWrapper = $(".ui-dialog");

            // 定义小购物车的显示状态变量
            var isMinicartOpen = false;

            // 点击显示/关闭小购物车
            showcartLink.on("click", function() {
                if (isMinicartOpen) {
                    // 如果小购物车已经打开，则关闭它
                    dialogWrapper.hide();
                    isMinicartOpen = false;
                } else {
                    // 如果小购物车已经关闭，则显示它
                    // 在显示小购物车之前，先通过 AJAX 获取最新的购物车数据
                    window.updateMiniCart();

                    dialogWrapper.show();
                    isMinicartOpen = true;
                }
            });

            // 定义 updateMiniCart 为全局函数
            window.updateMiniCart = function() {
                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: 'GET',
                    success: function(data) {
                        $('#minicart-content-wrapper').html(data.cartListHtml);
                        $('.counter.qty .counter-number').text(data.cartCount);
                        $('.amount.price-container .price-wrapper .price').text(data.cartSubtotal);
                    }
                });
            };

            // 使用事件委托来监听动态添加的关闭按钮
            $(document).on("click", "#btn-minicart-close", function() {
                dialogWrapper.hide();
                isMinicartOpen = false;
            });

            // 使用事件委托来监听动态添加的 "See Details" 链接
            $(document).on("click", ".toggle", function() {
                // 获取当前 "See Details" 链接对应的详情元素
                var detailsContent = $(this).next(".content");

                // 使用 slideToggle 方法在显示和隐藏之间切换
                detailsContent.slideToggle();
            });

            // 点击页面其他地方关闭小购物车
            $(document).on("click", function(event) {
                var target = $(event.target);

                // 检查点击事件的目标元素是否在小购物车以外的区域
                if (!target.closest(".minicart-wrapper").length && !target.closest(".ui-dialog")
                    .length) {
                    // 关闭小购物车
                    dialogWrapper.hide();
                    isMinicartOpen = false;
                }
            });
        });
    });
</script>

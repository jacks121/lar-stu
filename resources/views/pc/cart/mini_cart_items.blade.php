@php
    $cartList = $cartDataWithSummary['items'];
@endphp
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
                                    <a href="#" title="Remove item"
                                        class="action delete"
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
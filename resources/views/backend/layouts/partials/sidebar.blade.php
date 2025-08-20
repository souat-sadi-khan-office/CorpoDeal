<aside class="app-sidebar bg-body shadow" data-bs-theme="dark">
    <div class="sidebar-brand">

        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img
                src="{{ get_settings('system_logo_white') ? asset(get_settings('system_logo_white')) : asset('pictures/default-logo-white.png') }}"
                alt="App Logo" class="brand-image">
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <!-- Offline Order -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('offline-order'))
                    <li class="nav-item">
                        <a href="{{ route('admin.offline-order') }}" class="nav-link {{ Request::is('admin/offline-order') ? ' active' : '' }}">
                            <i class="nav-icon bi-bag-check"></i>
                            <p>
                                Offline Orders
                            </p>
                        </a>
                    </li>
                @endif
            <!-- Order -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('all-order.view') || Auth::guard('admin')->user()->hasPermissionTo('pending-order.view') || Auth::guard('admin')->user()->hasPermissionTo('packaging-order.view') || Auth::guard('admin')->user()->hasPermissionTo('shipping-order.view') || Auth::guard('admin')->user()->hasPermissionTo('confirm-order.view') || Auth::guard('admin')->user()->hasPermissionTo('delivered-order.view') || Auth::guard('admin')->user()->hasPermissionTo('returned-order.view') || Auth::guard('admin')->user()->hasPermissionTo('failed-order.view') || Auth::guard('admin')->user()->hasPermissionTo('refund-requested-order.view'))
                    <li class="nav-item {{ Request::is('admin/orders*') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-basket2-fill"></i>
                            <p>
                                Orders
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('all-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index') }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == null && Request::get('refund_requested') == null ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-basket2-fill"></i>
                                        <p>All Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('pending-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'pending']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == 'pending' ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-arrow-clockwise"></i>
                                        <p>Pending Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('packaging-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'packaging']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == 'packaging' ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-box2"></i>
                                        <p>Packaging Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('shipping-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'shipping']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == 'shipping' ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-rocket-takeoff"></i>
                                        <p>Shipping Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('confirm-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'out_of_delivery']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == 'out_of_delivery' ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-truck"></i>
                                        <p>Confirmed Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('delivered-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'delivered']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == 'delivered' ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-check-square"></i>
                                        <p>Delivered Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('returned-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'returned']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == 'returned' ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-arrow-return-left"></i>
                                        <p>Returned Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('failed-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'failed']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('status') == 'failed' ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-slash-square"></i>
                                        <p>Failed Orders</p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('refund-requested-order.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index', ['status' => 'refund_requested']) }}"
                                       class="nav-link {{ Request::is('admin/orders') && Request::get('refund_requested') != null ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-credit-card-2-front"></i>
                                        <p>Refund Requested Orders</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endcan

                <!-- Categories -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('category.view') || Auth::guard('admin')->user()->hasPermissionTo('category-banner.view'))
                    <li class="nav-item {{ Request::is('admin/categories') || Request::is('admin/categories/add') || Request::is('admin/categories/sub') || Request::is('admin/category-banner*') || Request::is('admin/categories/sub/add') || Request::is('admin/categories/edit*') || Request::is('admin/categories/keys*') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-columns-gap"></i>
                            <p>
                                Categories
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">

                            @if(Auth::guard('admin')->user()->hasPermissionTo('category.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.index') }}" class="nav-link {{ Request::is('admin/categories') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-bookmark-star-fill"></i>
                                        <p>
                                            Primary Categories
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('category.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.index.sub') }}" class="nav-link {{ Request::is('admin/categories/sub') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-bookmarks-fill"></i>
                                        <p>
                                            Sub Categories
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('category.create'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.add') }}" class="nav-link {{ Request::is('admin/categories/add') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-plus-circle"></i>
                                        <p>
                                            Add Category
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('category.create'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.sub.add') }}" class="nav-link {{ Request::is('admin/categories/sub/add') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-plus-circle"></i>
                                        <p>
                                            Add Sub Category
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('category-banner.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category-banner.index') }}" class="nav-link {{ Request::is('admin/category-banner*') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-file-image"></i>
                                        <p>Banners</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('specification-key.view') || Auth::guard('admin')->user()->hasPermissionTo('specification-types.view') || Auth::guard('admin')->user()->hasPermissionTo('specification-type-attribute.view'))
                    <li class="nav-item {{ Request::is('admin/categories/specification/keys/public') || Request::is('admin/categories/specification/keys') || Request::is('admin/categories/specification/types') || Request::is('admin/categories/specification/types/attributes/*')  ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-gear-wide-connected"></i>
                            <p>
                                Specification Keys
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left:10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('specification-key.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.specification.key.public') }}" class="nav-link {{ Request::is('admin/categories/specification/keys/public') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-command"></i>
                                        <p>
                                            Public Keys
                                        </p>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermissionTo('specification-key.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.specification.key.index') }}" class="nav-link {{ Request::is('admin/categories/specification/keys') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-plus-circle"></i>
                                        <p>
                                            Keys
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('specification-types.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.specification.type.index') }}" class="nav-link {{ Request::is('admin/categories/specification/types') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-plus-circle"></i>
                                        <p>
                                            Key Types
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('specification-type-attribute.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.category.specification.type.attribute.index') }}" class="nav-link {{ Request::is('admin/categories/specification/types/attributes/*') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-plus-circle"></i>
                                        <p>
                                            Type attributes
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Product -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('product.view') || Auth::guard('admin')->user()->hasPermissionTo('product.specification'))
                    <li class="nav-item {{ Request::is('admin/product*')? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-cart2"></i>
                            <p>
                                Products
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('product.create'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.product.create') }}"
                                    class="nav-link {{ Request::is('admin/products/create') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-bag-plus"></i>
                                        <p>Add New Product</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('product.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.product.index') }}"
                                    class="nav-link {{ Request::is('admin/product') || Request::is('admin/product/*') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-shop-window"></i>
                                        <p>Products</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('product.specification'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.product.specification.edit') }}"
                                    class="nav-link {{ Request::is('admin/products/specifications/*') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-layers"></i>
                                        <p>Specification Controls</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('stock.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.stock.index') }}"
                        class="nav-link {{ Request::is('admin/stock') || Request::is('admin/stock/*') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-archive"></i>
                            <p>Stock</p>
                        </a>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('flash-deal.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.flash-deal.index') }}"
                        class="nav-link {{ Request::is('admin/flash-deal*') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-amd"></i>
                            <p>Flash Deals</p>
                        </a>
                    </li>
                @endcan

                <!-- Banner -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('banner.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.banner.index') }}"
                        class="nav-link {{ Request::is('admin/banner') || Request::is('admin/banner/*') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-images"></i>
                            <p>Banner</p>
                        </a>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('coupon.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.coupon.index') }}"
                        class="nav-link {{ Request::is('admin/coupon') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-tags"></i>
                            <p>Coupon</p>
                        </a>
                    </li>
                @endcan

                <!-- Bulk Import -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('bulk-import.category') || Auth::guard('admin')->user()->hasPermissionTo('bulk-import.brand') || Auth::guard('admin')->user()->hasPermissionTo('bulk-import.product') || Auth::guard('admin')->user()->hasPermissionTo('bulk-import.stock') || Auth::guard('admin')->user()->hasPermissionTo('bulk-import.specification'))
                    <li class="nav-item {{ Request::is('admin/import*') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-cloud-upload"></i>
                            <p>
                                Bulk Import
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('bulk-import.category'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.import.category') }}"
                                    class="nav-link {{ Request::is('admin/import/category') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-basket2-fill"></i>
                                        <p>Categories</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('bulk-import.brand'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.import.brand') }}"
                                    class="nav-link {{ Request::is('admin/import/brand') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-arrow-clockwise"></i>
                                        <p>Brands</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('bulk-import.product'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.import.product') }}"
                                    class="nav-link {{ Request::is('admin/import/product') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-box2"></i>
                                        <p>Products</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('bulk-import.stock'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.import.product.stock') }}"
                                    class="nav-link {{ Request::is('admin/import/product/stock') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-archive"></i>
                                        <p>Product Stock</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('bulk-import.specification'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.import.product.specification') }}"
                                    class="nav-link {{ Request::is('admin/import/product/specification') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-archive"></i>
                                        <p>Product Specification</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('qr-cart-suggestion'))
                    <li class="nav-item">
                        <a href="{{ route('admin.qr.generate.form') }}"
                        class="nav-link {{ Request::is('admin/generate-qr') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-qr-code-scan"></i>
                            <p>Cart Suggestion (QR)</p>
                        </a>
                    </li>
                @endcan


                <!-- Customer -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('customer.view') || Auth::guard('admin')->user()->hasPermissionTo('customer-review.view') || Auth::guard('admin')->user()->hasPermissionTo('customer-question.view') || Auth::guard('admin')->user()->hasPermissionTo('customer-review.view'))
                    <li class="nav-item {{ Request::is('admin/customer*') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-people"></i>
                            <p>
                                Customers
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('customer.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.customer.index') }}"
                                    class="nav-link {{ Request::is('admin/customer') || Request::is('admin/customer/view*')  ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-person-bounding-box"></i>
                                        <p>All Customers</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('customer-question.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.customer.question.index') }}"
                                    class="nav-link {{ Request::is('admin/customer/question') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-patch-question"></i>
                                        <p>Questions</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('customer-review.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.customer-review.index') }}"
                                    class="nav-link {{ Request::is('admin/customer/review') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-chat-dots"></i>
                                        <p>Reviews</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Brands -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('brand.view') || Auth::guard('admin')->user()->hasPermissionTo('brand-type.view'))
                    <li
                        class="nav-item {{ Request::is('admin/brand*') || Request::is('admin/brand-type') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-ubuntu"></i>
                            <p>
                                Brands
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('brand.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.brand.index') }}"
                                    class="nav-link {{ Request::is('admin/brand') || Request::is('admin/brand/*') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-unity"></i>
                                        <p>Brand</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('brand-type.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.brand-type.index') }}"
                                    class="nav-link {{ Request::is('admin/brand-type') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-strava"></i>
                                        <p>Brand Types</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{--Reports--}}
                @if(Auth::guard('admin')->user()->hasPermissionTo('product-sale-report.view') || Auth::guard('admin')->user()->hasPermissionTo('order-report.view') || Auth::guard('admin')->user()->hasPermissionTo('stock-purchase-report.view') || Auth::guard('admin')->user()->hasPermissionTo('transaction-report.view') )
                    <li class="nav-item {{ Request::is('admin/reports*') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-flag"></i>
                            <p>
                                Reports
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('product-sale-report.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.productsSell') }}"
                                    class="nav-link {{ Request::is('admin/reports/productsSell') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-award"></i>
                                        <p>Product Sell Report</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('order-report.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.orderReport') }}"
                                    class="nav-link {{ Request::is('admin/reports/orders') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-bag"></i>
                                        <p>Orders Report</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('stock-purchase-report.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.report.stockPurchase') }}"
                                    class="nav-link {{ Request::is('admin/reports/stockPurchase') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-kanban"></i>
                                        <p>Stock Purchase Report</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('transaction-report.view'))
                                <li class="nav-item {{ Request::is('admin/reports/transactions*') ? 'menu-open' : '' }}">
                                    <a href="javascript:;" class="nav-link">
                                        <i class="nav-icon bi bi-wallet"></i>
                                        <p>
                                            Transactions Reports
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.report.transaction','cod') }}"
                                            class="nav-link {{ Request::is('admin/reports/transactions/cod') ? ' active' : '' }}">
                                                <i class="nav-icon bi bi-truck"></i>
                                                <p>Cash On Delivery</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.report.transaction','gateway') }}"
                                            class="nav-link {{ Request::is('admin/reports/transactions/gateway') ? ' active' : '' }}">
                                                <i class="nav-icon bi bi-credit-card-2-front"></i>
                                                <p>Gateway</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Pricing Tier -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('pricing-tier.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.pricing-tier.index') }}"
                        class="nav-link {{ Request::is('admin/pricing-tier*') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-gift-fill"></i>
                            <p>
                                Pricing Tier
                            </p>
                        </a>
                    </li>
                @endcan

                <!-- WishList -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('wishlist.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.report.wishlist') }}"
                        class="nav-link {{ Request::is('admin/report/wishlist*') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-heart"></i>
                            <p>
                                WishList
                            </p>
                        </a>
                    </li>
                @endcan

                <!-- Website Setup -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('website-setup.home-page-category') || Auth::guard('admin')->user()->hasPermissionTo('website-setup.header') || Auth::guard('admin')->user()->hasPermissionTo('website-setup.footer') || Auth::guard('admin')->user()->hasPermissionTo('website-setup.page-management') || Auth::guard('admin')->user()->hasPermissionTo('website-setup.appearance') || Auth::guard('admin')->user()->hasPermissionTo('website-setup.seo'))
                    <li class="nav-item {{ Request::is('admin/website*') || Request::is('admin/page*') || Request::is('admin/home-page-category*') || Request::is('admin/settings/seo/*') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-globe"></i>
                            <p>
                                Website Setup
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('website-setup.home-page-category'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.home-page-category.index') }}" class="nav-link {{ Request::is('admin/home-page-category') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-lightning"></i>
                                        <p>Home Page Category</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('website-setup.header'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.website.header') }}" class="nav-link {{ Request::is('admin/website/header') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-window-fullscreen"></i>
                                        <p>Header</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('website-setup.footer'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.website.footer') }}"
                                    class="nav-link {{ Request::is('admin/website/footer') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-window-desktop"></i>
                                        <p>Footer</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('website-setup.page-management'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.page.index') }}"
                                    class="nav-link {{ Request::is('admin/page*') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-window-stack"></i>
                                        <p>Pages</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('website-setup.appearance'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.website.appearance') }}"
                                    class="nav-link {{ Request::is('admin/website/appearance') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-window-split"></i>
                                        <p>Appearance</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('website-setup.seo'))
                                <li class="nav-item {{ Request::is('admin/settings/seo/*') ? 'menu-open' : '' }} ">
                                    <a href="javascript:;" class="nav-link">
                                        <i class="bi bi-globe-americas nav-icon"></i>
                                        <p>
                                            SEO
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul style="margin-left: 10px;" class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'home') }}" class="nav-link {{ Request::is('admin/settings/seo/home') ? ' active' : '' }}">
                                                <i class="bi bi-house nav-icon"></i>
                                                <p>Home Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'login') }}" class="nav-link {{ Request::is('admin/settings/seo/login') ? ' active' : '' }}">
                                                <i class="bi bi-box-arrow-in-left nav-icon"></i>
                                                <p>Login Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'register') }}" class="nav-link {{ Request::is('admin/settings/seo/register') ? ' active' : '' }}">
                                                <i class="bi bi-r-circle nav-icon"></i>
                                                <p>Register Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'forget') }}" class="nav-link {{ Request::is('admin/settings/seo/forget') ? ' active' : '' }}">
                                                <i class="bi bi-app-indicator nav-icon"></i>
                                                <p>Forget Password Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'contact') }}" class="nav-link {{ Request::is('admin/settings/seo/contact') ? ' active' : '' }}">
                                                <i class="bi bi-chat-left nav-icon"></i>
                                                <p>Contact Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'pc_builder') }}" class="nav-link {{ Request::is('admin/settings/seo/pc_builder') ? ' active' : '' }}">
                                                <i class="bi bi-sliders nav-icon"></i>
                                                <p>PC Builder Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'all_categories') }}" class="nav-link {{ Request::is('admin/settings/seo/all_categories') ? ' active' : '' }}">
                                                <i class="bi bi-balloon nav-icon"></i>
                                                <p>All Categories Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'all_brand') }}" class="nav-link {{ Request::is('admin/settings/seo/all_brand') ? ' active' : '' }}">
                                                <i class="bi bi-balloon-fill nav-icon"></i>
                                                <p>All Brands Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'laptop_buying_guide') }}" class="nav-link {{ Request::is('admin/settings/seo/laptop_buying_guide') ? ' active' : '' }}">
                                                <i class="bi bi-laptop nav-icon"></i>
                                                <p>Laptop Buying Guide Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'track_order') }}" class="nav-link {{ Request::is('admin/settings/seo/track_order') ? ' active' : '' }}">
                                                <i class="bi bi-sign-railroad nav-icon"></i>
                                                <p>Track Order Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'track_order_result') }}" class="nav-link {{ Request::is('admin/settings/seo/track_order_result') ? ' active' : '' }}">
                                                <i class="bi bi-sign-railroad-fill nav-icon"></i>
                                                <p>Track Order Result Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'compare') }}" class="nav-link {{ Request::is('admin/settings/seo/compare') ? ' active' : '' }}">
                                                <i class="bi bi-crosshair nav-icon"></i>
                                                <p>Compare Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'cart') }}" class="nav-link {{ Request::is('admin/settings/seo/cart') ? ' active' : '' }}">
                                                <i class="bi bi-bag nav-icon"></i>
                                                <p>Cart Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'checkout') }}" class="nav-link {{ Request::is('admin/settings/seo/checkout') ? ' active' : '' }}">
                                                <i class="bi bi-credit-card nav-icon"></i>
                                                <p>Checkout Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'order_confirmation') }}" class="nav-link {{ Request::is('admin/settings/seo/order_confirmation') ? ' active' : '' }}">
                                                <i class="bi bi-check-circle-fill nav-icon"></i>
                                                <p>Order Confirmation Page</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.settings.seo', 'flash_deals') }}" class="nav-link {{ Request::is('admin/settings/seo/flash_deals') ? ' active' : '' }}">
                                                <i class="nav-icon bi bi-amd nav-icon"></i>
                                                <p>Flash Deals Page</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Setup & Configuration -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('settings.general-settings') || Auth::guard('admin')->user()->hasPermissionTo('settings.homepage-settings') || Auth::guard('admin')->user()->hasPermissionTo('settings.currency') || Auth::guard('admin')->user()->hasPermissionTo('settings.vat-tax') || Auth::guard('admin')->user()->hasPermissionTo('settings.email-templates') || Auth::guard('admin')->user()->hasPermissionTo('settings.sms-templates'))
                    <li
                        class="nav-item {{ Request::is('admin/settings/*') || Request::is('admin/currency') || Request::is('admin/homepage/*') || Request::is('admin/tax') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link">
                            <i class="nav-icon bi bi-gear"></i>
                            <p>
                                Settings
                                @if (get_settings('default_laptop_category') == null)
                                    <span class="p-3 bg-danger">Alert</span>
                                @endif
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.general-settings'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings.general') }}"
                                    class="nav-link {{ Request::is('admin/settings/general') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-gear"></i>
                                        <p>
                                            General Settings

                                            @if (get_settings('default_laptop_category') == null)
                                                <span class="p-3 bg-danger">Alert</span>
                                            @endif
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.homepage-settings'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.homepage.settings') }}"
                                    class="nav-link {{ Request::is('admin/homepage/*') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-house-gear"></i>
                                        <p>Homepage Settings</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.currency'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.currency.index') }}"
                                    class="nav-link {{ Request::is('admin/currency') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-currency-exchange"></i>
                                        <p>Currency</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.vat-tax'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.tax.index') }}"
                                    class="nav-link {{ Request::is('admin/tax') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-piggy-bank-fill"></i>
                                        <p>VAT & Tax</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.email-templates'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings.email') }}"
                                    class="nav-link {{ Request::is('admin/settings/email') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-envelope-paper-fill"></i>
                                        <p>Mail Templates</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.sms-templates'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.settings.otp') }}"
                                    class="nav-link {{ Request::is('admin/settings/otp') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-envelope-paper-fill"></i>
                                        <p>OTP Templates</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                <!-- Laptop Buying Guide -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.offer-page') || Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.finder-page') || Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.budget') || Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.purpose') || Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size') || Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.portability') || Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.features'))
                    <li
                        class="nav-item {{ Request::is('admin/laptop*') ? 'menu-open' : '' }}">
                        <a href="javascript:;" class="nav-link ">
                            <i class="nav-icon bi bi-laptop"></i>
                            <p>
                                Laptop Finder
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul style="margin-left: 10px;" class="nav nav-treeview">
                            @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.offer-page'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.laptop.offer-page-seo') }}"
                                    class="nav-link {{ Request::is('admin/laptop/offer-page-seo') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-globe"></i>
                                        <p>Offer Page SEO</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.finder-page'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.laptop.finder-page-seo') }}"
                                    class="nav-link {{ Request::is('admin/laptop/finder-page-seo') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-globe"></i>
                                        <p>Finder Page SEO</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.budget'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.laptop.budget.index') }}"
                                    class="nav-link {{ Request::is('admin/laptop/budget') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-coin"></i>
                                        <p>Budget</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.purpose'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.laptop.purpose.index') }}"
                                    class="nav-link {{ Request::is('admin/laptop/purpose') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-question-circle"></i>
                                        <p>Purpose</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.screen-size'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.laptop.screen.index') }}"
                                    class="nav-link {{ Request::is('admin/laptop/screen') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-fullscreen"></i>
                                        <p>Screen Size</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.portability'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.laptop.portability.index') }}"
                                    class="nav-link {{ Request::is('admin/laptop/portability') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-person-wheelchair"></i>
                                        <p>Portability</p>
                                    </a>
                                </li>
                            @endcan

                            @if(Auth::guard('admin')->user()->hasPermissionTo('laptop-finder.features'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.laptop.features.index') }}"
                                    class="nav-link {{ Request::is('admin/laptop/features') ? ' active' : '' }}">
                                        <i class="nav-icon bi bi-lightbulb-fill"></i>
                                        <p>Features</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Shipping Configuration -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.update') || Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.zone') || Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.country') || Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.city') || Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier'))
                <li
                    class="nav-item {{ Request::is('admin/carrier*') || Request::is('admin/shipping-configuration') || Request::is('admin/zone') || Request::is('admin/city') || Request::is('admin/country') ? 'menu-open' : '' }}">
                    <a href="javascript:;" class="nav-link ">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>
                            Shipping
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul style="margin-left: 10px;" class="nav nav-treeview">
                        @if (Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.update'))
                            <li class="nav-item">
                                <a href="{{ route('admin.shipping.configuration') }}" class="nav-link {{ Request::is('admin/shipping-configuration') ? ' active' : '' }}">
                                    <i class="nav-icon bi bi-sliders"></i>
                                    <p>Shipping Configuration</p>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.zone'))
                            <li class="nav-item">
                                <a href="{{ route('admin.zone.index') }}"
                                class="nav-link {{ Request::is('admin/zone') ? ' active' : '' }}">
                                    <i class="nav-icon bi bi-radar"></i>
                                    <p>Zones</p>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.country'))
                            <li class="nav-item">
                                <a href="{{ route('admin.country.index') }}"
                                class="nav-link {{ Request::is('admin/country') ? ' active' : '' }}">
                                    <i class="nav-icon bi bi-map"></i>
                                    <p>Countries</p>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.city'))
                            <li class="nav-item">
                                <a href="{{ route('admin.city.index') }}"
                                class="nav-link {{ Request::is('admin/city') ? ' active' : '' }}">
                                    <i class="nav-icon bi bi-pin-map"></i>
                                    <p>Cities</p>
                                </a>
                            </li>
                        @endif

                        @if(Auth::guard('admin')->user()->hasPermissionTo('shipping-configuration.carrier'))
                            <li class="nav-item">
                                <a href="{{ route('admin.carrier.index') }}"
                                class="nav-link {{ Request::is('admin/carrier*') ? ' active' : '' }}">
                                    <i class="nav-icon bi bi-rocket"></i>
                                    <p>Carrier</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('send-mail'))
                    <li class="nav-item">
                        <a href="{{ route('admin.mail.index') }}"
                        class="nav-link {{ Request::is('admin/push_mails') ? ' active' : '' }}">
                            <i class=" nav-icon bi bi-envelope"></i>
                            <p>
                                Push Emails
                            </p>
                        </a>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('installment-plans.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.installment.index') }}"
                        class="nav-link {{ Request::is('admin/installments/plans') ? ' active' : '' }}">
                            <i class=" nav-icon bi bi-palette2"></i>
                            <p>
                                Installment Plans
                            </p>
                        </a>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('installment-plans.view-balance-request'))
                    <li class="nav-item">
                        <a href="{{ route('admin.balance.request') }}"
                        class="nav-link {{ Request::is('admin/balance/requests') ? ' active' : '' }}">
                            <i class=" nav-icon bi bi-palette2"></i>
                            <p>
                            Balance Request
                            </p>
                        </a>
                    </li>
                @endcan

                <!-- contact message -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('contact-message.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.contact.message') }}"
                        class="nav-link {{ Request::is('admin/contact-message') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-chat"></i>
                            <p>
                                Contact Messages
                            </p>
                        </a>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('activity-log.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.activity.log') }}"
                        class="nav-link {{ Request::is('admin/activity-logs') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-radioactive"></i>
                            <p>
                                Activity Logs
                            </p>
                        </a>
                    </li>
                @endcan

                <!-- Staff & Permission -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('stuff.view') || Auth::guard('admin')->user()->hasPermissionTo('roles.view'))
                <li class="nav-item {{ Request::is('admin/stuff*') || Request::is('admin/roles*') ? 'menu-open' : '' }}">
                    <a href="javascript:;" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>
                            Staffs
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul style="margin-left: 10px;" class="nav nav-treeview">
                        @if(Auth::guard('admin')->user()->hasPermissionTo('stuff.view'))
                            <li class="nav-item">
                                <a href="{{ route('admin.stuff.index') }}"
                                class="nav-link {{ Request::is('admin/stuff*') ? ' active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>All Staffs</p>
                                </a>
                            </li>
                        @endif

                        @if(Auth::guard('admin')->user()->hasPermissionTo('roles.view'))
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}"
                                class="nav-link {{ Request::is('admin/roles*') ? ' active' : '' }}">
                                    <i class="nav-icon bi bi-person-gear"></i>
                                    <p>Staff Permission</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endcan

                <!-- System -->
                @if(Auth::guard('admin')->user()->hasPermissionTo('gateway-configuration.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.system_server') }}"
                       class="nav-link {{ Request::is('admin/server-status') ? ' active' : '' }}">
                        <i class="nav-icon bi bi-hdd"></i>
                        <p>
                            System
                        </p>
                    </a>
                </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('image-upload.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.image.index') }}"
                        class="nav-link {{ Request::is('admin/image-upload') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-cloud-upload"></i>
                            <p>
                                Image Upload
                            </p>
                        </a>
                    </li>
                @endcan

                @if(Auth::guard('admin')->user()->hasPermissionTo('gateway-configuration.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.gateway.configuration') }}"
                        class="nav-link {{ Request::is('admin/gateway-configuration') ? ' active' : '' }}">
                            <i class="nav-icon bi bi-sign-turn-right"></i>
                            <p>
                                Gateway Configuration
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>

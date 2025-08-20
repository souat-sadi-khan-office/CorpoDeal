<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\ChartController;
use App\Http\Controllers\Admin\InstallmentPlanController;
use App\Http\Controllers\Admin\PushEmailController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\PricingTierController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\CarrierController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\StuffController;
use App\Http\Controllers\Admin\HelperController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\BrandTypeController;
use App\Http\Controllers\Admin\FlashDealController;
use App\Http\Controllers\Admin\SpecificationsTypes;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\SpecificationAttributes;
use App\Http\Controllers\Admin\SpecificationsController;
use App\Http\Controllers\Admin\ConfigurationSettingController;
use App\Http\Controllers\Admin\ProductStockController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GatewayConfigurationController;
use App\Http\Controllers\Admin\LaptopBudgetController;
use App\Http\Controllers\Admin\LaptopFinderFeaturesController;
use App\Http\Controllers\Admin\LaptopFinderPortabilityController;
use App\Http\Controllers\Admin\LaptopFinderScreenController;
use App\Http\Controllers\Admin\LaptopFinderPurposeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\CategoryBannerController;
use App\Http\Controllers\Admin\HomePageCategoryController;
use App\Http\Controllers\CustomerReviewController;
use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\SearchController;
use App\Http\Middleware\CheckPermission;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login/post', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['isAdmin', 'web'])->group(function () {

    Route::get('my-profile',  [AdminController::class, 'profile'])->name('profile');
    Route::patch('update-profile',  [AdminController::class, 'profileUpdate'])->name('update.profile');
    Route::patch('update-password',  [AdminController::class, 'passwordUpdate'])->name('update.password');

    Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/generate-qr', [QrCodeController::class, 'showGenerateQrForm'])->name('qr.generate.form');
    Route::post('/generate-qr', [QrCodeController::class, 'generateQrCode'])->name('qr.generate');

    Route::get('offline-order', [AdminController::class, 'offlineOrder'])->name('offline-order');
    Route::post('offline-order-create', [AdminController::class, 'offlineOrderStore'])->name('offline-order-create');

    Route::get('contact-message', [AdminController::class, 'contactMessage'])->name('contact.message');
    Route::get('contact-message/view/{id}', [AdminController::class, 'viewContactMessage'])->name('message.view');
    Route::post('contact-message/status/{id}', [AdminController::class, 'updateMessageStatus'])->name('message.status');
    Route::delete('contact-message/delete/{id}', [AdminController::class, 'deleteContactMessage'])->name('message.destroy');

    Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {
        Route::get('add', [CategoryController::class, 'addform'])->name('add');
        Route::get('sub/add', [CategoryController::class, 'addformsub'])->name('sub.add');
        Route::any('store', [CategoryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::any('delete/{id}', [CategoryController::class, 'delete'])->name('delete');
        Route::any('update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('sub', [CategoryController::class, 'indexsub'])->name('index.sub');
        Route::get('keys/{id}', [CategoryController::class, 'categoryKeys'])->name('keys');

        Route::group(['prefix' => 'specification', 'as' => 'specification.'], function () {
            Route::get('keys', [SpecificationsController::class, 'index'])->name('key.index');
            Route::get('keys/public', [SpecificationsController::class, 'publickeys'])->name('key.public');
            Route::get('key/create', [SpecificationsController::class, 'create'])->name('key.create');
            Route::post('key/store', [SpecificationsController::class, 'store'])->name('key.store');
            Route::patch('key/update/{id}', [SpecificationsController::class, 'update'])->name('key.update');
            Route::get('key/show/{id}', [SpecificationsController::class, 'show'])->name('key.show');
            Route::post('status/{id}', [SpecificationsController::class, 'updatestatus'])->name('key.status');
            Route::post('public/{id}', [SpecificationsController::class, 'updateIsPublic'])->name('key.is_public');
            Route::post('updateposition/{id}', [SpecificationsController::class, 'updateposition'])->name('key.position');
            Route::any('delete/{id}', [SpecificationsController::class, 'delete'])->name('key.delete');
            Route::get('types/{id}', [SpecificationsController::class, 'keyTypes'])->name('types');

            // don't remove from here, otherwise it will create 302 redirection somehow
            Route::get('type-create', [SpecificationsTypes::class, 'create'])->name('type.create');

            Route::group(['prefix' => 'types', 'as' => 'type.'], function () {
                Route::get('/', [SpecificationsTypes::class, 'index'])->name('index');

                Route::post('store', [SpecificationsTypes::class, 'store'])->name('store');
                Route::patch('update/{id}', [SpecificationsTypes::class, 'update'])->name('update');
                Route::get('show/{id}', [SpecificationsTypes::class, 'show'])->name('show');
                Route::post('status/{id}', [SpecificationsTypes::class, 'updatestatus'])->name('status');
                Route::post('show_on_filter/{id}', [SpecificationsTypes::class, 'filterstatus'])->name('filter');
                Route::post('updateposition/{id}', [SpecificationsTypes::class, 'updateposition'])->name('position&filter');
                Route::any('delete/{id}', [SpecificationsTypes::class, 'delete'])->name('delete');
                Route::get('attribute/{id}', [SpecificationsTypes::class, 'typeAttributes'])->name('attributes');

                Route::group(['prefix' => 'attributes', 'as' => 'attribute.'], function () {
                    Route::get('listing', [SpecificationAttributes::class, 'index'])->name('index');
                    Route::get('create', [SpecificationAttributes::class, 'create'])->name('create');
                    Route::post('store', [SpecificationAttributes::class, 'store'])->name('store');
                    Route::patch('update/{id}', [SpecificationAttributes::class, 'updateAttributes'])->name('update');
                    Route::get('show/{id}', [SpecificationAttributes::class, 'show'])->name('show');
                    Route::get('single/{id}', [SpecificationAttributes::class, 'single'])->name('single');
                    Route::post('update/{id}', [SpecificationAttributes::class, 'update'])->name('update');
                    Route::post('status/{id}', [SpecificationAttributes::class, 'updatestatus'])->name('status');
                    Route::any('delete/{id}', [SpecificationAttributes::class, 'delete'])->name('delete');
                });
            });
        });
    });
    Route::post('category/is/featured/{id}', [CategoryController::class, 'updateisFeatured'])->name('category.is_featured');
    Route::post('category/status/{id}', [CategoryController::class, 'updatestatus'])->name('category.status');
    Route::any('/slug-check', [HelperController::class, 'checkSlug'])->name('slug.check');

    // flash-deal
    Route::resource('flash-deal', FlashDealController::class);

    // stock
    Route::post('stock/status/{id}', [ProductStockController::class, 'updateStatus'])->name('stock.status');
    Route::resource('stock', ProductStockController::class);

    // Product
    Route::post('product/duplicate/{id}', [ProductController::class, 'duplicate'])->name('product.duplicate');
    Route::get('product/stock/{id}', [ProductController::class, 'stock'])->name('product.stock');
    Route::post('product/status/{id}', [ProductController::class, 'updateStatus'])->name('product.status');
    Route::post('product/featured/{id}', [ProductController::class, 'updateFeatured'])->name('product.featured');
    Route::resource('product', ProductController::class);
    Route::group(['prefix' => 'products', 'as' => 'product.'], function () {
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::get('specification/{id}', [ProductController::class, 'specificationControl'])->name('manage.specification');
        Route::post('spec/store', [ProductController::class, 'storeSpec'])->name('spec.store');
        Route::any('store', [ProductController::class, 'store'])->name('store');
        Route::group(['prefix' => 'specifications', 'as' => 'specification.'], function () {
            Route::get('/', [ProductController::class, 'specification'])->name('index');
            Route::post('add/{productId}', [ProductController::class, 'specificationsAdd'])->name('add');
            Route::get('edit', [ProductController::class, 'specificationproducts'])->name('edit');
            Route::get('edit/{id}', [ProductController::class, 'specificationproductModal'])->name('edit.modal');
            Route::get('edit/page/{id}', [ProductController::class, 'specificationProductPage'])->name('edit.page');
            Route::post('keyfeature/{id}', [ProductController::class, 'keyfeature'])->name('keyfeature');
            Route::any('delete/{id}', [ProductController::class, 'delete'])->name('delete');
        });
    });

    // Import
    Route::get('import/category', [ImportController::class, 'category'])->name('import.category');
    Route::post('import/category/post', [ImportController::class, 'importCategories'])->name('upload.category');
    Route::get('import/brand', [ImportController::class, 'brand'])->name('import.brand');
    Route::post('import/brand/post', [ImportController::class, 'importBrands'])->name('upload.brand');
    Route::get('import/product', [ImportController::class, 'product'])->name('import.product');
    Route::post('import/product/post', [ImportController::class, 'importProducts'])->name('upload.product');
    Route::get('import/product/stock', [ImportController::class, 'stock'])->name('import.product.stock');
    Route::post('import/product/stock/post', [ImportController::class, 'importStock'])->name('upload.product.stock');
    Route::get('import/product/specification', [ImportController::class, 'specification'])->name('import.product.specification');
    Route::post('import/product/specification/post', [ImportController::class, 'importSpecification'])->name('upload.product.specification');

    Route::get('/image-upload', [ImageController::class, 'index'])->name('image.index');
    Route::post('/image-upload', [ImageController::class, 'upload'])->name('image.upload');
    Route::delete('/image-delete/{filename}', [ImageController::class, 'delete'])->name('image.delete');

    // Order
    Route::group(['prefix' => 'orders', 'as' => 'order.'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('details/{id}', [OrderController::class, 'details'])->name('details');
        Route::post('/{orderId}/update-status', [OrderController::class, 'updateStatus'])->name('update.status');
        Route::get('invoice/{id}', [OrderController::class, 'invoice'])->name('invoice');

    });

    //Installments
    Route::group(['prefix' => 'installments', 'as' => 'installment.'], function () {
        Route::get('/plans', [InstallmentPlanController::class, 'index'])->name('index');
        Route::get('/plan/create', [InstallmentPlanController::class, 'create'])->name('plan.create');
        Route::post('/plan/store', [InstallmentPlanController::class, 'store'])->name('plan.store');
        Route::post('/plan/status/{id}', [InstallmentPlanController::class, 'status'])->name('plan.status');

    });

    //Balance Requests
    Route::group(['prefix' => 'balance', 'as' => 'balance.'], function () {
        Route::get('/requests', [InstallmentPlanController::class, 'balanceRequests'])->name('request');
        Route::get('/request/{id}', [InstallmentPlanController::class, 'balanceRequest'])->name('request.view');
        Route::post('/request/update/{id}', [InstallmentPlanController::class, 'requestUpdate'])->name('request.update');

    });

    // Laptop
    Route::group(['prefix' => 'laptop', 'as' => 'laptop.'], function () {
        // Route::get('/', [LaptopController::class, 'index'])->name('index');
        Route::get('details/{id}', [OrderController::class, 'details'])->name('details');
        Route::post('/{orderId}/update-status', [OrderController::class, 'updateStatus'])->name('update.status');
        Route::get('invoice/{id}', [OrderController::class, 'invoice'])->name('invoice');

        // budget
        Route::post('budget/status/{id}', [LaptopBudgetController::class, 'updateStatus'])->name('budget.status');
        Route::resource('budget', LaptopBudgetController::class);

        // purpose
        Route::post('purpose/status/{id}', [LaptopFinderPurposeController::class, 'updateStatus'])->name('purpose.status');
        Route::resource('purpose', LaptopFinderPurposeController::class);

        // screen
        Route::post('screen/status/{id}', [LaptopFinderScreenController::class, 'updateStatus'])->name('screen.status');
        Route::resource('screen', LaptopFinderScreenController::class);

        // portability
        Route::post('portability/status/{id}', [LaptopFinderPortabilityController::class, 'updateStatus'])->name('portability.status');
        Route::resource('portability', LaptopFinderPortabilityController::class);

        // features
        Route::post('features/status/{id}', [LaptopFinderFeaturesController::class, 'updateStatus'])->name('features.status');
        Route::resource('features', LaptopFinderFeaturesController::class);
    });

    // Reviews
    Route::resource('customer-review', CustomerReviewController::class);
    Route::post('customer-review/status/{id}', [CustomerReviewController::class, 'updateStatus'])->name('customer-review.status');

    // Question
    Route::get('customer/question', [QuestionController::class, 'index'])->name('customer.question.index');
    Route::get('customer/question/answer/{id}', [QuestionController::class, 'answer'])->name('customer.question.answer');
    Route::patch('customer/question/submit-answer', [QuestionController::class, 'submitAnswer'])->name('customer.question.update');
    Route::post('customer/question/status/{id}', [CustomerController::class, 'updateQuestionStatus'])->name('customer.question.status');
    Route::delete('customer/question/delete/{id}', [QuestionController::class, 'destroy'])->name('customer.question.destroy');

    // Customer
    Route::post('customer/status/{id}', [CustomerController::class, 'updateStatus'])->name('customer.status');
    Route::patch('customer/point/update/{id}', [CustomerController::class, 'updatePoints'])->name('customer.point.update');
    Route::get('customer/view/{id?}/{action?}', [CustomerController::class, 'view'])->name('customer.view');
    Route::resource('customer', CustomerController::class);

    // Customer Address
    Route::get('customer/address/create/{id}', [CustomerController::class, 'createAddress'])->name('customer.address.create');
    Route::post('customer/address/store', [CustomerController::class, 'storeAddress'])->name('customer.address.store');
    Route::get('customer/address/edit/{id}', [CustomerController::class, 'editAddress'])->name('customer.address.edit');
    Route::put('customer/address/update/{id}', [CustomerController::class, 'updateAddress'])->name('customer.address.update');
    Route::delete('customer/address/delete/{id}', [CustomerController::class, 'destroyAddress'])->name('customer.address.destroy');

    // Customer Phone
    Route::get('customer/phone/create/{id}', [CustomerController::class, 'createPhone'])->name('customer.phone.create');
    Route::post('customer/phone/store', [CustomerController::class, 'storePhone'])->name('customer.phone.store');
    Route::get('customer/phone/edit/{id}', [CustomerController::class, 'editPhone'])->name('customer.phone.edit');
    Route::put('customer/phone/update/{id}', [CustomerController::class, 'updatePhone'])->name('customer.phone.update');
    Route::delete('customer/phone/delete/{id}', [CustomerController::class, 'destroyPhone'])->name('customer.phone.destroy');

    // Customer WishList
    Route::delete('customer/wishlist/destroy/{id}', [CustomerController::class, 'destroyWishList'])->name('customer.wishlist.destroy');

    // Customer Review
    Route::delete('customer/rating/destroy/{id}', [CustomerController::class, 'destroyRating'])->name('customer.rating.destroy');

    // Customer Cart
    Route::get('customer/cart/show/{id}', [CustomerController::class, 'showCart'])->name('customer.cart.show');
    Route::delete('customer/cart/destroy/{id}', [CustomerController::class, 'destroyCart'])->name('customer.cart.destroy');

    // Brand Types
    Route::post('brand-type/status/{id}', [BrandTypeController::class, 'updateStatus'])->name('brand_type.status');
    Route::post('brand-type/feature/{id}', [BrandTypeController::class, 'updateFeatured'])->name('brand_type.featured');
    Route::resource('brand-type', BrandTypeController::class);

    // Brand
    Route::post('brand/status/{id}', [BrandController::class, 'updateStatus'])->name('brand.status');
    Route::post('brand/featured/{id}', [BrandController::class, 'updateFeatured'])->name('brand.featured');
    Route::resource('brand', BrandController::class);

    // Banner
    Route::post('banner/status/{id}', [BannerController::class, 'updateStatus'])->name('banner.status');
    Route::resource('banner', BannerController::class);

    // Coupon
    Route::post('coupon/status/{id}', [CouponController::class, 'updateStatus'])->name('coupon.status');
    Route::get('coupon/assign/{id?}', [CouponController::class, 'assignCoupon'])->name('coupon.assign');
    Route::post('coupon/assign-to-user', [CouponController::class, 'assignToCustomer'])->name('coupon.assign-to-user');
    Route::resource('coupon', CouponController::class);

    // City
    Route::post('get-city-information-by-id', [CityController::class, 'getCityInformationById'])->name('get-city-information-by-id');
    Route::post('city/status/{id}', [CityController::class, 'updateStatus'])->name('city.status');
    Route::resource('city', CityController::class);

    // Country
    Route::post('get-country-information-by-id', [CountryController::class, 'getCountryInformationById'])->name('get-country-information-by-id');
    Route::post('country/status/{id}', [CountryController::class, 'updateStatus'])->name('country.status');
    Route::resource('country', CountryController::class);

    // Zone
    Route::post('get-zone-information-by-id', [ZoneController::class, 'getZoneInformationById'])->name('get-zone-information-by-id');
    Route::post('zone/status/{id}', [ZoneController::class, 'updateStatus'])->name('zone.status');
    Route::resource('zone', ZoneController::class);

    // carrier
    Route::post('get-carrier-information-by-id', [CarrierController::class, 'getZoneInformationById'])->name('get-carrier-information-by-id');
    Route::post('carrier/status/{id}', [CarrierController::class, 'updateStatus'])->name('carrier.status');
    Route::resource('carrier', CarrierController::class);

    // Stuff
    Route::resource('stuff', StuffController::class);

    // Roles Route
    Route::resource('roles', RoleController::class);

    // Currency
    Route::post('zone/currency/{id}', [CurrencyController::class, 'updateStatus'])->name('currency.status');
    Route::resource('currency', CurrencyController::class);

    // Tax
    Route::post('tax/status/{id}', [TaxController::class, 'updateStatus'])->name('tax.status');
    Route::resource('tax', TaxController::class);

    // pricing-tier
    Route::post('pricing-tier/status/{id}', [PricingTierController::class, 'updateStatus'])->name('pricing-tier.status');
    Route::resource('pricing-tier', PricingTierController::class);

    Route::post('page/status/{id}', [PageController::class, 'updateStatus'])->name('page.status');
    Route::resource('page', PageController::class);

    // Settings
    Route::view('homepage/configuration', 'backend.settings.homepageSettings')->name('homepage.settings')->middleware('checkPermission:settings.homepage-settings');
    Route::post('homepage/settings/{section}', [HomePageController::class, 'visibility'])->name('homepage.settings.status');
    Route::post('cache/clear', [HelperController::class, 'cacheClear'])->name('clear.cache');
    Route::controller(ConfigurationSettingController::class)->group(function () {
        Route::get('settings/general', 'general')->name('settings.general');
        Route::get('settings/otp', 'otp')->name('settings.otp');
        Route::get('settings/email', 'email')->name('settings.email');
        Route::get('settings/vat', 'vat')->name('settings.vat');
        Route::get('shipping-configuration', 'shippingConfiguration')->name('shipping.configuration');

        Route::get('settings/seo/{slug}', 'seo')->name('settings.seo');

        // laptop
        Route::get('laptop/offer-page-seo', 'laptopOfferPage')->name('laptop.offer-page-seo');
        Route::get('laptop/finder-page-seo', 'laptopFinderPage')->name('laptop.finder-page-seo');

        Route::get('website/header', 'websiteHeader')->name('website.header');
        Route::get('website/footer', 'websiteFooter')->name('website.footer');
        Route::get('website/appearance', 'websiteAppearance')->name('website.appearance');

        Route::post('settings/update', 'update')->name('settings.update');
    });

    Route::post('home-page-category/status/{id}', [HomePageCategoryController::class, 'updateStatus'])->name('home-page-category.status');
    Route::resource('home-page-category', HomePageCategoryController::class);

    Route::post('category-banner/status/{id}', [CategoryBannerController::class, 'updateStatus'])->name('category-banner.status');
    Route::resource('category-banner', CategoryBannerController::class);

    Route::get('search-menu', [HelperController::class, 'searchMenu'])->name('search-menu');

    // Push mails From admin
    Route::group(['prefix' => 'push_mails', 'as' => 'mail.'], function () {
        Route::get('/', [PushEmailController::class, 'index'])->name('index');
        Route::post('send', [PushEmailController::class, 'send'])->name('send');
    });

    Route::prefix('reports')->name('report.')->group(function () {
        Route::get('wishlist', [ReportsController::class, 'wishlistReport'])->name('wishlist');
        Route::delete('wishlist/delete/{id}', [ReportsController::class, 'deleteWishlist'])->name('wishlist.delete');
        Route::get('productsSell', [ReportsController::class, 'productsSellReport'])->name('productsSell');
        Route::get('orders', [ReportsController::class, 'orderReport'])->name('orderReport');
        Route::get('stockPurchase', [ReportsController::class, 'stockPurchaseReport'])->name('stockPurchase');
        Route::get('profit', [ReportsController::class, 'profitReport'])->name('profitReport');
        Route::get('transactions/{type}', [ReportsController::class, 'transactions'])->name('transaction');
    });

    // Activity Log
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.log');
    Route::get('/activity/{id}', [ActivityLogController::class, 'show'])->name('activity.show');

    // Charts
    Route::get('/get-user-data', [ChartController::class, 'userData'])->name('activity.log')->name('chart.user.data');
    Route::get('/get-user-status-doughet', [ChartController::class, 'userStatus'])->name('activity.log')->name('chart.user.status');
    Route::get('/get-order-data', [ChartController::class, 'orderData'])->name('activity.log')->name('chart.order.data');

    // System
    Route::view('/server-status', 'backend.system.server_status')->name('system_server')->middleware('checkPermission:system-status.view');
    Route::view('/gateway-configuration', 'backend.gateway.configuration')->name('gateway.configuration')->middleware('checkPermission:gateway-configuration.view');
    Route::post('/gateway-configuration-update', [GatewayConfigurationController::class, 'configuration'])->name('gateway.configuration.update');
    Route::get('banner/source/{source}', [SearchController::class, 'getSourceOptions'])->name('banner.source');


//Notifications
    Route::get('/notifications', [NotificationController::class, 'getAdminNotifications'])->name('notifications');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsAdminRead'])->name('notification.read-all');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsAdminRead'])->name('notification.read');
});

// Add this to your web.php for testing purposes
Route::get('/livewire-components', function () {
    return response()->json(\Livewire\Livewire::getComponentNames());
});

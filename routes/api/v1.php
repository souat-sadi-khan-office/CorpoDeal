<?php

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ConfigurationSettingController;
use App\Http\Controllers\Admin\InstallmentPlanController;
use App\Http\Controllers\Api\V1\OrderTrackingController;
use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\SslCommerzController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\CustomerNotificationApiController;
use App\Http\Controllers\Api\V1\BrandApiController;
use App\Http\Controllers\Api\V1\CartApiController;
use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\V1\CheckoutApiController;
use App\Http\Controllers\Api\V1\CompareApiController;
use App\Http\Controllers\Api\V1\CouponApiController;
use App\Http\Controllers\Api\V1\FlashDealApiController;
use App\Http\Controllers\Api\V1\HomepageApiController;
use App\Http\Controllers\Api\V1\PageApiController;
use App\Http\Controllers\Api\V1\PhoneBookApiController;
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\SearchApiController;
use App\Http\Controllers\Api\V1\UserAddressApiController;
use App\Http\Controllers\Api\V1\UserApiController;

Route::middleware(['check.api.token'])->group(function () {
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('categories/featured', [CategoryApiController::class, 'featuredCategories']);
    Route::get('categories/all', [CategoryApiController::class, 'allCategoriesWithChildren']);

    Route::get('countries', [UserApiController::class, 'getCountries']);
    Route::get('get-currency/{countryId}', [UserApiController::class, 'getCurrency']);

    Route::get('delivery-methods', [HomepageApiController::class, 'deliveryMethods']);
    Route::get('payment-methods', [HomepageApiController::class, 'paymentMethods']);

    Route::get('bestsellers', [HomepageApiController::class, 'bestSellers']);
    Route::get('featured', [HomepageApiController::class, 'featured']);
    Route::get('offered', [HomepageApiController::class, 'offered']);
    Route::get('onsale', [HomepageApiController::class, 'onSale']);
    Route::get('featured-list', [HomepageApiController::class, 'featuredList']);
    Route::get('top-rated', [HomepageApiController::class, 'topRated']);
    Route::get('brands', [HomepageApiController::class, 'brands']);
    Route::get('sliders', [HomepageApiController::class, 'sliders']);
    Route::get('mid-banners', [HomepageApiController::class, 'midBanners']);
    Route::get('trending', [HomepageApiController::class, 'trending']);
    Route::get('flash-deals', [HomepageApiController::class, 'flashDeals']);
    Route::get('home-categories', [HomepageApiController::class, 'homeCategories']);
    Route::post('submit-question-form', [HomepageApiController::class, 'submitQuestionForm'])->name('question-form.submit');
    Route::post('submit-review-form', [HomepageApiController::class, 'submitReviewForm'])->name('review.submit');

    Route::get('/coupons', [CouponApiController::class, 'couponCodesApi']);

    Route::post('compare/add', [CompareApiController::class, 'addToCompare']);
    Route::get('compare/list', [CompareApiController::class, 'getCompareList']);
    Route::delete('compare/remove/{slug}', [CompareApiController::class, 'removeFromCompare']);

    Route::get('/order-tracking/{id}', [OrderTrackingController::class, 'trackOrder']);

    Route::get('categories/{slug}/products', [CategoryApiController::class, 'productsBySlug']);
    Route::get('brands/{slug}/products', [BrandApiController::class, 'productsBySlug']);
    Route::get('pages/{slug}', [PageApiController::class, 'getBySlug']);

    Route::get('flash-deals/{slug}', [FlashDealApiController::class, 'getBySlug']);
    Route::get('products/{slug}', [ProductApiController::class, 'getBySlug']);
    Route::get('products', [ProductApiController::class, 'getAllProducts']);

    Route::get('cities', [CityController::class, 'api']);
    Route::get('installment-plans', [InstallmentPlanController::class, 'installmentPlans'])->name('negative.balance');
    Route::get('/settings', [ConfigurationSettingController::class, 'api']);

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthApiController::class, 'register']);
        Route::post('/login', [AuthApiController::class, 'login']);
        Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthApiController::class, 'resetPassword']);
        Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);
    });

    Route::middleware(['auth:sanctum'])->prefix('account')->name('api.account.')->group(function () {
        Route::get('/', [UserApiController::class, 'dashboard']);
        Route::get('profile', [UserApiController::class, 'myProfile']);
        Route::get('my-orders', [UserApiController::class, 'myOrders'])->name('my_orders');
        Route::get('my-order/{id}', [UserApiController::class, 'orderDetails'])->name('my_order_details');
        Route::get('quotes', [UserApiController::class, 'quotes'])->name('quote');
        Route::post('update-profile', [UserApiController::class, 'updateProfile'])->name('update.profile');
        Route::post('update-password', [UserApiController::class, 'updatePassword'])->name('update.password');

        Route::get('/notifications', [CustomerNotificationApiController::class, 'index']);
        Route::post('/notifications/{id}/read', [CustomerNotificationApiController::class, 'markAsRead']);
        Route::delete('/notifications/clear', [CustomerNotificationApiController::class, 'clearAll']);

        Route::apiResource('phone-book', PhoneBookApiController::class);
        Route::apiResource('address-book', UserAddressApiController::class);

        Route::post('wishlist/{id}', [UserApiController::class, 'storeWishlist'])->name('wishlist.store');
        Route::get('wishlist', [UserApiController::class, 'wishlist'])->name('wishlist');
        Route::delete('wishlist/{id}', [UserApiController::class, 'destroyWishlist'])->name('wishlist.destroy');

        Route::get('star-points', [UserApiController::class, 'star_points'])->name('star_points');
        Route::get('my-coupons', [UserApiController::class, 'myPoints'])->name('my_coupons');

        Route::get('my-negative-balance', [UserApiController::class, 'myNegativeBalance'])->name('negative.balance');
        Route::post('negative-balance-request', [UserApiController::class, 'negativeBalanceStore'])->name('negative.balance.store');

        Route::get('saved-pc', [UserApiController::class, 'saved_pc'])->name('saved_pc');

        Route::get('remove', [UserApiController::class, 'deletionNote']);
        Route::post('delete', [UserApiController::class, 'deleteAccount']);
    });

    Route::post('search', [SearchApiController::class, 'ajaxSearch']);

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartApiController::class, 'index']);
        Route::get('/items', [CartApiController::class, 'items']);

        // Now public - no auth required
        Route::post('/add', [CartApiController::class, 'add']);
        Route::post('/subtract', [CartApiController::class, 'subtract']);
        Route::post('/remove', [CartApiController::class, 'remove']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/checkout/cart', [CheckoutApiController::class, 'cartDetails']);
        Route::post('/checkout/apply-coupon', [CheckoutApiController::class, 'applyCoupon']);
        Route::post('/checkout/remove-coupon', [CheckoutApiController::class, 'removeCoupon']);
        Route::post('/checkout/place-order', [CheckoutApiController::class, 'placeOrder']);
        Route::get('/checkout/user-addresses', [CheckoutApiController::class, 'getUserAddresses']);
        Route::any('/sslcommerz/process', [SslCommerzController::class, 'order'])->name('api.sslcommerz.process');

    });

});

Route::prefix('sslcommerz')->name('api.sslcommerz.')->group(function () {
    Route::post('success', [SslCommerzController::class, 'success'])->name('success');
    Route::post('failure', [SslCommerzController::class, 'failure'])->name('failure');
    Route::post('cancel', [SslCommerzController::class, 'cancel'])->name('cancel');
    Route::post('ipn', [SslCommerzController::class, 'ipn'])->name('ipn');
});

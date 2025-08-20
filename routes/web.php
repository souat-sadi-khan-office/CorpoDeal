<?php

use App\Http\Controllers\Admin\HelperController;
use App\Http\Controllers\Admin\InstallmentPlanController;
use App\Http\Controllers\Frontend\VerificationController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\PhoneBookController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\PcBuilderController;
use App\Http\Controllers\SslCommerzController;
use Illuminate\Support\Facades\DB;

// Route::get('/', function () {
//     return view('frontend.homepage.index');
// })->name('home');

Route::get('admin', function() {
    return redirect()->route('admin.login');
})->name('admin');

Route::get('flash-deals', [HomePageController::class, 'flashDealsPage'])->name('flash-deals');

Route::get('laptop-buying-guide', [HomePageController::class, 'laptopBuyingGuide'])->name('laptop-buying-guide');
Route::get('/scan', [QrCodeController::class, 'scanQRCode'])->name('scanQRCode');
Route::get('pc-builder-add-to-cart', [PcBuilderController::class, 'addToCart'])->name('pc-builder-add-to-cart');
Route::get('print-pc', [PcBuilderController::class, 'printPc'])->name('print-pc');
Route::get('pc-builder', [PcBuilderController::class, 'index'])->name('pc-builder');
Route::get('pc-builder/choose/{item}', [PcBuilderController::class, 'choose'])->name('pc-builder.choose');
Route::get('pc-builder/remove/{item}', [PcBuilderController::class, 'remove'])->name('pc-builder.remove');
Route::get('pc-builder/pick-item/{item}/{id}', [PcBuilderController::class, 'pickItem'])->name('pc-builder.pick');

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('forget-password', [LoginController::class, 'forgotPassword'])->name('forget-password');
Route::get('otp-form', [LoginController::class, 'otpForm'])->name('otp.form');
Route::post('forget-password/post', [LoginController::class, 'postForgotPassword'])->name('post.forget-password');
Route::post('post-validation-otp', [LoginController::class, 'validateOtp'])->name('post.validate.otp');
Route::get('reset-password', [LoginController::class, 'reset_password'])->name('password.reset.form');
Route::post('reset-password/post', [LoginController::class, 'resetPassword'])->name('post.reset.password');
Route::get('register', [RegisterController::class, 'index'])->name('register');
Route::post('login/post', [LoginController::class, 'login'])->name('login.post');
Route::post('register/post', [RegisterController::class, 'register'])->name('register.post');

Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('login/facebook', [LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [LoginController::class, 'handleFacebookCallback']);

Route::get('resent/otp', [RegisterController::class, 'resent_otp'])->name('resend.otp');
Route::get('phone/verify', [VerificationController::class, 'phone'])->name('verify.phone');

Route::middleware(['auth:customer'])->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});
Route::middleware(['isCustomer', 'web', 'ipSession', 'email.verify'])->group(function () {
    Route::get('account', function () {
        return redirect()->route('dashboard');
    });

    Route::get('save-pc', [UserController::class, 'savePc'])->name('save-pc');
    Route::get('pc-builder/view-item/{slug}', [UserController::class, 'viewPc'])->name('pc-builder.view.item');
    Route::get('pc-builder/remove-item/{slug}', [UserController::class, 'removePc'])->name('pc-builder.remove.item');

    Route::get('dashboard', function () {
        return redirect()->route('dashboard');
    });

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('buy/{slug}', [CartController::class, 'buyProductNow'])->name('buy.now');
    Route::post('buy-now', [CartController::class, 'buyNow'])->name('buy-now');
    Route::get('account/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::prefix('account')->name('account.')->group(function () {
        Route::resource('phone-book', PhoneBookController::class);
        Route::resource('address-book', AddressController::class);
        Route::get('my-orders', [UserController::class, 'myOrders'])->name('my_orders');
        Route::get('my-order/{id}', [UserController::class, 'orderDetails'])->name('my_order_details');
        Route::get('order-invoice/{id}', [UserController::class, 'orderInvoice'])->name('order.invoice');
        Route::get('quotes', [UserController::class, 'quotes'])->name('quote');
        Route::get('edit-profile', [UserController::class, 'profile'])->name('edit_profile');
        Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update.profile');
        Route::get('change-password', [UserController::class, 'password'])->name('change_password');
        Route::post('update-password', [UserController::class, 'updatePassword'])->name('update.password');
        Route::get('wish-list', [UserController::class, 'wishlist'])->name('wishlist');
        Route::delete('wish-list/destroy/{id}', [UserController::class, 'destroyWishlist'])->name('wishlist.destroy');
        Route::get('saved-pc', [UserController::class, 'saved_pc'])->name('saved_pc');
        Route::get('star-points', [UserController::class, 'star_points'])->name('star_points');
        Route::get('my-coupons', [UserController::class, 'myPoints'])->name('my_coupons');
        Route::get('transaction', [UserController::class, 'transactions'])->name('transaction');
        Route::get('negative-balance', [InstallmentPlanController::class, 'negativeBalance'])->name('negative.balance');
        Route::post('negative-balance-request', [InstallmentPlanController::class, 'negativeBalanceStore'])->name('negative.balance.store');

        Route::get('account/remove', [UserController::class, 'removeAccount'])->name('remove.account');
        Route::post('account/delete', [UserController::class, 'deleteAccount'])->name('delete');
    });
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::any('place', [OrderController::class, 'store'])->name('store');
        Route::post('get_address/{id}', [OrderController::class, 'address'])->name('address');
        Route::get('confirmation/{id}', [OrderController::class, 'orderConfirmation'])->name('confirm');
    });

    Route::any('sslcommerz/process', [SslCommerzController::class, 'order'])->name('sslcommerz.process');

});
Route::middleware('sslCommerzCsrf')->prefix('sslcommerz')->name('sslcommerz.')->group(function () {
    // Route::prefix('sslcommerz')->name('sslcommerz.')->group(function () {
    Route::post('success', [SslCommerzController::class, 'success'])->name('success');
    Route::any('failure', [SslCommerzController::class, 'failure'])->name('failure');
    Route::any('cancel', [SslCommerzController::class, 'cancel'])->name('cancel');
    Route::post('ipn', [SslCommerzController::class, 'ipn'])->name('ipn');
});


Route::middleware(['web', 'ipSession'])->group(function () {
    Route::any('/', [HomePageController::class, 'index'])->name('home');

    Route::get('track-order', [UserController::class, 'trackOrder'])->name('track.order');
    Route::get('order/track/{id}', [OrderController::class, 'orderTrackingInformation'])->name('order.tracking.information');
    Route::post('order/validate', [OrderController::class, 'orderValidate'])->name('order.validate');
    Route::post('order/re-order', [OrderController::class, 'reOrder'])->name('order.re-order');

    Route::get('contact-us', [HomePageController::class, 'contact'])->name('contact');
    Route::get('coupon-codes', [HomePageController::class, 'couponCodes'])->name('coupon-codes');
    Route::post('submit-contact-form', [HomePageController::class, 'submitContactForm'])->name('contact.submit');

    Route::get('compare', [HomePageController::class, 'compare'])->name('compare');
    Route::get('compare/remove/{slug}', [HomePageController::class, 'removeCompare'])->name('compare.remove');

    Route::post('ajax-search', [SearchController::class, 'ajaxSearch'])->name('ajax-search');
    Route::get('ajax-product-search', [SearchController::class, 'ajaxSearchProduct'])->name('ajax.product.search');

    Route::get('on-sale-products', [HomePageController::class, 'onSaleProduct'])->name('on-sale-product');
    Route::get('featured-products', [HomePageController::class, 'onSaleProduct'])->name('featured-product');
    Route::get('top-rated-products', [HomePageController::class, 'onSaleProduct'])->name('top-rated-product');

    Route::post('newsletter-form-submit', [HomePageController::class, 'postNewsletter'])->name('post.newsletter');

    Route::post('add-to-compare-list', [HomePageController::class, 'addToCompareList'])->name('add-to-compare-list');
    Route::post('coupon/check', [HomePageController::class, 'couponCheck'])->name('coupon.check');
    Route::post('coupon/buy', [HomePageController::class, 'couponBuy'])->name('coupon.buy');
    Route::post('add-to-wish-list', [HomePageController::class, 'addToWishList'])->name('add-to-wish-list');
    Route::post('submit-question-form', [HomePageController::class, 'submitQuestionForm'])->name('question-form.submit');
    Route::post('submit-review-form', [HomePageController::class, 'submitReviewForm'])->name('review.submit');
    Route::any('quick-view/{slug}', [HomePageController::class, 'quickview'])->name('quick.view');
    Route::get('brands', [HomePageController::class, 'allBrands'])->name('brands');
    Route::get('categories', [HomePageController::class, 'allCategories'])->name('categories');
    Route::get('get-categories', [HomePageController::class, 'getAllCategories'])->name('get-categories');

    Route::post('search/customers', [SearchController::class, 'searchByCustomer'])->name('search.customers');
    Route::post('search/category', [SearchController::class, 'searchByCategory'])->name('search.category');
    Route::post('search/category/parent', [SearchController::class, 'searchByParentCategory'])->name('search.category.parent');
    Route::get('search/category/for-laptop', [SearchController::class, 'searchParentCategoryForLaptop'])->name('search.category.for-laptop');
    Route::post('search/category-by-id', [SearchController::class, 'searchByCategoryId'])->name('search.category_by_id');
    Route::post('search/brand-by-id', [SearchController::class, 'searchByBrandId'])->name('search.brand_by_id');
    Route::post('search/brands', [SearchController::class, 'searchByBrands'])->name('search.brands');
    Route::post('search/product', [SearchController::class, 'searchByProduct'])->name('search.product');
    Route::post('search/product-by-id', [SearchController::class, 'searchByProductId'])->name('search.product_id');
    Route::post('search/product-stock', [SearchController::class, 'searchForProductStock'])->name('search.product_stock');
    Route::post('search/product-data', [SearchController::class, 'searchForProductDetails'])->name('search.product_data');
    Route::post('search/brand-types', [SearchController::class, 'searchForBrandTypes'])->name('search.brand-types');
    Route::post('get-laptop-by-finder', [SearchController::class, 'getLapTopByFinder']);
    Route::post('clear-laptop-search', [SearchController::class, 'clearLaptopSearch']);

    Route::get('/get-countries', [AddressController::class, 'getCountriesByZone'])->name('getCountries');
    Route::get('/get-cities', [AddressController::class, 'getCitiesByCountry'])->name('getCities');
    Route::post('/currency/change', [HomePageController::class, 'currencyChange'])->name('currency.change');

    Route::post('/get-cart-items', [CartController::class, 'getCartItems'])->name('get-cart-items');
    Route::delete('/remove-cart-items', [CartController::class, 'removeCartItems'])->name('remove-cart-items');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/sub', [CartController::class, 'subToCart'])->name('cart.sub');
    Route::get('cart', [CartController::class, 'cart'])->name('cart');

    Route::post('add-quantity-to-cart', [HomePageController::class, 'addQtyToCart'])->name('add.cart');

    Route::post('filter/products', [HelperController::class, 'filterProduct'])->name('filter.products');

    Route::get('search', [HelperController::class, 'search'])->name('search');
    Route::any('{slug}', [HelperController::class, 'fetcher'])->name('slug.handle');
});

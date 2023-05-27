<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/cart/product', [\App\Http\Controllers\CartController::class, 'edit'])->name('cart.edit');

Route::group(['prefix' => 'cart'], function() {
    Route::get('/', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/coupon', [\App\Http\Controllers\CartController::class, 'addCoupon'])->name('cart.coupon');
    Route::post('/remove-coupon', [\App\Http\Controllers\CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
});

Route::get('/shop', [\App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');

Route::group(['prefix' => 'checkout'], function () {
    Route::get('/', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/details', [\App\Http\Controllers\CheckoutController::class, 'addDetails'])->name('checkout.details');
    Route::get('/payment', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/manage-payment', [\App\Http\Controllers\CheckoutController::class, 'managePayment'])->name('checkout.manage-payment');
    Route::get('/payment-cancel', [\App\Http\Controllers\CheckoutController::class, 'cancelOrder'])->name('payment.cancel');
});

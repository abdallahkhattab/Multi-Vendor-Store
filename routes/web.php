<?php

use App\Services\CurrencyConverter;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserType;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfilesController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Front\CurrencyConverterController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Front\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Front\PaymentsController;
use App\Http\Controllers\Front\ProductsController as FrontProductsController;

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
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
],function(){
    Route::get('home',[HomeController::class,'index'])->name('home');

Route::get('home/products',[FrontProductsController::class,'index'])->name('home.products.index');
Route::get('home/products/{product:slug}',[FrontProductsController::class,'show'])->name('home.products.show');

Route::resource('cart',CartController::class);

Route::get('home/checkout',[CheckoutController::class,'create'])->name('checkout.index');
Route::post('home/checkout/pay',[CheckoutController::class,'store'])->name('checkout.store');

Route::get('orders/{order}/pay',[PaymentsController::class,'create'])->name('orders.payments.create');

Route::post('orders/{order}/stripe/payment-intent',[PaymentsController::class,'createStripePaymentIntent'])
->name('stripe.paymentIntent.create');

Route::get('orders/{order}/pay/stripe/callback',[PaymentsController::class,'confirm'])
->name('stripe.return');

Route::get('auth/user/2fa',[TwoFactorAuthenticationController::class,'index'])->name('front.2fa');

Route::post('currency',[CurrencyConverterController::class,'confirm'])->name('currency.store');


});


//require __DIR__.'/auth.php';

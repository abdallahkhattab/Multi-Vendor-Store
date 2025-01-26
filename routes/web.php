<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfilesController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductsController as FrontProductsController;
use App\Http\Middleware\CheckUserType;

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

Route::get('home',[HomeController::class,'index'])->name('home');

Route::get('home/products',[FrontProductsController::class,'index'])->name('home.products.index');
Route::get('home/products/{product:slug}',[FrontProductsController::class,'show'])->name('home.products.show');

Route::resource('cart',CartController::class);

Route::get('home/checkout',[CheckoutController::class,'create'])->name('checkout.index');
Route::post('home/checkout/pay',[CheckoutController::class,'store'])->name('checkout.store');

Route::middleware(['auth','auth.type:super-admin,admin'])->group(function () {
   
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
        return view('layouts.master');
    })->name('dashboard');
    
   
    
    Route::resource('categories', CategoriesController::class);
    Route::resource('products',ProductsController::class);
    Route::get('myprofile', [ProfilesController::class, 'edit'])->name('dashboard.profile.edit');
    Route::patch('myprofile/update', [ProfilesController::class, 'update'])->name('dashboard.profile.update');


    Route::get('deleted-categories',[CategoriesController::class,'deletedCategories'])->name('deletedCategories');
    Route::post('deleted-categories/{id}/restore',[CategoriesController::class,'restoreDeletedCategory'])->name('categories.restore');
    Route::delete('categories/force-delete/{id}', [CategoriesController::class, 'forceDelete'])->name('categories.forceDelete');
    
});

//require __DIR__.'/auth.php';

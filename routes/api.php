<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductsController;
use App\Http\Controllers\Api\AccessTokensController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DeliveriesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[AccessTokensController::class,'register']);

Route::apiResource('products',ProductsController::class);
Route::post('auth/access-tokens',[AccessTokensController::class,'login'])
->middleware('guest:sanctum');

Route::delete('auth/access-tokens/{token?}',[AccessTokensController::class,'destroy']);
//delete all tokens
Route::delete('logoutFromAllDevices',[AccessTokensController::class,'LogOutFromAllDevices'])->middleware('auth:sanctum');

Route::get('cart', [CartController::class, 'index']);

Route::post('cart/add',[CartController::class,'store'])->middleware('throttle:60,1');

Route::put('cart/update/{id}',[CartController::class,'update']);

Route::put('deliveries/{delivery}',[DeliveriesController::class,'update']);

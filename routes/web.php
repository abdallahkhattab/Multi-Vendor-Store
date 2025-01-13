<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\CategoriesController;

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
});
/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
*/
Route::middleware('auth')->group(function () {
   
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
    Route::get('deleted-categories',[CategoriesController::class,'deletedCategories'])->name('deletedCategories');
    Route::post('deleted-categories/{id}/restore',[CategoriesController::class,'restoreDeletedCategory'])->name('categories.restore');
    Route::delete('/categories/force-delete/{id}', [CategoriesController::class, 'forceDelete'])->name('categories.forceDelete');
    
});

require __DIR__.'/auth.php';

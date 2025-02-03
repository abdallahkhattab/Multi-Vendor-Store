<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfilesController;
use App\Http\Controllers\Dashboard\CategoriesController;

Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
   
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

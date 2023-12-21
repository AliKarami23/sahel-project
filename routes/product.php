<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ProductController;

Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::prefix('/Product/')->name('Product.')->group(function () {

        Route::post('Create', [ProductController::class, 'create'])->name('create')->middleware(['permission:Product.Create']);
        Route::put('Edit/{id}', [ProductController::class, 'edit'])->name('edit')->middleware(['permission:Product.Edit']);
        Route::delete('Delete/{id}', [ProductController::class, 'delete'])->name('delete')->middleware(['permission:Product.Delete']);
    });

});
Route::prefix('/Product/')->name('Product.')->group(function () {

    Route::get('Show/{id}', [ProductController::class, 'show'])->name('show')->middleware(['permission:Product.Show']);
    Route::get('List', [ProductController::class, 'list'])->name('list')->middleware(['permission:Product.List']);
});


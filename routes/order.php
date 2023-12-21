<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\OrderController;

Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::prefix('/Order/')->name('Order.')->group(function () {

        Route::middleware(['CompleteProfile'])->group(function () {
            Route::post('Create', [OrderController::class, 'create'])->name('create')->middleware(['permission:Order.Create']);
            Route::put('Edit/{id}', [OrderController::class, 'edit'])->name('edit')->middleware(['permission:Order.Edit', 'CheckOrderPermission', 'CheckPaymentOrder']);
        });
        Route::get('Show/{id}', [OrderController::class, 'show'])->name('show')->middleware(['permission:Order.Show', 'CheckOrderPermission']);
        Route::get('List', [OrderController::class, 'list'])->name('list')->middleware(['permission:Order.List']);
        Route::delete('Delete/{id}', [OrderController::class, 'delete'])->name('delete')->middleware(['permission:Order.Delete']);
    });

});

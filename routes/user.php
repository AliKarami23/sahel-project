<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::prefix('/User/')->group(function () {

        Route::put('Update', [UserController::class, 'update'])->name('updateUser')->middleware(['permission:User.Update']);
        Route::get('Operation/{id}', [UserController::class, 'operation'])->name('operation')->middleware(['permission:User.Operation', 'CheckOrderAccess']);
        Route::get('BlockOrActive/{id}', [UserController::class, 'blockOrActive'])->name('blockOrActive')->middleware(['permission:User.BlockOrActive']);
    });

    Route::prefix('/Customer/')->name('Customer.')->group(function () {

        Route::put('Edit/{id}', [UserController::class, 'edit'])->name('edit')->middleware(['permission:Customer.Edit']);
        Route::get('Show/{id}', [UserController::class, 'show'])->name('show')->middleware(['permission:Customer.Show']);
        Route::delete('Delete/{id}', [UserController::class, 'delete'])->name('delete')->middleware(['permission:Customer.Delete']);
        Route::get('List', [UserController::class, 'list'])->name('list')->middleware(['permission:Customer.List']);
    });
});

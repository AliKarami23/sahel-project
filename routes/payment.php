<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\PaymentController;


Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::middleware(['CheckPaymentStatusMiddleware'])->group(function () {
        Route::post('/Payment', [PaymentController::class, 'payment'])->name('Payment');
        Route::post('/Payment/callback', [PaymentController::class, 'callback'])->name('callbackPayment');
    });
    Route::get('Admin/PaymentList', [PaymentController::class, 'paymentList'])->name('paymentList')->middleware(['permission:Payment.List']);
    Route::get('Admin/PaymentFilter', [PaymentController::class, 'paymentFilter'])->name('paymentFilter')->middleware(['permission:Payment.Filter']);

});

<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\RegisterController;


Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::post('/Register/GetInformation', [RegisterController::class, 'getInformation'])->name('getInformation')->middleware(['permission:GetInformation']);
    Route::post('/Logout', [RegisterController::class, 'Logout'])->name('Logout');
    Route::post('/Register/Admin/UpdatePassword', [RegisterController::class, 'updatePassword'])->name('updatePassword')->middleware(['permission:Admin.UpdatePassword']);

});
Route::prefix('/Register/')->group(function () {

    Route::post('GetNumber', [RegisterController::class, 'getNumber'])->name('getNumber');
    Route::post('GetCodeSent', [RegisterController::class, 'getCodeSent'])->name('getCodeSent');
    Route::post('Admin/Login', [RegisterController::class, 'adminLogin'])->name('adminLogin');
    Route::post('Admin/EmailPassword', [RegisterController::class, 'emailPassword'])->name('emailPassword');
    Route::post('Admin/VerifyCode', [RegisterController::class, 'verifyCode'])->name('verifyCode');
});


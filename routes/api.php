<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ReportController;
use \App\Http\Controllers\MediaController;

require __DIR__ . '/user.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/product.php';
require __DIR__ . '/order.php';
require __DIR__ . '/card.php';
require __DIR__ . '/extradition.php';
require __DIR__ . '/payment.php';

Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::get('/link', function () {
        $target = '/home/sahel/domains/sahel.v1r.ir/sahel-project/storage/app/public';
        $shortcut = '/home/sahel/domains/sahel.v1r.ir/public_html';
        symlink($target, $shortcut);
    });

//Dashboard
    Route::get('/Admin/Dashboard', [ReportController::class, 'dashboard'])->name('dashboard')->middleware(['permission:Admin.Dashboard']);

//Media
    Route::post('/UploadImage', [MediaController::class, 'uploadImage'])->name('uploadImage')->middleware(['permission:UploadImage']);
    Route::post('/UploadMainImage', [MediaController::class, 'uploadMainImage'])->name('uploadMainImage')->middleware(['permission:UploadMainImage']);
    Route::post('/UploadVideo', [MediaController::class, 'uploadVideo'])->name('uploadVideo')->middleware(['permission:UploadVideo']);

});

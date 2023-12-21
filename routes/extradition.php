<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ExtraditionController;

Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::prefix('/Extradition')->name('Extradition.')->group(function () {

        Route::post('Request', [ExtraditionController::class, 'request'])->name('request')->middleware(['Check']);
        Route::get('List', [ExtraditionController::class, 'list'])->name('list');
        Route::get('Show/{id}', [ExtraditionController::class, 'show'])->name('show');
        Route::post('Answer/{id}', [ExtraditionController::class, 'answer'])->name('Answer');
    });
    Route::post('/Extradition/cancellationSans', [ExtraditionController::class, 'cancellationSans'])->name('cancellationSans');
});

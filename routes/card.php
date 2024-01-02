<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CardController;


Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::prefix('/Card/')->group(function () {

        Route::get('UserCards', [CardController::class, 'UserCard'])->name('userCard')->middleware(['permission:Card.UserCard']);
        Route::get('AllCards', [CardController::class, 'AllCard'])->name('allCard')->middleware(['permission:Card.AllCard']);
        Route::get('showCards/{id}', [CardController::class, 'showCard'])->name('showCard')->middleware(['permission:Card.show']);
        Route::get('DownloadPdf/{id}', [CardController::class, 'downloadPdf'])->name('downloadPdf')->middleware(['permission:Card.DownloadPdf']);
        Route::get('FilterCard', [CardController::class, 'filterCard'])->name('filterCard')->middleware(['permission:Card.FilterCard']);
    });

});

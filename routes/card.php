<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CardController;


Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::prefix('/Card/')->group(function () {

        Route::get('UserTickets', [CardController::class, 'UserCard'])->name('userCard')->middleware(['permission:Card.UserCard']);
        Route::get('AllTickets', [CardController::class, 'AllCard'])->name('allCard')->middleware(['permission:Card.AllCard']);
        Route::get('DownloadPdf/{id}', [CardController::class, 'downloadPdf'])->name('downloadPdf')->middleware(['permission:Card.DownloadPdf']);
        Route::get('FilterCard', [CardController::class, 'filterCard'])->name('filterCard')->middleware(['permission:Card.FilterCard']);
    });

});

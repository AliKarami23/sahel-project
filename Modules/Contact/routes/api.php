<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\app\Http\Controllers\ContactController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {

    Route::get('/Contact/List', [ContactController::class, 'list'])->name('listContact')->middleware(['permission:Contact.List']);
    Route::delete('/Contact/Destroy/{id}', [ContactController::class, 'destroy'])->name('destroyContact')->middleware(['permission:Contact.Destroy']);
    Route::get('/Contact/Show/{id}', [ContactController::class, 'show'])->name('showContact')->middleware(['permission:Contact.Show']);
    Route::post('/Contact/Answer/{id}', [ContactController::class, 'answer'])->name('answerContact')->middleware(['permission:Contact.Answer']);

});

Route::post('/Contact/Create', [ContactController::class, 'create'])->name('createContact');

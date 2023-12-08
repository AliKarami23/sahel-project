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

    Route::get('/Contact/List', [ContactController::class, 'List'])->name('ListContact')->middleware(['permission:Contact.List']);
    Route::delete('/Contact/Delete/{id}', [ContactController::class, 'Delete'])->name('DeleteContact')->middleware(['permission:Contact.Delete']);
    Route::get('/Contact/Show/{id}', [ContactController::class, 'Show'])->name('ShowContact')->middleware(['permission:Contact.Show']);
    Route::post('/Contact/Answer/{id}', [ContactController::class, 'Answer'])->name('AnswerContact')->middleware(['permission:Contact.Answer']);

});

Route::post('/Contact/Create', [ContactController::class, 'Create'])->name('CreateContact');

<?php

use Illuminate\Http\Request;
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

    Route::post('/contact/create', [ContactController::class, 'create_contact'])->name('CreateContact');
    Route::get('/contact/list', [ContactController::class, 'list_contact'])->name('ListContact');
    Route::delete('/contact/delete/{id}', [ContactController::class, 'delete_contact'])->name('DeleteContact');
    Route::get('/contact/answer/{id}', [ContactController::class, 'show_answer_contact'])->name('ShowAnswerPage');
    Route::post('/contact/answer/{id}', [ContactController::class, 'answer_contact'])->name('AnswerContact');

});

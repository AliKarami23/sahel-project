<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Question\app\Http\Controllers\QuestionController;

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

    Route::post('/question/create', [QuestionController::class, 'create_question'])->name('CreateQuestion');
    Route::put('/question/edit/{id}', [QuestionController::class, 'edit_question'])->name('EditQuestion');
    Route::delete('/question/delete/{id}', [QuestionController::class, 'delete_question'])->name('DeleteQuestion');
    Route::get('/question/list', [QuestionController::class, 'list_question'])->name('listQuestion');

});

<?php

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

    Route::post('/Question/Create', [QuestionController::class, 'Create'])->name('CreateQuestion')->middleware(['permission:Question.Create']);
    Route::put('/Question/Edit/{id}', [QuestionController::class, 'Edit'])->name('EditQuestion')->middleware(['permission:Question.Edit']);
    Route::get('/Question/Show/{id}', [QuestionController::class, 'Show'])->name('ShowQuestion')->middleware(['permission:Question.Show']);
    Route::delete('/Question/Delete/{id}', [QuestionController::class, 'Delete'])->name('DeleteQuestion')->middleware(['permission:Question.Delete']);
    Route::get('/Question/List', [QuestionController::class, 'List'])->name('listQuestion')->middleware(['permission:Question.List']);

});

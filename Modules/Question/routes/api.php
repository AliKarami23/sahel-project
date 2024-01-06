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

    Route::post('/Question/Create', [QuestionController::class, 'create'])->name('createQuestion')->middleware(['permission:Question.Create']);
    Route::put('/Question/Edit/{id}', [QuestionController::class, 'edit'])->name('editQuestion')->middleware(['permission:Question.Edit']);
    Route::get('/Question/Show/{id}', [QuestionController::class, 'show'])->name('showQuestion')->middleware(['permission:Question.Show']);
    Route::delete('/Question/Delete/{id}', [QuestionController::class, 'delete'])->name('deleteQuestion')->middleware(['permission:Question.Delete']);

});

Route::get('/Question/List', [QuestionController::class, 'list'])->name('listQuestion')->middleware(['permission:Question.List']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Comment\app\Http\Controllers\CommentController;

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

    Route::post('/Comment/Create', [CommentController::class, 'Create'])->name('CreateComment')->middleware(['permission:Comment.Create']);
    Route::get('/Comment/List', [CommentController::class, 'List'])->name('ListComment')->middleware(['permission:Comment.List']);
    Route::delete('/Comment/Delete/{id}', [CommentController::class, 'Delete'])->name('DeleteComment')->middleware(['permission:Comment.Delete']);
    Route::get('/Comment/Show/{id}', [CommentController::class, 'Show'])->name('ShowComment')->middleware(['permission:Comment.Show']);
    Route::post('/Comment/Activate/{id}', [CommentController::class, 'Activate'])->name('ActivateComment')->middleware(['permission:Comment.Activate']);
    Route::post('/Comment/Answer/{id}', [CommentController::class, 'Answer'])->name('AnswerComment')->middleware(['permission:Comment.Answer']);

});

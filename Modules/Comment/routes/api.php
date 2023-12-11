<?php

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

    Route::post('/Comment/Create', [CommentController::class, 'create'])->name('createComment')->middleware(['permission:Comment.Create']);
    Route::get('/Comment/List', [CommentController::class, 'list'])->name('listComment')->middleware(['permission:Comment.List']);
    Route::delete('/Comment/Delete/{id}', [CommentController::class, 'delete'])->name('deleteComment')->middleware(['permission:Comment.Delete']);
    Route::get('/Comment/Show/{id}', [CommentController::class, 'show'])->name('showComment')->middleware(['permission:Comment.Show']);
    Route::post('/Comment/Activate/{id}', [CommentController::class, 'activate'])->name('activateComment')->middleware(['permission:Comment.Activate']);
    Route::post('/Comment/Answer/{id}', [CommentController::class, 'answer'])->name('answerComment')->middleware(['permission:Comment.Answer']);

});

<?php

use Illuminate\Support\Facades\Route;
use \Modules\Article\app\Http\Controllers\ArticleController;

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

    Route::post('/Article/Create', [ArticleController::class, 'create'])->name('createArticle')->middleware(['permission:Article.Create']);
    Route::get('/Article/Show/{id}', [ArticleController::class, 'show'])->name('showArticle')->middleware(['permission:Article.Show']);
    Route::put('/Article/Edit/{id}', [ArticleController::class, 'edit'])->name('editArticle')->middleware(['permission:Article.Edit']);
    Route::get('/Article/List', [ArticleController::class, 'list'])->name('listArticle')->middleware(['permission:Article.List']);
    Route::delete('/Article/Delete/{id}', [ArticleController::class, 'delete'])->name('deleteArticle')->middleware(['permission:Article.Delete']);
});

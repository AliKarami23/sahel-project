<?php

use Illuminate\Http\Request;
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

    Route::post('/Article/Create', [ArticleController::class, 'Create'])->name('CreateArticle')->middleware(['permission:Article.Create']);
    Route::get('/Article/Show/{id}', [ArticleController::class, 'Show'])->name('ShowArticle')->middleware(['permission:Article.Show']);
    Route::put('/Article/Edit/{id}', [ArticleController::class, 'Edit'])->name('EditArticle')->middleware(['permission:Article.Edit']);
    Route::get('/Article/List', [ArticleController::class, 'List'])->name('ListArticle')->middleware(['permission:Article.List']);
    Route::delete('/Article/Delete/{id}', [ArticleController::class, 'Delete'])->name('DeleteArticle')->middleware(['permission:Article.Delete']);
//Media
    Route::post('/Article/UploadImage/{id}', [ArticleController::class, 'UploadImage'])->name('ArticleUploadImage')->middleware(['permission:Article.UploadImage']);
    Route::post('/Article/UploadVideo/{id}', [ArticleController::class, 'UploadVideo'])->name('ArticleUploadVideo')->middleware(['permission:Article.UploadVideo']);

});

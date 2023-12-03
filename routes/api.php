<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\RegisterController;
use \App\Http\Controllers\ProductController;
use \Modules\Article\app\Http\Controllers\ArticleController;
use Modules\Contact\app\Http\Controllers\ContactController;
use Modules\Question\app\Http\Controllers\QuestionController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\PaymentController;
use \App\Http\Controllers\CardController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {

//Auth
    Route::post('/Register/GetInformation', [RegisterController::class, 'GetInformation'])->name('GetInformation');
    Route::post('/Logout', [RegisterController::class, 'Logout'])->name('Logout');


// Customer
    Route::put('/Customer/Edit/{id}', [UserController::class, 'Edit'])->name('EditCustomer');
    Route::put('/Customer/Update', [UserController::class, 'Update'])->name('UpdateCustomer');
    Route::delete('/Customer/Delete/{id}', [UserController::class, 'Delete'])->name('DeleteCustomer');
    Route::get('/Customer/List', [UserController::class, 'List'])->name('ListCustomer');


//Product
    Route::post('/Product/Create', [ProductController::class, 'Create'])->name('CreateProduct');
    Route::put('/Product/Edit/{id}', [ProductController::class, 'Edit'])->name('EditProduct');
    Route::put('/Product/show/Edit', [ProductController::class, 'showEdit'])->name('EditProduct');
    Route::get('/Product/List', [ProductController::class, 'List'])->name('ListProduct');
    Route::delete('/Product/Delete/{id}', [ProductController::class, 'Delete'])->name('DeleteProduct');


//Order
    Route::post('/Order/Create', [OrderController::class, 'Create'])->name('CreateOrder');
    Route::put('/Order/Edit/{id}', [OrderController::class, 'Edit'])->name('EditOrder');
    Route::put('/Order/Show/Edit', [OrderController::class, 'ShowEdit'])->name('ShowEditOrder');
    Route::get('/Order/List', [OrderController::class, 'List'])->name('ListOrder');
    Route::delete('/Order/Delete/{id}', [OrderController::class, 'Delete'])->name('DeleteOrder');


//Media
    Route::post('/UploadImage/{id}', [ProductController::class, 'UploadImage'])->name('UploadImage');
    Route::post('/UploadMainImage/{id}', [ProductController::class, 'UploadMainImage'])->name('UploadMainImage');
    Route::post('/UploadVideo/{id}', [ProductController::class, 'UploadVideo'])->name('UploadVideo');


//Payment
    Route::post('/Payment', [PaymentController::class, 'Payment'])->name('Payment');

// Card
    Route::get('Card/showUserTickets', [CardController::class, 'showUserTickets'])->name('showUserTickets');
    Route::get('Card/showAllTickets', [CardController::class, 'showAllTickets'])->name('showAllTickets');
    Route::get('Card/download-pdf/{id}', [CardController::class, 'downloadPdf'])->name('download_pdf');
    Route::get('Card/FilterCard', [CardController::class, 'FilterCard'])->name('FilterCard');






    //Article
    Route::post('/article/create', [ArticleController::class, 'create_article'])->name('CreateArticle');
    Route::put('/article/edit/{id}', [ArticleController::class, 'edit_article'])->name('EditArticle');
    Route::delete('/article/delete/{id}', [ArticleController::class, 'delete_article'])->name('DeleteArticle');
    Route::get('/article/list', [ArticleController::class, 'list_article'])->name('listArticle');

    //Question
    Route::post('/question/create', [QuestionController::class, 'create_question'])->name('CreateQuestion');
    Route::put('/question/edit/{id}', [QuestionController::class, 'edit_question'])->name('EditQuestion');
    Route::delete('/question/delete/{id}', [QuestionController::class, 'delete_question'])->name('DeleteQuestion');
    Route::get('/question/list', [QuestionController::class, 'list_question'])->name('listQuestion');

    //Contact
    Route::post('/contact/create', [ContactController::class, 'create_contact'])->name('CreateContact');
});

//Auth
Route::post('/Register/GetNumber', [RegisterController::class, 'GetNumber'])->name('GetNumber');
Route::post('/Register/GetCodeSent', [RegisterController::class, 'GetCodeSent'])->name('GetCodeSent');
Route::post('/Register/Admin/Login', [RegisterController::class, 'AdminLogin'])->name('AdminLogin');
Route::post('/Register/Admin/EmailPassword', [RegisterController::class, 'EmailPassword'])->name('EmailPassword');
Route::post('/Register/Admin/VerifyCode', [RegisterController::class, 'VerifyCode'])->name('VerifyCode');
Route::post('/Register/Admin/UpdatePassword', [RegisterController::class, 'UpdatePassword'])->name('UpdatePassword');

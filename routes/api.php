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
use \App\Http\Controllers\ReportController;

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

Route::group(['middleware' => ['auth:sanctum', 'CheckUserStatus']], function () {


//Auth
    Route::post('/Register/GetInformation', [RegisterController::class, 'GetInformation'])->name('GetInformation')->middleware(['permission:GetInformation']);
    Route::post('/Logout', [RegisterController::class, 'Logout'])->name('Logout');
    Route::post('/Register/Admin/UpdatePassword', [RegisterController::class, 'UpdatePassword'])->name('UpdatePassword')->middleware(['permission:Admin.UpdatePassword']);


//Dashboard
    Route::get('/Admin/Dashboard', [ReportController::class, 'Dashboard'])->name('Dashboard')->middleware(['permission:Dashboard']);


// Customer
    Route::put('/Customer/Edit/{id}', [UserController::class, 'Edit'])->name('EditCustomer')->middleware(['permission:Customer.Edit']);
    Route::put('/Customer/Update', [UserController::class, 'Update'])->name('UpdateCustomer')->middleware(['permission:Customer.Update']);
    Route::delete('/Customer/Delete/{id}', [UserController::class, 'Delete'])->name('DeleteCustomer')->middleware(['permission:Customer.Delete']);
    Route::get('/Customer/List', [UserController::class, 'List'])->name('ListCustomer')->middleware(['permission:User.BlockOrActive']);
    Route::get('/User/operation/{id}', [UserController::class, 'operation'])->name('operation')->middleware(['permission:User.operation', 'CheckOrderAccess']);
    Route::get('/User/BlockOrActive/{id}', [UserController::class, 'BlockOrActive'])->name('BlockOrActive')->middleware(['permission:User.BlockOrActive']);


//Product
    Route::post('/Product/Create', [ProductController::class, 'Create'])->name('CreateProduct')->middleware(['permission:Product.Create']);
    Route::put('/Product/Edit/{id}', [ProductController::class, 'Edit'])->name('EditProduct')->middleware(['permission:Product.Edit']);
    Route::put('/Product/show/Edit', [ProductController::class, 'showEdit'])->name('showEditProduct')->middleware(['permission:Product.showEdit']);
    Route::get('/Product/List', [ProductController::class, 'List'])->name('ListProduct')->middleware(['permission:Product.List']);
    Route::delete('/Product/Delete/{id}', [ProductController::class, 'Delete'])->name('DeleteProduct')->middleware(['permission:Product.Delete']);

//Order
    Route::post('/Order/Create', [OrderController::class, 'Create'])->name('CreateOrder')->middleware(['permission:Order.Create']);
    Route::put('/Order/Edit/{id}', [OrderController::class, 'Edit'])->name('EditOrder')->middleware(['permission:Order.Edit', 'checkOrderPermission']);
    Route::put('/Order/Show/Edit', [OrderController::class, 'ShowEdit'])->name('ShowEditOrder')->middleware(['permission:Order.ShowEdit']);
    Route::get('/Order/List', [OrderController::class, 'List'])->name('ListOrder')->middleware(['permission:Order.List']);
    Route::delete('/Order/Delete/{id}', [OrderController::class, 'Delete'])->name('DeleteOrder')->middleware(['permission:Order.Delete']);


//Media
    Route::post('/Product/UploadImage/{id}', [ProductController::class, 'UploadImage'])->name('UploadImage')->middleware(['permission:Product.UploadImage']);
    Route::post('/Product/UploadMainImage/{id}', [ProductController::class, 'UploadMainImage'])->name('UploadMainImage')->middleware(['permission:Product.UploadMainImage']);
    Route::post('/Product/UploadVideo/{id}', [ProductController::class, 'UploadVideo'])->name('UploadVideo')->middleware(['permission:Product.UploadVideo']);


//Payment
    Route::post('/Payment', [PaymentController::class, 'Payment'])->name('Payment');
    Route::post('Admin/PaymentList', [PaymentController::class, 'PaymentList'])->name('PaymentList')->middleware(['permission:PaymentList']);
    Route::post('Admin/PaymentFilter', [PaymentController::class, 'PaymentFilter'])->name('PaymentFilter')->middleware(['permission:PaymentFilter']);

// Card
    Route::get('Card/UserTickets', [CardController::class, 'UserTickets'])->name('UserTickets')->middleware(['permission:Card.UserTickets']);
    Route::get('Card/AllTickets', [CardController::class, 'AllTickets'])->name('AllTickets')->middleware(['permission:Card.AllTickets']);
    Route::get('Card/DownloadPdf/{id}', [CardController::class, 'DownloadPdf'])->name('DownloadPdf')->middleware(['permission:Card.DownloadPdf']);
    Route::get('Card/FilterCard', [CardController::class, 'FilterCard'])->name('FilterCard')->middleware(['permission:Card.FilterCard']);


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

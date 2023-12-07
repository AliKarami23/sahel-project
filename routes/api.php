<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\RegisterController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\PaymentController;
use \App\Http\Controllers\CardController;
use \App\Http\Controllers\ReportController;
use \App\Http\Controllers\MediaController;

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


// Customer && User
    Route::put('/User/Update', [UserController::class, 'Update'])->name('UpdateUser')->middleware(['permission:User.Update']);
    Route::get('/User/Operation/{id}', [UserController::class, 'Operation'])->name('Operation')->middleware(['permission:User.Operation', 'CheckOrderAccess']);
    Route::get('/User/BlockOrActive/{id}', [UserController::class, 'BlockOrActive'])->name('BlockOrActive')->middleware(['permission:User.BlockOrActive']);
    Route::put('/Customer/Edit/{id}', [UserController::class, 'Edit'])->name('EditCustomer')->middleware(['permission:Customer.Edit']);
    Route::get('/Customer/Show/{id}', [UserController::class, 'Show'])->name('ShowCustomer')->middleware(['permission:Customer.Show']);
    Route::delete('/Customer/Delete/{id}', [UserController::class, 'Delete'])->name('DeleteCustomer')->middleware(['permission:Customer.Delete']);
    Route::get('/Customer/List', [UserController::class, 'List'])->name('ListCustomer')->middleware(['permission:Customer.List']);


//Product
    Route::post('/Product/Create', [ProductController::class, 'Create'])->name('CreateProduct')->middleware(['permission:Product.Create']);
    Route::put('/Product/Edit/{id}', [ProductController::class, 'Edit'])->name('EditProduct')->middleware(['permission:Product.Edit']);
    Route::get('/Product/Show/{id}', [ProductController::class, 'Show'])->name('ShowOrder')->middleware(['permission:Product.Show']);
    Route::get('/Product/List', [ProductController::class, 'List'])->name('ListProduct')->middleware(['permission:Product.List']);
    Route::delete('/Product/Delete/{id}', [ProductController::class, 'Delete'])->name('DeleteProduct')->middleware(['permission:Product.Delete']);

//Order
    Route::post('/Order/Create', [OrderController::class, 'Create'])->name('CreateOrder')->middleware(['permission:Order.Create']);
    Route::put('/Order/Edit/{id}', [OrderController::class, 'Edit'])->name('EditOrder')->middleware(['permission:Order.Edit', 'checkOrderPermission', 'checkPaymentStatus']);
    Route::get('/Order/Show/{id}', [OrderController::class, 'Show'])->name('ShowOrder')->middleware(['permission:Order.Show', 'checkPaymentStatus']);
    Route::get('/Order/List', [OrderController::class, 'List'])->name('ListOrder')->middleware(['permission:Order.List']);
    Route::delete('/Order/Delete/{id}', [OrderController::class, 'Delete'])->name('DeleteOrder')->middleware(['permission:Order.Delete']);


//Media
    Route::post('/UploadImage', [MediaController::class, 'UploadImage'])->name('UploadImage');
    Route::post('/UploadMainImage', [MediaController::class, 'UploadMainImage'])->name('UploadMainImage');
    Route::post('/UploadVideo', [MediaController::class, 'UploadVideo'])->name('UploadVideo');


//Payment
    Route::post('/Payment', [PaymentController::class, 'Payment'])->name('Payment');
    Route::get('Admin/PaymentList', [PaymentController::class, 'PaymentList'])->name('PaymentList')->middleware(['permission:PaymentList']);
    Route::get('Admin/PaymentFilter', [PaymentController::class, 'PaymentFilter'])->name('PaymentFilter')->middleware(['permission:PaymentFilter']);

// Card
    Route::get('Card/UserTickets', [CardController::class, 'UserTickets'])->name('UserTickets')->middleware(['permission:Card.UserTickets']);
    Route::get('Card/AllTickets', [CardController::class, 'AllTickets'])->name('AllTickets')->middleware(['permission:Card.AllTickets']);
    Route::get('Card/DownloadPdf/{id}', [CardController::class, 'DownloadPdf'])->name('DownloadPdf')->middleware(['permission:Card.DownloadPdf']);
    Route::get('Card/FilterCard', [CardController::class, 'FilterCard'])->name('FilterCard')->middleware(['permission:Card.FilterCard']);

  });

//Auth
Route::post('/Register/GetNumber', [RegisterController::class, 'GetNumber'])->name('GetNumber');
Route::post('/Register/GetCodeSent', [RegisterController::class, 'GetCodeSent'])->name('GetCodeSent');
Route::post('/Register/Admin/Login', [RegisterController::class, 'AdminLogin'])->name('AdminLogin');
Route::post('/Register/Admin/EmailPassword', [RegisterController::class, 'EmailPassword'])->name('EmailPassword');
Route::post('/Register/Admin/VerifyCode', [RegisterController::class, 'VerifyCode'])->name('VerifyCode');

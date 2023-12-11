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
    Route::post('/Register/GetInformation', [RegisterController::class, 'getInformation'])->name('getInformation')->middleware(['permission:GetInformation']);
    Route::post('/Logout', [RegisterController::class, 'Logout'])->name('Logout');
    Route::post('/Register/Admin/UpdatePassword', [RegisterController::class, 'updatePassword'])->name('updatePassword')->middleware(['permission:Admin.UpdatePassword']);


//Dashboard
    Route::get('/Admin/Dashboard', [ReportController::class, 'dashboard'])->name('dashboard')->middleware(['permission:Admin.Dashboard']);


// Customer && User
    Route::put('/User/Update', [UserController::class, 'update'])->name('updateUser')->middleware(['permission:User.Update']);
    Route::get('/User/Operation/{id}', [UserController::class, 'operation'])->name('operation')->middleware(['permission:User.Operation', 'CheckOrderAccess']);
    Route::get('/User/BlockOrActive/{id}', [UserController::class, 'blockOrActive'])->name('blockOrActive')->middleware(['permission:User.BlockOrActive']);
    Route::put('/Customer/Edit/{id}', [UserController::class, 'edit'])->name('editCustomer')->middleware(['permission:Customer.Edit']);
    Route::get('/Customer/Show/{id}', [UserController::class, 'show'])->name('showCustomer')->middleware(['permission:Customer.Show']);
    Route::delete('/Customer/Delete/{id}', [UserController::class, 'delete'])->name('deleteCustomer')->middleware(['permission:Customer.Delete']);
    Route::get('/Customer/List', [UserController::class, 'list'])->name('listCustomer')->middleware(['permission:Customer.List']);


//Product
    Route::post('/Product/Create', [ProductController::class, 'create'])->name('createProduct')->middleware(['permission:Product.Create']);
    Route::put('/Product/Edit/{id}', [ProductController::class, 'edit'])->name('editProduct')->middleware(['permission:Product.Edit']);
    Route::get('/Product/Show/{id}', [ProductController::class, 'show'])->name('showOrder')->middleware(['permission:Product.Show']);
    Route::get('/Product/List', [ProductController::class, 'list'])->name('listProduct')->middleware(['permission:Product.List']);
    Route::delete('/Product/Delete/{id}', [ProductController::class, 'delete'])->name('deleteProduct')->middleware(['permission:Product.Delete']);

//Order
    Route::post('/Order/Create', [OrderController::class, 'create'])->name('createOrder')->middleware(['permission:Order.Create']);
    Route::put('/Order/Edit/{id}', [OrderController::class, 'edit'])->name('editOrder')->middleware(['permission:Order.Edit', 'checkOrderPermission','CheckPaymentOrder']);
    Route::get('/Order/Show/{id}', [OrderController::class, 'show'])->name('showOrder')->middleware(['permission:Order.Show','checkOrderPermission']);
    Route::get('/Order/List', [OrderController::class, 'list'])->name('listOrder')->middleware(['permission:Order.List']);
    Route::delete('/Order/Delete/{id}', [OrderController::class, 'delete'])->name('deleteOrder')->middleware(['permission:Order.Delete']);


//Media
    Route::post('/UploadImage', [MediaController::class, 'uploadImage'])->name('uploadImage')->middleware(['permission:UploadImage']);
    Route::post('/UploadMainImage', [MediaController::class, 'uploadMainImage'])->name('uploadMainImage')->middleware(['permission:UploadMainImage']);
    Route::post('/UploadVideo', [MediaController::class, 'uploadVideo'])->name('uploadVideo')->middleware(['permission:UploadVideo']);


//Payment
    Route::post('/Payment', [PaymentController::class, 'Payment'])->name('Payment');
    Route::get('Admin/PaymentList', [PaymentController::class, 'paymentList'])->name('paymentList')->middleware(['permission:Payment.List']);
    Route::get('Admin/PaymentFilter', [PaymentController::class, 'paymentFilter'])->name('paymentFilter')->middleware(['permission:Payment.Filter']);

// Card
    Route::get('Card/UserTickets', [CardController::class, 'UserTickets'])->name('userTickets')->middleware(['permission:Card.UserTickets']);
    Route::get('Card/AllTickets', [CardController::class, 'AllTickets'])->name('allTickets')->middleware(['permission:Card.AllTickets']);
    Route::get('Card/DownloadPdf/{id}', [CardController::class, 'downloadPdf'])->name('downloadPdf')->middleware(['permission:Card.DownloadPdf']);
    Route::get('Card/FilterCard', [CardController::class, 'filterCard'])->name('filterCard')->middleware(['permission:Card.FilterCard']);

});

//Auth
Route::post('/Register/GetNumber', [RegisterController::class, 'getNumber'])->name('getNumber');
Route::post('/Register/GetCodeSent', [RegisterController::class, 'getCodeSent'])->name('getCodeSent');
Route::post('/Register/Admin/Login', [RegisterController::class, 'adminLogin'])->name('adminLogin');
Route::post('/Register/Admin/EmailPassword', [RegisterController::class, 'emailPassword'])->name('emailPassword');
Route::post('/Register/Admin/VerifyCode', [RegisterController::class, 'verifyCode'])->name('verifyCode');

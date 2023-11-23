<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\RegisterController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\UserController;

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

//Route::group(['middleware' => ['auth:sanctum']], function () {


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

//});

Route::post('/Register/GetNumber', [RegisterController::class, 'GetNumber'])->name('GetNumber');
Route::post('/Register/GetCodeSent', [RegisterController::class, 'GetCodeSent'])->name('GetCodeSent');
Route::post('/Register/Admin/Login', [RegisterController::class, 'AdminLogin'])->name('AdminLogin');




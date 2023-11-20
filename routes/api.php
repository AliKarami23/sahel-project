<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\RegisterController;

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

    Route::post('/Register/GetInformation', [RegisterController::class, 'GetInformation'])->name('GetInformation');
    Route::post('/Logout', [RegisterController::class, 'Logout'])->name('Logout');


    // Customer
    Route::put('/Customer/Edit/{id}', [RegisterController::class, 'EditCustomer']);
    Route::put('/Customer/Update', [RegisterController::class, 'UpdateCustomer']);
    Route::delete('/Customer/Delete/{id}', [RegisterController::class, 'DeleteCustomer']);
    Route::get('/Customer/List', [RegisterController::class, 'ListCustomer']);


});

Route::post('/Register/GetNumber', [RegisterController::class, 'GetNumber'])->name('GetNumber');
Route::post('/Register/GetCodeSent', [RegisterController::class, 'GetCodeSent'])->name('GetCodeSent');
Route::post('/Register/Admin/Login', [RegisterController::class, 'AdminLogin'])->name('AdminLogin');

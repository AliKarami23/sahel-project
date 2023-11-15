<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Modules\Role\app\Http\Controllers\RoleController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::post('/AddRole', [RoleController::class, 'create'])->name('AddRole')->middleware('permission:Role.Add');
    Route::get('/ListRole', [RoleController::class, 'index'])->name('ListRole')->middleware('permission:Role.List');
    Route::put('/EditRole/{id}', [RoleController::class, 'edit'])->name('EditRole')->middleware('permission:Role.Edit');
    Route::delete('/DeleteRole/{id}', [RoleController::class, 'destroy'])->name('DeleteRole')->middleware('permission:Role.Delete');



});

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


    Route::post('/CreateRole', [RoleController::class, 'Create'])->name('createRole')->middleware('permission:Role.Create');
    Route::get('/ListRole', [RoleController::class, 'List'])->name('listRole')->middleware('permission:Role.List');
    Route::put('/EditRole/{id}', [RoleController::class, 'Edit'])->name('editRole')->middleware('permission:Role.Edit');
    Route::delete('/DeleteRole/{id}', [RoleController::class, 'delete'])->name('deleteRole')->middleware('permission:Role.Delete');



});

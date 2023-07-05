<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ToDoListController;
use App\Http\Controllers\ToDoListTaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::resource('/user', UserController::class)->only('index', 'show');








Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('/user', UserController::class)->only('destroy');
    Route::resource('/category', CategoryController::class)->only('index', 'store', 'show');
    Route::resource('/todolist', ToDoListController::class)->only('index', 'show', 'store', 'destroy');
    Route::resource('todolist.task', ToDoListTaskController::class)->only('store', 'update', 'destroy');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;

Route::get('/', [userController::class,"admin"]);
Route::post('/user/adduser', [userController::class, 'adduser']);
Route::delete('/user/delete/{id}', [userController::class, 'deleteuser']);
Route::get('/user/edit/{id}', [UserController::class, 'edituser']);
Route::post('/user/update/{id}', [UserController::class, 'updateuser']);

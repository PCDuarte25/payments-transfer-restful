<?php

use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

// This is the API route file for the application.

// Auth routes.
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Users routes.
    Route::post('/users', [UsersController::class, 'createNewUser']);
    Route::put('/users/{user_id}', [UsersController::class, 'updateUser'])->middleware('auth:sanctum');
    Route::delete('/users/{user_id}', [UsersController::class, 'deleteUser'])->middleware('auth:sanctum');

    // Transactions routes.
    Route::post('/transactions', [TransactionController::class, 'createTransaction'])->middleware('auth:sanctum');
});

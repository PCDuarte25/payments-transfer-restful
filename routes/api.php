<?php

use App\Http\Controllers\api\v1\UsersController;
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

// Users routes.
Route::prefix('v1')->group(function () {
    Route::post('/users', [UsersController::class, 'createNewUser']);
});


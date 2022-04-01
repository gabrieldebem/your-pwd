<?php

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

Route::post('/auth', [\App\Http\Controllers\AuthController::class, 'auth']);
Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
Route::get('/test', function (Request $request) {
    return response()->json($request->header());
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show']);
    Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);
    Route::post('/users/{user}/verification-code', [\App\Http\Controllers\UserController::class, 'sendEmailVerification']);
    Route::put('/users/{user}/verification-code/check', [\App\Http\Controllers\UserController::class, 'checkVerificationCode']);

    Route::post('/passwords', [\App\Http\Controllers\PasswordController::class, 'store']);
    Route::get('/passwords', [\App\Http\Controllers\PasswordController::class, 'index']);
    Route::get('/passwords/{password}', [\App\Http\Controllers\PasswordController::class, 'show']);
    Route::get('/passwords/{password}/decrypt', [\App\Http\Controllers\PasswordController::class, 'showDecryptedPassword']);
    Route::put('/passwords/{password}', [\App\Http\Controllers\PasswordController::class, 'update']);
    Route::delete('/passwords/{password}', [\App\Http\Controllers\PasswordController::class, 'destroy']);
});

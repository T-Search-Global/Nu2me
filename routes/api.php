<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ListingController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
Route::post('/listing-create', [ListingController::class, 'store']);
Route::get('/listing-edit/{id}', [ListingController::class, 'edit']);
Route::post('/listing-update/{id}', [ListingController::class, 'update']);
Route::post('/listing-delete/{id}', [ListingController::class, 'destroy']);

});

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'sendOtpEmail']);
Route::post('/forgot-password-otp', [AuthController::class, 'forgotPasswordOtp']);
Route::post('/reset-password', [AuthController::class, 'updatePassword']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Auth\SocialiteController;





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
   return new UserResource($request->user());
});

Route::middleware('auth:sanctum')->post('/user/mark-paid', [AuthController::class, 'markAsPaid']);
Route::middleware('auth:sanctum','paid')->group(function () {

    Route::post('/user-update', [UserController::class, 'profile']);
    Route::post('/delete-account', [UserController::class, 'deleteAccount']);
    Route::post('/listing-create', [ListingController::class, 'store']);

    Route::get('/listing', [ListingController::class, 'getListing']);

    Route::get('/listing-edit/{id}', action: [ListingController::class, 'edit']);
    Route::post('/listing-update/{id}', [ListingController::class, 'update']);
    Route::post('/listing-delete/{id}', [ListingController::class, 'destroy']);

    Route::get('/listing-detail/{id}',  [ListingController::class, 'getListingDetail']);
    Route::get('/listing-search',  [ListingController::class, 'listingSearch']);

    Route::post('/rating-create',  [ListingController::class, 'storeRating']);

    Route::post('/user/update', [AuthController::class, 'updateUser']);
    Route::get('/user/edit', [AuthController::class, 'editUser']);

    Route::post('/pin-create', [PinController::class, 'store']);
    Route::get('/pin-show', [PinController::class, 'show']);

    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/conversations', [ConversationController::class, 'show']);

    Route::post('/conversations/{id}/messages', [MessageController::class, 'store']);

    Route::get('/conversations/{id}/messages', [MessageController::class, 'show']);



    Route::get('/events/show', [EventController::class, 'events'])->name('events');
    Route::post('/events/create', [EventController::class, 'eventCreate']);
    Route::post('/events/mark-paid/{id}', [EventController::class, 'markEventPaid']);
});

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [SocialiteController::class, 'loginWithGoogle']);
// Route::get('/google/refresh-token/{user}', [SocialiteController::class, 'refreshGoogleToken']);
//

Route::post('/login/facebook', [SocialiteController::class, 'loginWithFacebook']);

Route::post('/forgot-password', [AuthController::class, 'sendOtpEmail']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

Route::post('/forgot-password-otp', [AuthController::class, 'forgotPasswordOtp']);
Route::post('/reset-password', [AuthController::class, 'updatePassword']);



Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

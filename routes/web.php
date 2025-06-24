<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



  Route::get('events', [EventController::class, 'index'])->name('admin.events.index');
    Route::post('events', [EventController::class, 'store'])->name('admin.events.store');
    Route::delete('events/destroy/{id}', [EventController::class, 'destroy'])->name('admin.events.destroy');



    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');

    Route::get('/listing', [ListingController::class, 'index'])->name('listing');

// admin
    Route::get('/listing/listing-charges', [ListingController::class, 'listingCharges'])->name('listingCharges');

    Route::put('/charge/update', [ListingController::class, 'updateCharge'])->name('updateCharges');

    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcement/store', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::post('/announcement/destroy/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

});

require __DIR__.'/auth.php';

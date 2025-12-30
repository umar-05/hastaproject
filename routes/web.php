<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController; 
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// FIX 1: Point the root URL to a public page (like 'home'), not the dashboard.
// If you point to 'dashboard' without auth, it will crash for guests.
Route::get('/', function() {
    return view('home'); 
});

Route::get('/home', function() {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Vehicle Routes
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');

Route::get('/details', function () {
    return view('details');
})->name('details');

Route::get('/book-now', function () {
    return view('customer.book-now');
})->name('book-now');

Route::get('/faq', function () {
    return view('customer.faq');
})->name('faq');

Route::get('/loyalty', function () {
    return view('loyalty');
})->name('loyalty');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// BOOKING ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{fleet}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/voucher/validate', [BookingController::class, 'validateVoucher'])->name('voucher.validate');
});

Route::get('/rewards', function () {
    return view('rewards.index');
})->name('rewards.index');

Route::get('/rewards/{reward}', function ($reward) {
    return view('rewards.show', compact('reward'));
})->name('rewards.show');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// OCR route
Route::post('/ocr/process', [OcrController::class, 'process'])->name('ocr.process');

// Registration route
Route::post('/register/process-matric-card', [RegisteredUserController::class, 'processMatricCard'])
    ->name('register.process-matric-card')
    ->middleware('guest');

Route::middleware(['auth'])->group(function () {
    
    // 1. Add Staff
    Route::get('/staff/add', [StaffController::class, 'create'])->name('staff.add-staff');
    Route::post('/staff/add', [StaffController::class, 'store'])->name('staff.store');

    // 2. Pickup & Return
    Route::get('/staff/pickup-return', [StaffController::class, 'pickupReturn'])->name('staff.pickup-return');

    // 3. Rewards (Fixes your current error)
    Route::get('/staff/rewards', [StaffController::class, 'rewards'])->name('staff.rewards');

    // 4. Reports (Fixes the next error)
    Route::get('/staff/reports', [StaffController::class, 'reports'])->name('staff.report');
    
});

require __DIR__.'/auth.php';


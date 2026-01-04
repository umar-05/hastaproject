<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================
// 1. PUBLIC ROUTES (Accessible by everyone)
// ==============================
// NOTE: We do NOT add 'prevent-back' here. This fixes the login loop.
Route::get('/', fn() => view('home'))->name('root');
Route::get('/faq', fn() => view('customer.faq'))->name('faq');
Route::get('/contact', fn() => view('contact'))->name('contact');

// OCR & Registration Logic (Guests only)
Route::post('/ocr/process', [OcrController::class, 'process'])->name('ocr.process');
Route::post('/register/process-matric-card', [RegisteredUserController::class, 'processMatricCard'])
    ->name('register.process-matric-card')
    ->middleware('guest');


// ==============================
// 2. CUSTOMER ROUTES (Guard: customer)
// ==============================
// ADDED: 'prevent-back'
Route::middleware(['auth:customer', 'verified', 'prevent-back'])->group(function () {
    
    // Dashboard
    Route::get('/home', [CustomerController::class, 'dashboard'])->name('home');

    // Vehicle Booking (The "Book Now" Flow)
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');

    // Customer Rewards
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/my-rewards', [RewardController::class, 'showClaimed'])->name('rewards.claimed');
    
    // Booking Management
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{fleet}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/voucher/validate', [BookingController::class, 'validateVoucher'])->name('voucher.validate');
});


// ==============================
// 3. STAFF ROUTES (Guard: staff)
// ==============================
// ADDED: 'prevent-back'
Route::middleware(['auth:staff', 'prevent-back'])->prefix('staff')->name('staff.')->group(function () {
    
    // Staff Dashboard
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');

    Route::get('/booking-management', function () {
        return view('staff.bookingmanagement');
    })->name('bookingsmanage'); 

    // Staff-Specific Profile Management
    Route::get('/profile', [StaffController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');

    // Staff Management
    Route::get('/add', [StaffController::class, 'create'])->name('add-staff');
    Route::post('/store', [StaffController::class, 'store'])->name('store');
    Route::get('/pickup-return', [StaffController::class, 'pickupReturn'])->name('pickup-return');
    Route::get('/reports', [StaffController::class, 'reports'])->name('report');
    
    // Reward Management for Staff
    Route::prefix('rewards')->name('reward.')->group(function() {
        Route::get('/', [RewardController::class, 'index'])->name('index'); 
        Route::get('/create', [RewardController::class, 'create'])->name('create');
        Route::post('/', [RewardController::class, 'store'])->name('store');
        Route::get('/{reward}/edit', [RewardController::class, 'edit'])->name('edit');
        Route::put('/{reward}', [RewardController::class, 'update'])->name('update');
    });
});


// ==============================
// 4. SHARED ROUTES (Profile)
// ==============================
// ADDED: 'prevent-back'
Route::middleware(['auth:customer', 'prevent-back'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==============================
// 5. AUTH SYSTEM
// ==============================
require __DIR__.'/auth.php';

// Fallback for default Laravel redirects
// ADDED: 'prevent-back'
Route::get('/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth:staff', 'prevent-back'])->name('dashboard');
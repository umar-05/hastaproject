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
use Illuminate\Support\Facades\Auth; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================
// 1. PUBLIC ROUTES
// ==============================
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
Route::middleware(['auth:customer', 'verified', 'prevent-back'])->group(function () {
    
    // Dashboard
    Route::get('/home', [CustomerController::class, 'dashboard'])->name('home');

    // Vehicle Booking
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');

    // Rewards Store
    Route::get('/rewards', [RewardController::class, 'index'])->name('reward.index');
    Route::get('/rewards/my-claimed', [RewardController::class, 'claimed'])->name('reward.claimed');
    Route::post('/rewards/claim', [RewardController::class, 'claim'])->name('rewards.claim');

    
    // Booking Management
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{fleet}', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::match(['get','post'],'/bookings/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/voucher/validate', [BookingController::class, 'validateVoucher'])->name('voucher.validate');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/documents', [ProfileController::class, 'storeDocuments'])->name('profile.documents.store');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==============================
// 3. STAFF ROUTES (Guard: staff)
// ==============================
// Routes are prefixed with '/staff' and named 'staff.'
Route::middleware(['auth:staff', 'prevent-back'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard -> /staff/dashboard
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');

    // Booking Management -> /staff/bookings
    Route::get('/bookings', [StaffController::class, 'bookingManagement'])->name('bookingsmanage');

    // Fleet Management -> /staff/fleet
    // FIX: Removed '/staff' from URI because the group prefix handles it
    Route::prefix('fleet')->name('fleet.')->group(function() {
    Route::get('/', [StaffController::class, 'fleet'])->name('index');           // staff.fleet.index
    Route::get('/create', [StaffController::class, 'createVehicle'])->name('create'); // staff.fleet.create
    Route::post('/', [StaffController::class, 'storeVehicle'])->name('store');        // staff.fleet.store
    Route::delete('/{id}', [StaffController::class, 'destroyVehicle'])->name('destroy'); // staff.fleet.destroy
});

    // Staff Profile -> /staff/profile
    Route::get('/profile', [StaffController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');

    // Staff User Management
    Route::get('/add', [StaffController::class, 'create'])->name('add-staff');
    Route::post('/store', [StaffController::class, 'store'])->name('store');
    
    // Operational
    Route::get('/pickup-return', [StaffController::class, 'pickupReturn'])->name('pickup-return');
    Route::get('/reports', [StaffController::class, 'reports'])->name('report');
    
    // Reward Management for Staff
    Route::get('/dashboard/reward', function() {
            return view('staff.rewards');
        })->name('rewards');

        // Your existing dashboard route
        Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');

        // Reward management routes (under /staff/dashboard)
        Route::get('/dashboard/reward', [RewardController::class, 'index'])->name('rewards');
        Route::post('/dashboard/reward', [RewardController::class, 'store'])->name('rewards.store');
});


// ==============================
// 4. AUTH SYSTEM
// ==============================
require __DIR__.'/auth.php';

// Fallback Redirect
Route::get('/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth:staff', 'prevent-back'])->name('dashboard');

// API Auth Check
Route::get('/api/auth-check', function () {
    return Auth::guard('staff')->check() || Auth::guard('customer')->check() 
        ? response()->json(['authenticated' => true]) 
        : response()->json(['authenticated' => false], 401);
})->name('auth.check');
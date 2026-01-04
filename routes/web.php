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

Route::post('/ocr/process', [OcrController::class, 'process'])->name('ocr.process');
Route::post('/register/process-matric-card', [RegisteredUserController::class, 'processMatricCard'])
    ->name('register.process-matric-card')
    ->middleware('guest');


// ==============================
// 2. CUSTOMER ROUTES (Guard: customer)
// ==============================
Route::middleware(['auth:customer', 'verified', 'prevent-back'])->group(function () {
    
    Route::get('/home', [CustomerController::class, 'dashboard'])->name('home');
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');

    // Rewards Store
    Route::get('/rewards', [RewardController::class, 'index'])->name('reward.index');
    Route::get('/rewards/my-claimed', [RewardController::class, 'claimed'])->name('reward.claimed');
    Route::post('/rewards/claim', [RewardController::class, 'claim'])->name('rewards.claim');
    
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
Route::middleware(['auth:staff', 'prevent-back'])->prefix('staff')->name('staff.')->group(function () {
    
    // Staff Dashboard
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');

    // Reward Management (This matches your Blade: route('staff.rewards') and route('staff.rewards.store'))
    Route::get('/dashboard/reward', [RewardController::class, 'index'])->name('rewards');
    Route::post('/dashboard/reward', [RewardController::class, 'store'])->name('rewards.store');
    Route::delete('/dashboard/reward/{id}', [RewardController::class, 'destroy'])->name('rewards.destroy');

    // Booking Management
    Route::get('/booking-management', function () {
        return view('staff.bookingmanagement');
    })->name('bookingsmanage'); 

    // Profile Management
    Route::get('/profile', [StaffController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');

    // Staff Management
    Route::get('/add', [StaffController::class, 'create'])->name('add-staff');
    Route::post('/store', [StaffController::class, 'store'])->name('store');
    Route::get('/pickup-return', [StaffController::class, 'pickupReturn'])->name('pickup-return');
    Route::get('/reports', [StaffController::class, 'reports'])->name('report');

    // Customer Management
    Route::get('/dashboard/customermanagement', [CustomerController::class, 'index'])->name('customermanagement');
    Route::resource('customermanagement-crud', CustomerController::class)
            ->parameters(['customermanagement-crud' => 'matricNum']);
});


// ==============================
// 4. SHARED ROUTES (Profile)
// ==============================
Route::middleware(['auth:customer', 'prevent-back'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==============================
// 5. AUTH & UTILS
// ==============================
require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth:staff', 'prevent-back'])->name('dashboard');

Route::get('/api/auth-check', function () {
    return Auth::guard('staff')->check() || Auth::guard('customer')->check() 
        ? response()->json(['authenticated' => true]) 
        : response()->json(['authenticated' => false], 401);
})->name('auth.check');
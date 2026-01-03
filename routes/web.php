<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RewardController;
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

Route::get('/reward/staff', function () {
    return view('reward.staff');
});

Route::get('/reward/customer', function () {
    return view('reward.customer');
});

Route::get('/reward/my-rewards', [RewardController::class, 'showClaimed'])->name('rewards.claimed');

Route::middleware('auth')->group(function () {
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/reward/my-rewards', [RewardController::class, 'showClaimed'])->name('rewards.claimed');
});


Route::middleware(['auth', 'staff'])->prefix('staff')->name('reward.')->group(function () {
    Route::get('/rewards', fn() => view('reward.index'))->name('index');        
    Route::get('/rewards/create', fn() => view('reward.reward'))->name('create');
    Route::post('/rewards', [\App\Http\Controllers\RewardController::class, 'store'])->name('store');
    Route::get('/rewards/{reward}/edit', [\App\Http\Controllers\RewardController::class, 'edit'])->name('edit');
    Route::put('/rewards/{reward}', [\App\Http\Controllers\RewardController::class, 'update'])->name('update');
});

// Vehicle Routes
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');

Route::get('/details', function () {
    return view('details');
})->name('details');

Route::get('/book-now', [VehicleController::class, 'bookNow'])->name('book-now');

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
    // This route shows the payment design you provided
    Route::post('/bookings/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    
    // This route is triggered by the 'Finish' button on the payment page
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
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

// Staff routes
Route::middleware(['auth'])->prefix('staff')->group(function () {
    Route::get('/fleet/{id}', [App\Http\Controllers\Staff\FleetController::class, 'show'])
         ->name('staff.fleet.show');
});

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


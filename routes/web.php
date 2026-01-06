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
use App\Http\Controllers\Staff\MissionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================
// 1. PUBLIC ROUTES
// ==============================
Route::get('/', [\App\Http\Controllers\VehicleController::class, 'welcome'])->name('root');
Route::get('/faq', fn() => view('customer.faq'))->name('faq');
Route::get('/contact', fn() => view('contact'))->name('contact');

Route::post('/ocr/process', [OcrController::class, 'process'])->name('ocr.process');
Route::post('/register/process-matric-card', [RegisteredUserController::class, 'processMatricCard'])
    ->name('register.process-matric-card')
    ->middleware('guest');

    Route::post('/bookings/validate-voucher', [BookingController::class, 'validateVoucher'])
    ->name('bookings.validateVoucher');


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
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::match(['get','post'],'/bookings/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/voucher/validate', [BookingController::class, 'validateVoucher'])->name('voucher.validate');
    Route::post('/bookings/{booking}/forms', [BookingController::class, 'uploadForms'])->name('bookings.upload-forms');
    
    // Page to VIEW the pickup form
    Route::get('/bookings/{booking}/pickup-inspection', [BookingController::class, 'showPickupForm'])->name('bookings.pickup-form');
    
    // Page to VIEW the return form
    
    // Page for submitting either form
    Route::post('/bookings/{booking}/inspection', [BookingController::class, 'storeInspection'])->name('bookings.store-inspection');
    Route::get('/bookings/{booking}/return-inspection', [BookingController::class, 'showReturnForm'])->name('bookings.return-form');
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/documents', [ProfileController::class, 'storeDocuments'])->name('profile.documents.store');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==============================
// 3. STAFF ROUTES (Guard: staff)
// ==============================
Route::middleware(['auth:staff', 'prevent-back'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard -> /staff/dashboard
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');

    Route::get('/booking-management', [StaffController::class, 'bookingManagement'])->name('bookingmanagement');

    Route::resource('mission', MissionController::class);

    // Fleet management (Staff)
    Route::prefix('fleet')->name('fleet.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Staff\FleetController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Staff\FleetController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Staff\FleetController::class, 'store'])->name('store');
        // Edit / Update / Destroy (uses plateNumber as the primary key)
        Route::get('/{plateNumber}/edit', [\App\Http\Controllers\Staff\FleetController::class, 'edit'])->name('edit');
        Route::match(['put','patch'],'/{plateNumber}', [\App\Http\Controllers\Staff\FleetController::class, 'update'])->name('update');
        Route::delete('/{plateNumber}', [\App\Http\Controllers\Staff\FleetController::class, 'destroy'])->name('destroy');
        // Show a single vehicle (uses plateNumber as the primary key)
        Route::get('/{plateNumber}', [\App\Http\Controllers\Staff\FleetController::class, 'show'])->name('show');
        // Additional staff fleet routes (approve/ cancel) is added here
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });

    // Staff Profile -> /staff/profile
    // Reward Management (This matches your Blade: route('staff.rewards') and route('staff.rewards.store'))
    Route::get('/dashboard/reward', [RewardController::class, 'index'])->name('rewards');
    Route::post('/dashboard/reward', [RewardController::class, 'store'])->name('rewards.store');
    Route::delete('/dashboard/reward/{id}', [RewardController::class, 'destroy'])->name('rewards.destroy');


    // Profile Management
    Route::get('/profile', [StaffController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');

    // In routes/web.php inside the Staff Group
Route::get('/reports/daily-income', function () {
    return view('staff.reports.dailyincome.index');
})->name('report.daily-income');

    // Staff User Management
    Route::get('/add', [StaffController::class, 'create'])->name('add-staff');
    Route::post('/store', [StaffController::class, 'store'])->name('store');
    
    // Operational
    Route::get('/pickup-return', [StaffController::class, 'pickupReturnSchedule'])->name('pickup-return');
    Route::get('/reports', [StaffController::class, 'reports'])->name('report');
    // Inside the staff middleware group in routes/web.php
    Route::get('/add-functioning', [StaffController::class, 'createFunctioning'])->name('add-stafffunctioning');
    // Reward Management for Staff
    // Add these inside the 'staff.' named group in web.php
    Route::get('/{staffID}/edit', [StaffController::class, 'edit'])->name('edit-staff');
    Route::put('/{staffID}', [StaffController::class, 'update'])->name('update-staff');
    Route::delete('/{staffID}', [StaffController::class, 'destroy'])->name('destroy-staff');
    Route::get('/staff/{staffID}/edit', [StaffController::class, 'edit'])->name('edit-staff');
    Route::prefix('rewards')->name('reward.')->group(function() {
        Route::get('/', [StaffController::class, 'rewards'])->name('index'); 
        Route::get('/create', [RewardController::class, 'create'])->name('create');
        Route::post('/', [RewardController::class, 'store'])->name('store');
        Route::get('/{reward}/edit', [RewardController::class, 'edit'])->name('edit');
        Route::put('/{reward}', [RewardController::class, 'update'])->name('update');
        
        // --- ADDED THIS LINE TO FIX YOUR ERROR ---
        Route::delete('/{reward}', [RewardController::class, 'destroy'])->name('destroy'); 
    });


    // Customer Management
    Route::get('/dashboard/customermanagement', [CustomerController::class, 'index'])->name('customermanagement');
    Route::resource('customermanagement-crud', CustomerController::class)
            ->parameters(['customermanagement-crud' => 'matricNum']);
    Route::get('/', [RewardController::class, 'index'])->name('index'); 
    Route::get('/create', [RewardController::class, 'create'])->name('create');
    Route::post('/', [RewardController::class, 'store'])->name('store');
    Route::get('/{reward}/edit', [RewardController::class, 'edit'])->name('edit');
    Route::put('/{reward}', [RewardController::class, 'update'])->name('update');
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
// 4. AUTH SYSTEM
// ==============================
require __DIR__.'/auth.php';

// Fallback for default Laravel redirects
// ADDED: 'prevent-back'
Route::get('/dashboard', [StaffController::class, 'index'])
    ->middleware(['auth:staff', 'prevent-back']);
// Fallback Redirect
Route::get('/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth:staff', 'prevent-back'])->name('dashboard');

Route::get('/api/auth-check', function () {
    return Auth::guard('staff')->check() || Auth::guard('customer')->check() 
        ? response()->json(['authenticated' => true]) 
        : response()->json(['authenticated' => false], 401);
})->name('auth.check');
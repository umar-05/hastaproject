<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\VehicleController;
<<<<<<< Updated upstream
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
=======
use App\Http\Controllers\BookingController; 


>>>>>>> Stashed changes

Route::get('/', function() {
    return view('dashboard');
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

Route::get('/bookings/create/{fleet}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

Route::get('/loyalty', function () {
    return view('loyalty');
})->name('loyalty');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Customer/Guest Routes
Route::get('/book-now', [CustomerController::class, 'bookNow'])->name('book-now');
Route::get('/bookings', [CustomerController::class, 'bookings'])->name('bookings.index');
Route::get('/rewards', [CustomerController::class, 'rewards'])->name('rewards.index');
Route::get('/faq', [CustomerController::class, 'faq'])->name('faq');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/ocr/process', [OcrController::class, 'process'])->name('ocr.process');

Route::post('/register/process-matric-card', [RegisteredUserController::class, 'processMatricCard'])
    ->name('register.process-matric-card')
    ->middleware('guest');

// Staff Routes
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/pickup-return', [StaffController::class, 'pickupReturn'])->name('pickup-return');
    Route::get('/rewards', [StaffController::class, 'rewards'])->name('rewards');
    Route::get('/report', [StaffController::class, 'report'])->name('report');
    Route::get('/add-staff', [StaffController::class, 'addStaff'])->name('add-staff');
    Route::get('/bookings', [StaffController::class, 'bookingsIndex'])->name('bookings.index');
    Route::get('/fleet', [StaffController::class, 'fleetIndex'])->name('fleet.index');
    Route::get('/maintenance', [StaffController::class, 'maintenanceIndex'])->name('maintenance.index');
    Route::get('/inspections', [StaffController::class, 'inspectionsIndex'])->name('inspections.index');
    Route::get('/payments', [StaffController::class, 'paymentsIndex'])->name('payments.index');
    Route::get('/customers', [StaffController::class, 'customersIndex'])->name('customers.index');
});

require __DIR__.'/auth.php';
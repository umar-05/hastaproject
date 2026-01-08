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
// 2. SHARED ROUTES (Customer OR Staff)
// ==============================
// Accessible by both Staff (for modals/views) and Customers.
Route::middleware(['auth:customer,staff', 'prevent-back'])->group(function () {
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/forms', [BookingController::class, 'uploadForms'])->name('bookings.upload-forms');
});


// ==============================
// 3. CUSTOMER ROUTES (Guard: customer)
// ==============================
Route::middleware(['auth:customer', 'verified', 'prevent-back'])->group(function () {

    Route::get('/home', [CustomerController::class, 'dashboard'])->name('home');

    // --- Profile Management ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/documents', [ProfileController::class, 'storeDocuments'])->name('profile.documents.store');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === RESTRICTED ROUTES (Blacklisted users cannot access these) ===
    Route::middleware(['not.blacklisted'])->group(function () {
        
        // 1. Book Now / Vehicles
        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');

        // 2. Rewards Store
        Route::get('/rewards', [RewardController::class, 'index'])->name('reward.index');
        Route::get('/rewards/my-claimed', [RewardController::class, 'claimed'])->name('reward.claimed');
        Route::post('/rewards/claim', [RewardController::class, 'claim'])->name('rewards.claim');

        // 3. Booking Management
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        
        // Creation & Logic
        Route::get('/bookings/create/{fleet}', [BookingController::class, 'create'])->name('bookings.create');
        Route::match(['get','post'],'/bookings/payment', [BookingController::class, 'payment'])->name('bookings.payment');
        Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
        Route::post('/voucher/validate', [BookingController::class, 'validateVoucher'])->name('voucher.validate');
        
        // Actions
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        // Vehicle Inspection Forms
        Route::get('/bookings/{booking}/pickup-inspection', [BookingController::class, 'showPickupForm'])->name('bookings.pickup-form');
        Route::get('/bookings/{booking}/return-inspection', [BookingController::class, 'showReturnForm'])->name('bookings.return-form');
        Route::post('/bookings/{booking}/inspection', [BookingController::class, 'storeInspection'])->name('bookings.store-inspection');
    });
});


// ==============================
// 4. STAFF ROUTES (Guard: staff)
// ==============================
Route::middleware(['auth:staff', 'prevent-back'])->prefix('staff')->name('staff.')->group(function () {

    // --- Dashboard & Main Operations ---
    Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
    Route::get('/booking-management', [StaffController::class, 'bookingManagement'])->name('bookingmanagement');
    Route::get('/booking-details/{bookingID}', [StaffController::class, 'showBooking'])->name('bookings.show');
    
    Route::resource('mission', MissionController::class);
    Route::get('/fleet/check', [StaffController::class, 'checkAvailability'])->name('fleet.check');
    Route::get('/pickup-return', [StaffController::class, 'pickupReturnSchedule'])->name('pickup-return');

    // --- Fleet Management ---
    Route::prefix('fleet')->name('fleet.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Staff\FleetController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Staff\FleetController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Staff\FleetController::class, 'store'])->name('store');
        
        // Specific Vehicle Routes
        Route::get('/{plateNumber}/edit', [\App\Http\Controllers\Staff\FleetController::class, 'edit'])->name('edit');
        Route::match(['put','patch'],'/{plateNumber}', [\App\Http\Controllers\Staff\FleetController::class, 'update'])->name('update');
        Route::delete('/{plateNumber}', [\App\Http\Controllers\Staff\FleetController::class, 'destroy'])->name('destroy');
        Route::get('/{plateNumber}', [\App\Http\Controllers\Staff\FleetController::class, 'show'])->name('show');

        // Tabs
        Route::get('/{plateNumber}/overview', [\App\Http\Controllers\Staff\FleetController::class, 'overview'])->name('tabs.overview');
        Route::get('/{plateNumber}/bookings', [\App\Http\Controllers\Staff\FleetController::class, 'bookings'])->name('tabs.bookings');
        Route::get('/{plateNumber}/maintenance', [\App\Http\Controllers\Staff\FleetController::class, 'maintenance'])->name('tabs.maintenance');
        Route::post('/{plateNumber}/maintenance', [\App\Http\Controllers\Staff\FleetController::class, 'storeMaintenance'])->name('maintenance.store');
        Route::get('/{plateNumber}/owner', [\App\Http\Controllers\Staff\FleetController::class, 'owner'])->name('tabs.owner');
        
        // Approve / Cancel Bookings (Fleet Context)
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });

    // --- Reward Management ---
    
    // 1. FIX: Restore 'staff.rewards' for Sidebar compatibility
    Route::get('/rewards-dashboard', [StaffController::class, 'rewards'])->name('rewards');

    // Profile Management
    Route::get('/profile', [StaffController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');
    
    //Daily-Income Report
    Route::get('/reports/daily-income', [StaffController::class, 'dailyIncome'])->name('report.daily-income');
    //Monthly-Income Report
    Route::get('/reports/monthly-income', [StaffController::class, 'monthlyIncome'])->name('report.monthly-income');
    // Staff User Management
    Route::get('/add', [StaffController::class, 'create'])->name('add-staff');
    Route::post('/store', [StaffController::class, 'store'])->name('store');

    Route::get('/reports', [StaffController::class, 'reports'])->name('report');
    Route::get('/add-functioning', [StaffController::class, 'createFunctioning'])->name('add-stafffunctioning');
    
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
        Route::delete('/{reward}', [RewardController::class, 'destroy'])->name('destroy');
    });

    /*// --- Reports ---
    Route::get('/reports', [StaffController::class, 'reports'])->name('report');
    Route::get('/reports/daily-income', function () {
        return view('staff.reports.dailyincome.index');
    })->name('report.daily-income');
    */
    Route::get('/reports/incomeexpenses', [StaffController::class, 'incomeExpenses'])->name('reports.incomeExpenses');

    // Blacklist
    Route::get('/reports/blacklist', [StaffController::class, 'blacklistIndex'])->name('blacklist.index');
    Route::post('/reports/blacklist', [StaffController::class, 'addToBlacklist'])->name('blacklist.store');
    Route::delete('/reports/blacklist/{matricNum}', [StaffController::class, 'destroyBlacklist'])->name('blacklist.destroy');
    Route::get('/reports/customer-search/{matric}', [StaffController::class, 'searchCustomer'])->name('customer.search');
    Route::post('/staff/blacklist/store', [StaffController::class, 'storeBlacklist'])->name('staff.blacklist.store');


    // --- Customer Management ---
    Route::get('/dashboard/customermanagement', [CustomerController::class, 'index'])->name('customermanagement');
    Route::resource('customermanagement-crud', CustomerController::class)
            ->parameters(['customermanagement-crud' => 'matricNum']);

    // --- Staff Profile Management ---
    Route::get('/profile', [StaffController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');

    // --- Staff User Management ---
    Route::get('/add', [StaffController::class, 'create'])->name('add-staff');
    Route::post('/store', [StaffController::class, 'store'])->name('store');
    Route::get('/add-functioning', [StaffController::class, 'createFunctioning'])->name('add-stafffunctioning');
    
    // Edit specific staff
    Route::get('/{staffID}/edit', [StaffController::class, 'edit'])->name('edit-staff');
    Route::put('/{staffID}', [StaffController::class, 'update'])->name('update-staff');
    Route::delete('/{staffID}', [StaffController::class, 'destroy'])->name('destroy-staff');

    // --- Mission Management Actions ---
    Route::get('/mission', [StaffController::class, 'missionsIndex'])->name('missions.index');
    Route::post('/mission/store', [StaffController::class, 'missionStore'])->name('missions.store');
    Route::post('/mission/{id}/accept', [StaffController::class, 'missionAccept'])->name('missions.accept');
    Route::post('/mission/{id}/complete', [StaffController::class, 'missionComplete'])->name('missions.complete');

}); // End Staff Middleware Group


// ==============================
// 5. AUTH SYSTEM
// ==============================
require __DIR__.'/auth.php';

// Fallback Redirect
Route::get('/dashboard', function () {
    return redirect()->route('staff.dashboard');
})->middleware(['auth:staff', 'prevent-back'])->name('dashboard');

// Auth Check API
Route::get('/api/auth-check', function () {
    return Auth::guard('staff')->check() || Auth::guard('customer')->check()
        ? response()->json(['authenticated' => true])
        : response()->json(['authenticated' => false], 401);
})->name('auth.check');
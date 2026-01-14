<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Reward;
use App\Models\Booking;
use App\Models\Inspection;
use App\Models\Fleet;
use App\Models\Customer;
use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    /**
     * Display the Staff Dashboard.
     */
    public function index()
    {
        // 1. Top Metric Counts
        $pickupsToday = Booking::whereDate('pickupDate', \Carbon\Carbon::today())
            ->whereIn('bookingStat', ['Confirmed', 'Approved'])
            ->count();
        
        $returnsToday = Booking::whereDate('returnDate', \Carbon\Carbon::today())
            ->where('bookingStat', 'Active')
            ->count();

        // FIXED: Changed 'carStat' to 'status' to match your database
        $availableCarsCount = Fleet::where('status', 'Available')->count();

        // 2. Recent Bookings
        $recentBookings = Booking::join('fleet', 'booking.plateNumber', '=', 'fleet.plateNumber')
            ->select('booking.*', 'fleet.modelName', 'fleet.plateNumber')
            ->orderBy('booking.created_at', 'desc')
            ->limit(3)
            ->get();

        // 3. College Trends (UNTOUCHED)
        $totalCustomers = Customer::count();
        $utmColleges = ['KRP', 'KTF', 'KTR', 'KTHO', 'KTDI', 'KDSE', 'KDOJ', 'KTC', 'K9', 'K10', 'OTHER'];
        $actualData = Customer::select('collegeAddress', \DB::raw('count(*) as count'))
            ->whereNotNull('collegeAddress')
            ->groupBy('collegeAddress')
            ->pluck('count', 'collegeAddress')
            ->toArray();

        $collegeDistribution = [];
        foreach ($utmColleges as $college) {
            $count = $actualData[$college] ?? 0;
            $collegeDistribution[$college] = $totalCustomers > 0 ? round(($count / $totalCustomers) * 100) : 0;
        }

        // 4. Daily Availability Calendar Logic
        $weekDates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = \Carbon\Carbon::today()->addDays($i);
            $weekDates[] = [
                'name' => $date->format('D'),
                'date' => $date->format('d'),
                'full_date' => $date->toDateString(),
                'is_today' => $i === 0
            ];
        }

        $cars = Fleet::all();
        $fleetAvailability = [];
        foreach ($cars as $car) {
            $schedule = [];
            foreach ($weekDates as $day) {
                $isBooked = Booking::where('plateNumber', $car->plateNumber)
                    ->whereIn('bookingStat', ['Approved', 'Active', 'Confirmed'])
                    ->whereDate('pickupDate', '<=', $day['full_date'])
                    ->whereDate('returnDate', '>=', $day['full_date'])
                    ->exists();

                $schedule[] = [
                    'available' => !$isBooked,
                    'is_today' => $day['is_today']
                ];
            }
            $fleetAvailability[] = [
                'modelName' => $car->modelName,
                'plateNumber' => $car->plateNumber,
                'schedule' => $schedule
            ];
        }

        return view('staff.dashboard', compact(
            'pickupsToday', 
            'returnsToday', 
            'availableCarsCount',
            'recentBookings',
            'collegeDistribution', 
            'weekDates',
            'fleetAvailability'
        ));
    }

    /**
     * Display the Pickup & Return Inspection page.
     */
    public function pickupReturn()
    {
        $today = now()->format('Y-m-d');

        $todayPickups = Booking::with(['fleet', 'customer'])
            ->whereDate('pickupDate', $today)
            ->whereIn('bookingStat', ['confirmed', 'pending'])
            ->get();

        $todayReturns = Booking::with(['fleet', 'customer'])
            ->whereDate('returnDate', $today)
            ->where('bookingStat', 'active')
            ->get();

        return view('staff.pickup-return', compact('todayPickups', 'todayReturns'));
    }

    /**
     * Show the profile edit form.
     */
    public function editProfile(Request $request): View
    {
        return view('staff.profile.edit', [
            'user' => $request->user('staff'),
        ]);
    }

    /**
     * Update the staff's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        if ($request->filled('email_username')) {
            $fullEmail = $request->input('email_username') . '@hasta.com';
            $request->merge(['email' => $fullEmail]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('staff')->ignore($request->user('staff')->staffID, 'staffID')],
            'position' => ['nullable', 'string', 'max:255'],
            'phoneNum' => ['nullable', 'string', 'max:20'],
            'icNum_passport' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'max:100'],
            'eme_name' => ['nullable', 'string', 'max:255'],
            'emerelation' => ['nullable', 'string', 'max:100'],
            'emephoneNum' => ['nullable', 'string', 'max:20'],
            'bankName' => ['nullable', 'string', 'max:100'],
            'accountNum' => ['nullable', 'string', 'max:50'],
        ]);

        $user = $request->user('staff');
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('staff.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit($staffID): \Illuminate\View\View
    {
        $staff = Staff::where('staffID', $staffID)->firstOrFail();
        return view('staff.add-stafffunctioning', compact('staff'));
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy($staffID): \Illuminate\Http\RedirectResponse
    {
        $staff = Staff::where('staffID', $staffID)->firstOrFail();

        if (Auth::guard('staff')->user()->staffID === $staff->staffID) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $staff->delete();

        return redirect()->route('staff.add-staff')
            ->with('status', "Staff member $staffID has been successfully removed.");
    }

    public function update(Request $request, $staffID): \Illuminate\Http\RedirectResponse
    {
        $staff = Staff::where('staffID', $staffID)->firstOrFail();

        if ($request->filled('email_username')) {
            $fullEmail = $request->input('email_username') . '@hasta.com';
            $request->merge(['email' => $fullEmail]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('staff')->ignore($staff->staffID, 'staffID')],
        ]);

        $staff->update($validated);

        return redirect()->route('staff.add-staff')
            ->with('status', "Staff member $staffID updated successfully!");
    }

    /**
     * Show form to add new staff (and list existing staff).
     */
    public function create(Request $request): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $query = Staff::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('staffID', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('position') && $request->position !== 'All Positions') {
            $query->where('position', $request->position);
        }

        $staffs = $query->orderBy('created_at', 'desc')->get();

        $allStaff = Staff::all();
        $totalStaffCount = $allStaff->count();
        $driverCount = $allStaff->where('position', 'Driver')->count();
        $adminCount = $allStaff->whereIn('position', ['Administrator', 'IT Officer'])->count();
        $managerCount = $allStaff->where('position', 'Manager')->count();

        return view('staff.add-staff', compact(
            'staffs', 
            'totalStaffCount', 
            'driverCount', 
            'adminCount', 
            'managerCount'
        ));
    }

    public function createFunctioning() 
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }
        return view('staff.add-stafffunctioning');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $fullEmail = $request->input('email_username') . '@hasta.com';
        $request->merge(['email' => $fullEmail]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email_username' => ['required', 'string', 'max:50', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff'],
            'position' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $latestStaff = \App\Models\Staff::orderBy('staffID', 'desc')->first();
        
        if (!$latestStaff) {
            $newStaffID = 'STAFF001';
        } else {
            $lastNumber = intval(substr($latestStaff->staffID, 5)); 
            $newStaffID = 'STAFF' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        Staff::create([
            'name' => $request->name,
            'staffID' => $newStaffID,
            'email' => $request->email, 
            'position' => $request->position,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('staff.add-staff')
            ->with('status', 'Staff member ' . $newStaffID . ' added successfully!');
    }

    /**
     * Show reports page.
     */
    public function reports(): View
    {
        return view('staff.report'); 
    }

    /**
     * Bookings management list.
     */
    public function bookingManagement(Request $request)
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }  
        
        $query = Booking::with(['fleet', 'customer']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bookingID', 'LIKE', "%{$search}%")
                ->orWhere('plateNumber', 'LIKE', "%{$search}%")
                ->orWhere('matricNum', 'LIKE', "%{$search}%")
                ->orWhereHas('customer', function($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('bookingStat', 'LIKE', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $totalBookings = Booking::count();
        $approvedCount = Booking::where('bookingStat', 'approved')->count();
        $pendingCount = Booking::where('bookingStat', 'pending')->count();
        $completedCount = Booking::where('bookingStat', 'completed')->count();
        $cancelledCount = Booking::where('bookingStat', 'cancelled')->count();

        return view('staff.bookingmanagement', [
            'bookings' => $bookings,
            'totalBookings' => $totalBookings,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount
        ]);
    }

    /**
     * Fleet Management page.
     */
    public function fleet(Request $request)
    {
        $stats = [
            'total'       => Fleet::count(),
            'available'   => Fleet::where('status', 'available')->count(),
            'rented'      => Fleet::where('status', 'rented')->count(),
            'maintenance' => Fleet::where('status', 'maintenance')->count(),
        ];

        $query = Fleet::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        if ($filter = $request->input('filter')) {
            if ($filter !== 'all') {
                $query->where('status', $filter);
            }
        }

        $vehicles = $query->latest()->paginate(9)->withQueryString();

        return view('staff.fleet.index', compact('vehicles', 'stats'));
    }

    /**
     * Rewards page.
     */
    public function rewards()
    {
        $activeRewards = Reward::where('rewardStatus', 'Active')->get();
        $inactiveRewards = Reward::where('rewardStatus', 'Inactive')->get();

        $stats = [
            'total'  => Reward::count(),
            'active' => $activeRewards->count(),
            'slots'  => $activeRewards->sum('totalClaimable'),
        ];

        return view('staff.rewards', compact('activeRewards', 'inactiveRewards', 'stats'));
    }

    /**
     * Display the Daily Income Report.
     */
    public function dailyIncome(): \Illuminate\View\View
    {
        $todayPayments = \App\Models\Payment::whereDate('paymentDate', \Carbon\Carbon::today())
            ->where('paymentStatus', 'paid') 
            ->get();
        
        $todayIncome = $todayPayments->sum('grandTotal');
        $transactionCount = $todayPayments->count();

        $yesterdayIncome = \App\Models\Payment::whereDate('paymentDate', \Carbon\Carbon::yesterday())
            ->where('paymentStatus', 'paid')
            ->sum('grandTotal');

        $change = $yesterdayIncome > 0 
            ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100 
            : ($todayIncome > 0 ? 100 : 0);

        $recentTransactionsRaw = \App\Models\Payment::with(['booking.customer'])
            ->whereDate('paymentDate', \Carbon\Carbon::today())
            ->where('paymentStatus', 'paid')
            ->orderBy('paymentDate', 'desc')
            ->limit(10)
            ->get();

        $recentTransactions = $recentTransactionsRaw->map(function ($payment) {
            return [
                'booking_id' => $payment->bookingID ?? 'N/A',
                'customer' => $payment->booking->customer->name ?? 'Unknown',
                'amount' => $payment->grandTotal,
                'date' => \Carbon\Carbon::parse($payment->paymentDate)->format('M d, Y'),
                'time' => \Carbon\Carbon::parse($payment->paymentDate)->format('h:i A'),
                'payment_method' => ucfirst($payment->method ?? 'N/A'),
            ];
        })->toArray();

        $dailyIncomeChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $dayIncome = \App\Models\Payment::whereDate('paymentDate', $date)->where('paymentStatus', 'paid')->sum('grandTotal');
            $dailyIncomeChart[] = [
                'date' => $date->format('Y-m-d'),
                'total' => round($dayIncome, 2)
            ];
        }

        $bookingsTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $bookingCount = \App\Models\Payment::whereDate('paymentDate', $date)->where('paymentStatus', 'paid')->count();
            $bookingsTrend[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $bookingCount
            ];
        }

        return view('staff.reports.dailyincome.index', compact(
            'todayIncome', 'yesterdayIncome', 'change', 'transactionCount', 'recentTransactions', 'dailyIncomeChart', 'bookingsTrend'
        ));
    }

    /**
     * Display the Monthly Income Report connected to the database.
     */
    public function monthlyIncome(): \Illuminate\View\View
    {
        $now = Carbon::now();
        $currentYear = $now->year;

        $currentMonthIncome = \App\Models\Payment::whereYear('paymentDate', $currentYear)
            ->whereMonth('paymentDate', $now->month)
            ->where('paymentStatus', 'paid')
            ->sum('grandTotal');

        $previousMonthIncome = \App\Models\Payment::whereYear('paymentDate', $now->copy()->subMonth()->year)
            ->whereMonth('paymentDate', $now->copy()->subMonth()->month)
            ->where('paymentStatus', 'paid')
            ->sum('grandTotal');

        $yearlyTotal = \App\Models\Payment::whereYear('paymentDate', $currentYear)
            ->where('paymentStatus', 'paid')
            ->sum('grandTotal');
        
        $monthsPassedSoFar = $now->month;
        $averageMonthly = $monthsPassedSoFar > 0 ? ($yearlyTotal / $monthsPassedSoFar) : 0;

        $cards = [
            'current_month'   => $currentMonthIncome,
            'previous_month'  => $previousMonthIncome,
            'average_monthly' => $averageMonthly,
            'yearly_total'    => $yearlyTotal,
        ];

        $paymentMethods = \App\Models\Payment::select('method', DB::raw('count(*) as count'))
            ->where('paymentStatus', 'paid')
            ->groupBy('method')
            ->pluck('count', 'method')
            ->toArray();

        $monthlyStats = \App\Models\Payment::select(
                DB::raw('MONTH(paymentDate) as month'),
                DB::raw('SUM(grandTotal) as income'),
                DB::raw('COUNT(paymentID) as bookings'),
                DB::raw('AVG(grandTotal) as avg')
            )
            ->whereYear('paymentDate', $currentYear)
            ->where('paymentStatus', 'paid') 
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $breakdown = [];
        $prevIncome = 0;

        for ($m = 1; $m <= 12; $m++) {
            $monthData = $monthlyStats->get($m);
            $income = $monthData ? (float)$monthData->income : 0;
            $growth = ($prevIncome > 0) ? (($income - $prevIncome) / $prevIncome) * 100 : ($prevIncome == 0 && $income > 0 ? 100 : null);
            $breakdown[] = [
                'month'    => Carbon::create()->month($m)->format('F'),
                'year'     => $currentYear,
                'income'   => $income,
                'bookings' => $monthData ? $monthData->bookings : 0,
                'avg'      => $monthData ? number_format($monthData->avg, 2) : '0.00',
                'growth'   => $growth !== null ? round($growth, 1) : null
            ];
            $prevIncome = $income;
        }

        return view('staff.reports.monthlyincome.index', compact('cards', 'paymentMethods', 'breakdown'));
    }

    // ==========================================
    // BLACKLIST MANAGEMENT SECTION
    // ==========================================

    public function blacklistIndex(): View
    {
        $blacklisted = Customer::where('accStatus', 'LIKE', 'blacklisted%')->get();
        $count = $blacklisted->count();
        return view('staff.blacklist', compact('blacklisted', 'count'));
    }

    public function addToBlacklist(Request $request) 
    {
        $customer = Customer::where('matricNum', $request->matricNum)->first();
        if ($customer) {
            $customer->accStatus = 'blacklisted'; 
            $customer->blacklistReason = $request->reason;
            $customer->save();
            return back()->with('success', 'Customer blacklisted successfully.');
        }
        return back()->with('error', 'Customer not found.');
    }

    public function storeBlacklist(Request $request)
    {
        $request->validate(['matricNum' => 'required|string', 'reason' => 'required|string|max:255']);
        $customer = Customer::where('matricNum', $request->matricNum)->first();
        if ($customer) {
            $customer->accStatus = 'blacklisted'; 
            $customer->blacklistReason = $request->reason;
            $customer->save();
            return redirect()->route('staff.blacklist.index')->with('success', 'Customer has been blacklisted successfully.');
        }
        return back()->with('error', 'Customer not found in database.');
    }

    public function destroyBlacklist($matricNum)
    {
        $customer = Customer::where('matricNum', $matricNum)->first();
        if ($customer) {
            $customer->accStatus = 'active'; 
            $customer->blacklistReason = null;
            $customer->save();
            return redirect()->route('staff.blacklist.index')->with('success', 'Customer has been removed from the blacklist.');
        }
        return back()->with('error', 'Customer not found.');
    }

    public function searchCustomer($matric)
    {
        $customer = Customer::where('matricNum', $matric)->first();
        if ($customer) {
            return response()->json([
                'name' => $customer->name,
                'faculty' => $customer->faculty ?? 'N/A',            
                'collegeAddress' => $customer->collegeAddress ?? 'N/A',
                'icNum_passport' => $customer->icNum_passport,
                'email' => $customer->email
            ]);
        }
        return response()->json(null);
    }

    // ==========================================
    // OTHER SECTIONS
    // ==========================================

    public function incomeExpenses()
    {
        return view('staff.incomeexpenses'); 
    }

    public function missionsIndex(Request $request)
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }
        $status = $request->query('status');
        $staffID = auth()->guard('staff')->user()->staffID;
        $query = Mission::query();
        if ($status === 'available') {
            $query->where('status', 'Available');
        } elseif ($status === 'ongoing') {
            $query->where('status', 'Ongoing')->where('assigned_to', $staffID);
        } elseif ($status === 'completed') {
            $query->where('status', 'Completed')->where('assigned_to', $staffID);
        }
        $missions = $query->latest()->get();
        return view('staff.missions', compact('missions'));
    }

    public function missionStore(Request $request) 
    {
        Mission::create(['title' => $request->title, 'requirements' => $request->req, 'description' => $request->desc, 'commission' => $request->commission, 'remarks' => $request->remarks, 'status' => 'Available']);
        return back()->with('success', 'Task published successfully!');
    }

    public function missionAccept($id) 
    {
        $mission = Mission::findOrFail($id);
        $mission->update(['status' => 'Ongoing', 'assigned_to' => auth()->user()->staffID]);
        return back()->with('success', 'Task accepted! Check your ongoing records.');
    }

    public function missionShow($id) 
    {
        return Mission::findOrFail($id);
    }

    public function missionComplete($id)
    {
        $mission = Mission::findOrFail($id);
        if ($mission->assigned_to !== auth()->user()->staffID) {
            return back()->with('error', 'You are not authorized to complete this task.');
        }
        $mission->update(['status' => 'Completed']);
        return back()->with('success', 'Task marked as completed! Commission earned.');
    }

    public function pickupReturnSchedule()
    {
        $todayPickups = \App\Models\Booking::with(['fleet', 'customer'])
            ->where('bookingStat', 'approved')
            ->where(function ($query) {
                $query->whereNull('pickupForm')->orWhere('pickupForm', '');
            })
            ->orderBy('pickupDate', 'asc')
            ->get();

        $todayReturns = \App\Models\Booking::with(['fleet', 'customer'])
            ->where('bookingStat', 'approved')
            ->where(function ($query) {
                $query->whereNotNull('pickupForm')->where('pickupForm', '!=', '');
            })
            ->where(function ($query) {
                $query->whereNull('returnForm')->orWhere('returnForm', '');
            })
            ->orderBy('returnDate', 'asc')
            ->get();

        return view('staff.pickup-return', compact('todayPickups', 'todayReturns'));
    }

    /**
     * Show Booking Details (Full page or Modal)
     */
    public function showBooking(Request $request, $bookingID)
    {
        $booking = Booking::with(['fleet', 'customer'])->where('bookingID', $bookingID)->firstOrFail();
        $pickupInspection = Inspection::where('bookingID', $bookingID)->where('type', 'pickup')->first();
        $returnInspection = Inspection::where('bookingID', $bookingID)->where('type', 'return')->first();

        if ($request->ajax()) {
            return view('staff.partials.bookingmodal', compact('booking', 'pickupInspection', 'returnInspection'))->render();
        }

        return view('staff.viewdetails', compact('booking', 'pickupInspection', 'returnInspection'));
    }

    /**
     * NEW: AJAX Edit Form
     * Returns the HTML for the edit form inside the modal
     */
    public function editBooking(Request $request, $bookingID)
    {
        $booking = Booking::with(['fleet', 'customer'])->where('bookingID', $bookingID)->firstOrFail();
        $fleets = Fleet::orderBy('plateNumber')->get();

        if ($request->ajax()) {
            return view('staff.partials.editbooking-form', compact('booking', 'fleets'))->render();
        }

        return abort(404);
    }

    /**
     * Update Booking
     * Handles logic for saving edits and checking vehicle availability
     */
    public function updateBooking(Request $request, $bookingID)
    {
        $booking = Booking::where('bookingID', $bookingID)->firstOrFail();

        $validated = $request->validate([
            'plateNumber' => ['required', 'string', 'exists:fleet,plateNumber'],
            'pickupDate'  => ['required', 'date'],
            'returnDate'  => ['required', 'date', 'after_or_equal:pickupDate'],
            'bookingStat' => ['required', 'string'],
            'totalPrice'  => ['nullable', 'numeric'],
        ]);

        $newPlate = $validated['plateNumber'];
        $pickup = $validated['pickupDate'];
        $return = $validated['returnDate'];

        // Check for overlapping bookings (Conflict Check)
        $conflict = Booking::where('plateNumber', $newPlate)
            ->where('bookingID', '!=', $booking->bookingID)
            ->whereIn('bookingStat', ['approved', 'confirmed', 'active'])
            ->where(function($q) use ($pickup, $return) {
                $q->where(function($q2) use ($pickup, $return) {
                    $q2->where('pickupDate', '<=', $return)
                       ->where('returnDate', '>=', $pickup);
                });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'This vehicle is already booked for the selected period.');
        }

        // Apply updates
        $booking->update([
            'plateNumber' => $newPlate,
            'pickupDate'  => $pickup,
            'returnDate'  => $return,
            'bookingStat' => $validated['bookingStat'],
            'totalPrice'  => $validated['totalPrice'] ?? $booking->totalPrice,
        ]);

        return redirect()->route('staff.bookingmanagement')->with('success', 'Booking updated successfully.');
    }
}
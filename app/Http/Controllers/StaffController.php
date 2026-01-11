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
        $pickupsToday = Booking::whereDate('pickupDate', Carbon::today())
            ->whereIn('bookingStat', ['Confirmed', 'Approved'])
            ->count();
        
        $returnsToday = Booking::whereDate('returnDate', Carbon::today())
            ->where('bookingStat', 'Active')
            ->count();

        // 2. Recent Bookings
        // FIXED: Updated table names to standard plural ('bookings', 'fleets'). 
        // IF YOUR DB USES SINGULAR NAMES, change 'fleets' -> 'fleet' and 'bookings' -> 'booking' below.
        $recentBookings = Booking::join('fleet', 'booking.plateNumber', '=', 'fleet.plateNumber')
            ->select('booking.*', 'fleet.modelName', 'fleet.plateNumber')
            ->orderBy('booking.created_at', 'desc')
            ->limit(3)
            ->get();

        // 3. Fleet Distribution
        $totalCars = Fleet::count();
        $fleetDistribution = [
            'Perodua' => $totalCars > 0 ? round((Fleet::where('modelName', 'like', 'Perodua%')->count() / $totalCars) * 100) : 0,
            'Proton'  => $totalCars > 0 ? round((Fleet::where('modelName', 'like', 'Proton%')->count() / $totalCars) * 100) : 0,
            'Toyota'  => $totalCars > 0 ? round((Fleet::where('modelName', 'like', 'Toyota%')->count() / $totalCars) * 100) : 0,
        ];

        // 4. College Trends (The 10 UTM Colleges)
        $totalCustomers = Customer::count();
        $utmColleges = [
            'KOLEJ RAHMAN PUTRA', 'KOLEJ TUN FATIMAH', 'KOLEJ TUN RAZAK', 
            'KOLEJ TUN HUSSEIN ONN', 'KOLEJ TUN DR ISMAIL', 'KOLEJ DATO SERI ENDON', 
            'KOLEJ DATO ONN JAAFAR', 'KOLEJ TUNKU CANSELOR', 'KOLEJ 9', 'KOLEJ 10'
        ];

        $actualData = Customer::select('collegeAddress', DB::raw('count(*) as count'))
            ->whereNotNull('collegeAddress')
            ->groupBy('collegeAddress')
            ->pluck('count', 'collegeAddress')
            ->toArray();

        $collegeDistribution = [];
        foreach ($utmColleges as $college) {
            $count = $actualData[$college] ?? 0;
            $collegeDistribution[$college] = $totalCustomers > 0 ? round(($count / $totalCustomers) * 100) : 0;
        }

        $cars = Fleet::all();

        return view('staff.dashboard', compact(
            'pickupsToday', 
            'returnsToday', 
            'recentBookings',
            'fleetDistribution', 
            'collegeDistribution', 
            'cars'
        ));
    }

    /**
     * Check car availability for given dates.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'car_id' => 'required',
            'pickup' => 'required|date',
            'return' => 'required|date|after:pickup',
        ]);

        // NOTE: Ensure your Booking table has a 'carID' column. 
        // In the index() function, you used 'plateNumber'. Ensure these match your DB.
        $conflict = Booking::where('carID', $request->car_id)
            ->whereIn('bookingStat', ['Confirmed', 'Approved', 'Active'])
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('pickupDate', '<=', $request->return)
                      ->where('returnDate', '>=', $request->pickup);
                });
            })->exists();

        if ($conflict) {
            return back()->with('error', '❌ This car is unavailable for these dates.');
        }

        return back()->with('success', '✅ Success! This car is available.');
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
        // 1. Reconstruct the full email from the username input
        // This takes the input "john" and turns it into "john@hasta.com"
        if ($request->filled('email_username')) {
            $fullEmail = $request->input('email_username') . '@hasta.com';
            $request->merge(['email' => $fullEmail]);
        }

        // 2. Validate (The 'email' rule checks the reconstructed email above)
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
    // Find the staff member by their custom ID
    $staff = Staff::where('staffID', $staffID)->firstOrFail();

    // Return the view (ensure this view file exists)
    return view('staff.add-stafffunctioning', compact('staff'));
}

/**
 * Remove the specified staff member from storage.
 */
public function destroy($staffID): \Illuminate\Http\RedirectResponse
{
    // 1. Find the staff member
    $staff = Staff::where('staffID', $staffID)->firstOrFail();

    // 2. Prevent self-deletion if necessary
    if (Auth::guard('staff')->user()->staffID === $staff->staffID) {
        return back()->with('error', 'You cannot delete your own account.');
    }

    // 3. Delete the record
    $staff->delete();

    // 4. Redirect back to the staff list
    return redirect()->route('staff.add-staff')
        ->with('status', "Staff member $staffID has been successfully removed.");
}

public function update(Request $request, $staffID): \Illuminate\Http\RedirectResponse
    {
        // 1. Find the staff member
        $staff = Staff::where('staffID', $staffID)->firstOrFail();

        // 2. Reconstruct the full email address
        // The form sends 'email_username' (e.g., "john"), so we merge 'email' ("john@hasta.com") into the request.
        if ($request->filled('email_username')) {
            $fullEmail = $request->input('email_username') . '@hasta.com';
            $request->merge(['email' => $fullEmail]);
        }

        // 3. Validate
        // Now 'email' exists in the request, so 'required' validation will pass.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:50'],
            // We ignore the current staffID so it doesn't complain that the email is "already taken" by this user
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('staff')->ignore($staff->staffID, 'staffID')],
        ]);

        // 4. Update the record
        $staff->update($validated);

        // 5. Redirect
        return redirect()->route('staff.add-staff')
            ->with('status', "Staff member $staffID updated successfully!");
    }

    /**
     * Show form to add new staff (and list existing staff).
     */
public function create(Request $request): View
    {
        // 1. Auth Check
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        // 2. Start Query
        $query = Staff::query();

        // 3. Apply Search Filter (Name, ID, or Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('staffID', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 4. Apply Position Filter
        if ($request->filled('position') && $request->position !== 'All Positions') {
            $query->where('position', $request->position);
        }

        // 5. Get Filtered Results (Ordered by newest)
        $staffs = $query->orderBy('created_at', 'desc')->get();

        // 6. Calculate Static Counts (We count ALL staff for the cards, regardless of filter)
        // We use a separate query here so the cards don't change numbers when you search.
        $allStaff = Staff::all();
        
        $totalStaffCount = $allStaff->count();
        $driverCount = $allStaff->where('position', 'Driver')->count();
        // Combine Admin and IT Officer for the "Admin Staff" card
        $adminCount = $allStaff->whereIn('position', ['Administrator', 'IT Officer'])->count();
        $managerCount = $allStaff->where('position', 'Manager')->count();

        // 7. Return View
        return view('staff.add-staff', compact(
            'staffs', 
            'totalStaffCount', 
            'driverCount', 
            'adminCount', 
            'managerCount'
        ));
    }

    /**
     * Helper to show the 'functioning' creation view if needed.
     */
    public function createFunctioning() 
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }
        return view('staff.add-stafffunctioning');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Ensure user is authenticated
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        // 2. Pre-process Email
        $fullEmail = $request->input('email_username') . '@hasta.com';
        $request->merge(['email' => $fullEmail]);

        // 3. Validate
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email_username' => ['required', 'string', 'max:50', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff'],
            'position' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // 4. Auto-Generate Staff ID
        $latestStaff = \App\Models\Staff::orderBy('staffID', 'desc')->first();
        
        if (!$latestStaff) {
            $newStaffID = 'STAFF001';
        } else {
            $lastNumber = intval(substr($latestStaff->staffID, 5)); 
            $newStaffID = 'STAFF' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        // 5. Create Staff
        Staff::create([
            'name' => $request->name,
            'staffID' => $newStaffID,
            'email' => $request->email, 
            'position' => $request->position,
            'password' => Hash::make($request->password),
        ]);

        // --- CHANGED THIS LINE BELOW ---
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
 * 
 * This method calculates income from PAID payments only,
 * fetches chart data, and prepares recent transactions.
 */
public function dailyIncome(): \Illuminate\View\View
{
    // ========================================
    // 1. TODAY'S INCOME
    // ========================================
    // Only count payments that are marked as 'paid' or 'completed'
    $todayPayments = \App\Models\Payment::whereDate('paymentDate', \Carbon\Carbon::today())
        ->where('paymentStatus', 'paid') // Adjust this if you use 'completed', 'success', etc.
        ->get();
    
    $todayIncome = $todayPayments->sum('grandTotal'); // Using grandTotal as final income
    $transactionCount = $todayPayments->count();

    // ========================================
    // 2. YESTERDAY'S INCOME (for Change %)
    // ========================================
    $yesterdayIncome = \App\Models\Payment::whereDate('paymentDate', \Carbon\Carbon::yesterday())
        ->where('paymentStatus', 'paid')
        ->sum('grandTotal');

    // Calculate percentage change
    $change = $yesterdayIncome > 0 
        ? (($todayIncome - $yesterdayIncome) / $yesterdayIncome) * 100 
        : ($todayIncome > 0 ? 100 : 0);

    // ========================================
    // 3. RECENT TRANSACTIONS (for the table)
    // ========================================
    $recentTransactionsRaw = \App\Models\Payment::with(['booking.customer'])
        ->whereDate('paymentDate', \Carbon\Carbon::today())
        ->where('paymentStatus', 'paid')
        ->orderBy('paymentDate', 'desc')
        ->limit(10)
        ->get();

    // Format for the blade view
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

    // ========================================
    // 4. DAILY INCOME CHART (Last 7 Days)
    // ========================================
    $dailyIncomeChart = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = \Carbon\Carbon::today()->subDays($i);
        
        $dayIncome = \App\Models\Payment::whereDate('paymentDate', $date)
            ->where('paymentStatus', 'paid')
            ->sum('grandTotal');
        
        $dailyIncomeChart[] = [
            'date' => $date->format('Y-m-d'),
            'total' => round($dayIncome, 2)
        ];
    }

    // ========================================
    // 5. BOOKINGS TREND CHART (Last 7 Days)
    // ========================================
    $bookingsTrend = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = \Carbon\Carbon::today()->subDays($i);
        
        $bookingCount = \App\Models\Payment::whereDate('paymentDate', $date)
            ->where('paymentStatus', 'paid')
            ->count();
        
        $bookingsTrend[] = [
            'date' => $date->format('Y-m-d'),
            'count' => $bookingCount
        ];
    }

    

    // ========================================
    // 6. RETURN VIEW WITH ALL DATA
    // ========================================
    return view('staff.reports.dailyincome.index', compact(
        'todayIncome',
        'yesterdayIncome',
        'change',
        'transactionCount',
        'recentTransactions',
        'dailyIncomeChart',
        'bookingsTrend'
    ));
}
/**
 * Display the Monthly Income Report connected to the database.
 */
public function monthlyIncome(): \Illuminate\View\View
{
    $now = Carbon::now();
    $currentYear = $now->year;

    // 1. TOP CARDS DATA (Real Data)
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
    
    // Average Monthly based on months passed so far this year
    $monthsPassedSoFar = $now->month;
    $averageMonthly = $monthsPassedSoFar > 0 ? ($yearlyTotal / $monthsPassedSoFar) : 0;

    $cards = [
        'current_month'   => $currentMonthIncome,
        'previous_month'  => $previousMonthIncome,
        'average_monthly' => $averageMonthly,
        'yearly_total'    => $yearlyTotal,
    ];

    // 2. PAYMENT METHODS (Dynamic counts for Donut Chart)
    // Pulls from 'method' column in payment table
    $paymentMethods = \App\Models\Payment::select('method', DB::raw('count(*) as count'))
        ->where('paymentStatus', 'paid')
        ->groupBy('method')
        ->pluck('count', 'method')
        ->toArray();

    // 3. MONTHLY BREAKDOWN (Dynamic for Table and Bar Chart)
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

    // Loop through all 12 months to ensure even months with 0 income show up
    for ($m = 1; $m <= 12; $m++) {
        $monthData = $monthlyStats->get($m);
        $income = $monthData ? (float)$monthData->income : 0;
        
        // Calculate Growth % compared to the previous month in the loop
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

    return view('staff.reports.monthlyincome.index', compact(
        'cards',
        'paymentMethods',
        'breakdown'
    ));
}
    // ==========================================
    // BLACKLIST MANAGEMENT SECTION
    // ==========================================

    /**
     * Display Blacklist Page
     */
    public function blacklistIndex(): View
    {
        $blacklisted = Customer::where('accStatus', 'LIKE', 'blacklisted%')->get();
        $count = $blacklisted->count();

        return view('staff.blacklist', compact('blacklisted', 'count'));
    }

    /**
     * Add Customer to Blacklist
     */
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

    /**
     * Store Blacklist (Alternative Method)
     */
    public function storeBlacklist(Request $request)
    {
        $request->validate([
            'matricNum' => 'required|string',
            'reason' => 'required|string|max:255',
        ]);

        $customer = Customer::where('matricNum', $request->matricNum)->first();

        if ($customer) {
            $customer->accStatus = 'blacklisted'; 
            $customer->blacklistReason = $request->reason;
            $customer->save();

            return redirect()->route('staff.blacklist.index')
                ->with('success', 'Customer has been blacklisted successfully.');
        }

        return back()->with('error', 'Customer not found in database.');
    }

    /**
     * Remove Customer from Blacklist
     */
    public function destroyBlacklist($matricNum)
    {
        $customer = Customer::where('matricNum', $matricNum)->first();

        if ($customer) {
            $customer->accStatus = 'active'; 
            $customer->blacklistReason = null;
            $customer->save();

            return redirect()->route('staff.blacklist.index')
                ->with('success', 'Customer has been removed from the blacklist.');
        }

        return back()->with('error', 'Customer not found.');
    }

    /**
     * API: Search Customer by Matric No
     */
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
        // FIXED: Added safety check. If user is not logged in, 'auth()->user()' is null and this line would crash.
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $status = $request->query('status');
        $staffID = auth()->guard('staff')->user()->staffID;

        $query = Mission::query();

        // Filtering logic
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
        Mission::create([
            'title' => $request->title,
            'requirements' => $request->req,
            'description' => $request->desc,
            'commission' => $request->commission,
            'remarks' => $request->remarks,
            'status' => 'Available'
        ]);
        return back()->with('success', 'Task published successfully!');
    }

    public function missionAccept($id) 
    {
        $mission = Mission::findOrFail($id);
        $mission->update([
            'status' => 'Ongoing',
            'assigned_to' => auth()->user()->staffID
        ]);
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

        $mission->update([
            'status' => 'Completed'
        ]);

        return back()->with('success', 'Task marked as completed! Commission earned.');
    }
    public function pickupReturnSchedule()
    {
        // 1. PENDING PICKUPS
        // Logic: Booking is CONFIRMED + Pickup Form is EMPTY
        $todayPickups = \App\Models\Booking::with(['fleet', 'customer'])
            ->where('bookingStat', 'approved')
            ->where(function ($query) {
                $query->whereNull('pickupForm')
                      ->orWhere('pickupForm', '');
            })
            ->orderBy('pickupDate', 'asc') // Show oldest/most urgent first
            ->get();

        // 2. PENDING RETURNS
        // Logic: Booking is CONFIRMED (Active) + Pickup IS done + Return is EMPTY
        $todayReturns = \App\Models\Booking::with(['fleet', 'customer'])
            ->where('bookingStat', 'approved') // Only confirmed bookings are "active" on the road
            ->where(function ($query) {
                $query->whereNotNull('pickupForm')
                      ->where('pickupForm', '!=', '');
            })
            ->where(function ($query) {
                $query->whereNull('returnForm')
                      ->orWhere('returnForm', '');
            })
            ->orderBy('returnDate', 'asc')
            ->get();

        return view('staff.pickup-return', compact('todayPickups', 'todayReturns'));
    }

    public function showBooking($bookingID)
    {
        // 1. Fetch the Booking with relationships
        $booking = Booking::with(['fleet', 'customer'])->where('bookingID', $bookingID)->firstOrFail();

        // 2. Fetch Inspection Forms (if they exist)
        $pickupInspection = Inspection::where('bookingID', $bookingID)->where('type', 'pickup')->first();
        $returnInspection = Inspection::where('bookingID', $bookingID)->where('type', 'return')->first();

        // 3. Return the view
        return view('staff.viewdetails', compact('booking', 'pickupInspection', 'returnInspection'));
    }

}
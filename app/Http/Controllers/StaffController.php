<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Reward;
use App\Models\Booking;
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
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display the Staff Dashboard.
     */
public function index()
{
    // 1. Top Metric Counts
    $pickupsToday = \App\Models\Booking::whereDate('pickupDate', \Carbon\Carbon::today())
        ->whereIn('bookingStat', ['Confirmed', 'Approved'])
        ->count();
        
    $returnsToday = \App\Models\Booking::whereDate('returnDate', \Carbon\Carbon::today())
        ->where('bookingStat', 'Active')
        ->count();

    // 2. Recent Bookings (Database Join Fix)
    // NOTE: Change 'car_id' to whatever your actual column is in the booking table
    $recentBookings = Booking::join('fleet', 'booking.plateNumber', '=', 'fleet.plateNumber') // Change carID to plateNumber
        ->select('booking.*', 'fleet.modelName', 'fleet.plateNumber')
        ->orderBy('booking.created_at', 'desc')
        ->limit(3)
        ->get();

    // 3. Fleet Distribution (Using modelName)
    $totalCars = \App\Models\Fleet::count();
    $fleetDistribution = [
        'Perodua' => $totalCars > 0 ? round((\App\Models\Fleet::where('modelName', 'like', 'Perodua%')->count() / $totalCars) * 100) : 0,
        'Proton'  => $totalCars > 0 ? round((\App\Models\Fleet::where('modelName', 'like', 'Proton%')->count() / $totalCars) * 100) : 0,
        'Toyota'  => $totalCars > 0 ? round((\App\Models\Fleet::where('modelName', 'like', 'Toyota%')->count() / $totalCars) * 100) : 0,
    ];

    $cars = \App\Models\Fleet::all();

    return view('staff.dashboard', compact(
        'pickupsToday', 
        'returnsToday', 
        'recentBookings',
        'fleetDistribution', 
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

    // This query looks for any booking that conflicts with the user's dates
    $conflict = \App\Models\Booking::where('carID', $request->car_id)
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
        ->whereIn('bookingStat', ['confirmed', 'pending']) // Adjust based on your flow
        ->get();

    $todayReturns = Booking::with(['fleet', 'customer'])
        ->whereDate('returnDate', $today)
        ->where('bookingStat', 'active') // Assuming 'active' means car is currently out
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

    public function edit($staffID)
    {
        $staff = Staff::where('staffID', $staffID)->firstOrFail();
        // Re-use the same "functioning" file!
        return view('staff.add-stafffunctioning', compact('staff'));
    }

    /**
     * Update the staff's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
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

    public function update(Request $request, $staffID): \Illuminate\Http\RedirectResponse
{
    // Find the staff by their custom staffID
    $staff = Staff::where('staffID', $staffID)->firstOrFail();

    // Validate the data
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'position' => ['required', 'string', 'max:50'],
        'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('staff')->ignore($staff->staffID, 'staffID')],
    ]);

    // Save changes
    $staff->update($validated);

    // Redirect back to the staff record list
    return redirect()->route('staff.add-staff')->with('status', "Staff member $staffID updated successfully!");
}

    /**
     * Show form to add new staff.
     */
    public function create(): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

    $staffs = Staff::all();

    // 3. Send the data to the page
    return view('staff.add-staff', [
        'staffs' => $staffs, // This fills the table
        'totalStaffCount' => $staffs->count(), // Fills Card 1
        'driverCount' => $staffs->where('position', 'Driver')->count(), // Fills Card 2
        'adminCount' => $staffs->where('position', 'Administrator')->count(), // Fills Card 3
        'managerCount' => $staffs->where('position', 'Manager')->count(), // Fills Card 4
    ]);

        // FIX: Changed from 'staff.add' to 'staff.add-staff'
        return view('staff.add-staff'); 
    }

    /**
     * Store a new staff member.
     */
    public function createFunctioning() 
{
    // Check if staff is logged in
    if (!auth()->guard('staff')->check()) {
        return redirect()->route('login');
    }

    // This looks for the file: resources/views/staff/add-stafffunctioning.blade.php
    return view('staff.add-stafffunctioning');
}

    public function store(Request $request): RedirectResponse
{
    // 1. Ensure user is authenticated
    if (!Auth::guard('staff')->check()) {
        return redirect()->route('login');
    }

    // 2. Pre-process Email
    // Combine the username input with the hardcoded domain
    $fullEmail = $request->input('email_username') . '@hasta.com';

    // Merge this full email back into the request data so we can validate 'email'
    $request->merge(['email' => $fullEmail]);

    // 3. Validate
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email_username' => ['required', 'string', 'max:50', 'alpha_dash'], // Ensure username has no spaces/weird chars
        'email' => ['required', 'string', 'email', 'max:255', 'unique:staff'], // Validate the FULL email
        'position' => ['required', 'string', 'max:50'],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
    ]);

    // 4. Auto-Generate Staff ID
    $latestStaff = Staff::orderBy('staffID', 'desc')->first();
    
    if (!$latestStaff) {
        $newStaffID = 'STAFF001';
    } else {
        // Extract the number part from 'STAFF005'
        // 'STAFF' is 5 characters long, so we substring from index 5
        $lastNumber = intval(substr($latestStaff->staffID, 5)); 
        $newStaffID = 'STAFF' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    // 5. Create Staff
    Staff::create([
        'name' => $request->name,
        'staffID' => $newStaffID,
        'email' => $request->email, // This now contains 'username@hasta.com'
        'position' => $request->position,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('staff.add-stafffunctioning')
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
     * Bookings management list for staff.
     */
    public function bookingManagement(Request $request): View
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }  
        // 1. Start the query builder (don't use ->get() yet)
        $query = Booking::with(['fleet', 'customer']);

        // 2. Filter by Search (Booking ID or Plate Number)
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

        // 3. Get the results (paginated)
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        $totalBookings = Booking::count();
        $confirmedCount = Booking::where('bookingStat', 'confirmed')->count();
        $pendingCount = Booking::where('bookingStat', 'pending')->count();
        $completedCount = Booking::where('bookingStat', 'completed')->count();
        $cancelledCount = Booking::where('bookingStat', 'cancelled')->count();

        

        // Pending verification: if the `payment_status` column exists, use it;
        // otherwise fall back to counting pending bookings.
        if (Schema::hasColumn('booking', 'payment_status')) {
            $pendingVerificationCount = Booking::whereRaw('LOWER(bookingStat) = ?', ['pending'])
                ->where(function($q) {
                    $q->whereNull('payment_status')
                      ->orWhereRaw('LOWER(payment_status) <> ?', ['paid']);
                })->count();
        } else {
            $pendingVerificationCount = Booking::whereRaw('LOWER(bookingStat) = ?', ['pending'])->count();
        }

        return view('staff.bookingmanagement', [
            'bookings' => $bookings,
            'totalBookings' => $totalBookings,
            'confirmedCount' => $confirmedCount,
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount
        ]);
    }

    /**
     * Display the Fleet Management page with filtering and search.
     */
    public function fleet(Request $request)
    {
        // 1. Calculate Stats
        $stats = [
            'total'       => Fleet::count(),
            'available'   => Fleet::where('status', 'available')->count(),
            'rented'      => Fleet::where('status', 'rented')->count(),
            'maintenance' => Fleet::where('status', 'maintenance')->count(),
        ];

        // 2. Setup query
        $query = Fleet::query();

        // 3. Search Logic
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        // 4. Filter Logic
        if ($filter = $request->input('filter')) {
            if ($filter !== 'all') {
                $query->where('status', $filter);
            }
        }

        $vehicles = $query->latest()->paginate(9)->withQueryString();

        return view('staff.fleet.index', compact('vehicles', 'stats'));
    }

    /**
     * Display Rewards for Staff.
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
     * Display Blacklist Page
     */
    public function blacklistIndex(): View
    {
        // Fetch customers who are blacklisted
        $blacklisted = Customer::where('accStatus', 'LIKE', 'blacklisted%')->get();
        $count = $blacklisted->count();

        return view('staff.blacklist', compact('blacklisted', 'count'));
    }

    /**
     * Update a customer's status to blacklisted
     */
    public function addToBlacklist(Request $request) 
    {
        $customer = Customer::where('matricNum', $request->matricNum)->first();
        if ($customer) {
            $customer->update(['accStatus' => 'blacklisted: ' . $request->reason]);
            return back()->with('success', 'Customer blacklisted successfully.');
        }
        return back()->with('error', 'Customer not found.');
    }

    public function storeBlacklist(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'matricNum' => 'required',
            'reason' => 'required'
        ]);

        // 2. Find the customer
        $customer = Customer::where('matricNum', $request->matricNum)->first();

        if ($customer) {
            // 3. Update the status with the prefix
            // This changes NULL to "blacklisted: Your Reason"
            $customer->accStatus = 'blacklisted: ' . $request->reason;
            
            // 4. Save to database
            $customer->save();

            return redirect()->route('staff.blacklist.index')
                            ->with('success', 'Customer blacklisted successfully.');
        }

        return back()->with('error', 'Customer not found in database.');
    }

    /**
     * API: Search Customer by Matric No
     * This is what the Javascript fetches
     */
    public function searchCustomer($matric)
{
    // Search by matricNum as defined in your Customer model
    $customer = \App\Models\Customer::where('matricNum', $matric)->first();
    
    if ($customer) {
        return response()->json([
            'name' => $customer->name,
            'faculty' => $customer->faculty ?? 'N/A', // Send Faculty
            'collegeAddress' => $customer->collegeAddress ?? 'N/A', // Send College
            'icNum_passport' => $customer->icNum_passport,
            'email' => $customer->email
        ]);
    }
    return response()->json(null); // Return null if not found (triggers JS 'else')
}

    public function destroyBlacklist($matricNum)
    {
        // 1. Find the customer by their matric number
        $customer = Customer::where('matricNum', $matricNum)->first();

        if ($customer) {
            // 2. Set the status back to NULL (or 'active')
            // This removes them from the "blacklisted" query results
            $customer->accStatus = null; 
            $customer->save();

            return redirect()->route('staff.blacklist.index')
                            ->with('success', 'Customer has been removed from the blacklist.');
        }

        return back()->with('error', 'Customer not found.');
    }

    /**
     * Add to Blacklist
     */
    public function blacklistStore(Request $request)
    {
        $request->validate([
            'matricNum' => 'required|string',
            'reason' => 'required|string|max:255',
        ]);

        $customer = Customer::where('matricNumber', $request->matricNum)->first();

        if ($customer) {
            // Update status
            $customer->accStatus = 'blacklisted: ' . $request->reason;
            $customer->save();
            return back()->with('success', 'Customer has been blacklisted successfully.');
        }

        return back()->with('error', 'Customer not found.');
    }

    /**
     * Remove from Blacklist
     */
    public function blacklistDestroy($matric)
    {
        $customer = Customer::where('matricNumber', $matric)->first();

        if ($customer) {
            $customer->accStatus = 'active';
            $customer->save();
            return back()->with('success', 'Customer removed from blacklist.');
        }

        return back()->with('error', 'Customer not found.');
    }

    public function incomeExpenses()
    {
        return view('staff.incomeexpenses'); 
    }

    public function missionsIndex(Request $request)
    {
        $status = $request->query('status');
        $staffID = auth()->user()->staffID;

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
            'assigned_to' => auth()->user()->staffID // Assuming staff is logged in
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

        // Security check: only the assigned staff can complete it
        if ($mission->assigned_to !== auth()->user()->staffID) {
            return back()->with('error', 'You are not authorized to complete this task.');
        }

        $mission->update([
            'status' => 'Completed'
        ]);

        return back()->with('success', 'Task marked as completed! Commission earned.');
    }
}
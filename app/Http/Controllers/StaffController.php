<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Reward;
use App\Models\Booking;
use App\Models\Inspection;
use App\Models\Fleet;
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
        // 1. GET PENDING PICKUPS
        // Criteria: Status is 'confirmed' AND Pickup Form is empty
        $todayPickups = Booking::with(['fleet', 'customer'])
            ->where('bookingStat', 'approved') // Only show approved bookings
            ->where(function ($query) {
                $query->whereNull('pickupForm')
                    ->orWhere('pickupForm', '')
                    ->orWhere('pickupForm', '0'); // Handle different "empty" states
            })
            ->orderBy('pickupDate', 'asc') // Show earliest bookings first
            ->get();

        // 2. GET PENDING RETURNS
        // Criteria: Status is NOT cancelled/completed AND Pickup is DONE AND Return is EMPTY
        $todayReturns = Booking::with(['fleet', 'customer'])
            ->whereNotIn('bookingStat', ['cancelled', 'completed', 'pending'])
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

        // Debugging: If still 0, uncomment the line below to see all bookings in browser
        // dd(Booking::all()->toArray()); 

        return view('staff.dashboard', compact('todayPickups', 'todayReturns'));
    }

    /**
     * Display the Pickup & Return Inspection page.
     */
    public function pickupReturn()
{
    $today = now()->format('Y-m-d');

    $todayPickups = Booking::with(['fleet', 'customer'])
        ->whereDate('pickupDate', $today)
        ->whereIn('bookingStat', ['approved', 'pending']) // Adjust based on your flow
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
     * Show form to add new staff.
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
        \App\Models\Staff::create([
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
        $approvedCount = Booking::where('bookingStat', 'approved')->count();
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
            'approvedCount' => $approvedCount,
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
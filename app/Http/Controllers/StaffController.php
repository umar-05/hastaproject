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

        $cars = Fleet::all();

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
     * Show Edit form for a specific staff member (Admin view).
     */
    public function edit($staffID)
    {
        $staff = Staff::where('staffID', $staffID)->firstOrFail();
        return view('staff.add-stafffunctioning', compact('staff'));
    }

    /**
     * Update a specific staff member (Admin view).
     */
    public function update(Request $request, $staffID): RedirectResponse
    {
        $staff = Staff::where('staffID', $staffID)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('staff')->ignore($staff->staffID, 'staffID')],
        ]);

        $staff->update($validated);

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

        return view('staff.add-staff', [
            'staffs' => $staffs,
            'totalStaffCount' => $staffs->count(),
            'driverCount' => $staffs->where('position', 'Driver')->count(),
            'adminCount' => $staffs->where('position', 'Administrator')->count(),
            'managerCount' => $staffs->where('position', 'Manager')->count(),
        ]);
    }

    /**
     * Helper to show the 'functioning' creation view if needed.
     */
    public function createFunctioning() 
    {
        if (!auth()->guard('staff')->check()) {
            return redirect()->route('login');
        }
        return view('staff.add-stafffunctioning');
    }

    /**
     * Store a new staff member.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        // Combine username with domain
        $fullEmail = $request->input('email_username') . '@hasta.com';
        $request->merge(['email' => $fullEmail]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email_username' => ['required', 'string', 'max:50', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff'],
            'position' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Auto-Generate Staff ID
        $latestStaff = Staff::orderBy('staffID', 'desc')->first();
        
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
     * Bookings management list.
     */
    public function bookingManagement(Request $request): View
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
        $confirmedCount = Booking::where('bookingStat', 'confirmed')->count();
        $pendingCount = Booking::where('bookingStat', 'pending')->count();
        $completedCount = Booking::where('bookingStat', 'completed')->count();
        $cancelledCount = Booking::where('bookingStat', 'cancelled')->count();

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

    // ==========================================
    // BLACKLIST MANAGEMENT SECTION
    // ==========================================

    /**
     * Display Blacklist Page
     */
    public function blacklistIndex(): View
    {
        // Fetch customers who have the status 'blacklisted'
        $blacklisted = Customer::where('accStatus', 'LIKE', 'blacklisted%')->get();
        $count = $blacklisted->count();

        return view('staff.blacklist', compact('blacklisted', 'count'));
    }

    /**
     * Add Customer to Blacklist (Restored Method)
     * This fixes the "Call to undefined method" error.
     */
    public function addToBlacklist(Request $request) 
    {
        $customer = Customer::where('matricNum', $request->matricNum)->first();
        
        if ($customer) {
            // FIX: Update BOTH status and reason
            $customer->accStatus = 'blacklisted'; 
            $customer->blacklistReason = $request->reason;
            $customer->save();
            
            return back()->with('success', 'Customer blacklisted successfully.');
        }
        return back()->with('error', 'Customer not found.');
    }

    /**
     * Store Blacklist (Alternative Method)
     * Keeping this in case your form points here instead.
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
            // Reset status to active and clear reason
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
        $status = $request->query('status');
        $staffID = auth()->user()->staffID;

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
}
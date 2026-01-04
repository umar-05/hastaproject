<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Reward;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /**
     * Display the Staff Dashboard.
     */
    public function index(): View
    {
        // Ensure user is authenticated
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        // Example stats logic - adjust status strings based on your actual database values
        $bookingsToManage = Booking::where('bookingStat', 'Pending')->count();
        $pickupsToday = Booking::whereDate('pickupDate', now())->where('bookingStat', 'Confirmed')->count();
        $returnsToday = Booking::whereDate('returnDate', now())->where('bookingStat', 'Active')->count();

        return view('staff.dashboard', [
            'bookingsToManage' => $bookingsToManage,
            'pickupsToday' => $pickupsToday,
            'returnsToday' => $returnsToday,
        ]);
    }

    /**
     * Display the Pickup & Return Inspection page.
     */
    public function pickupReturn(): View
    {
        // Ensure user is authenticated
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        // Get pickups scheduled for today
        $todayPickups = Booking::with(['customer', 'fleet'])
            ->whereDate('pickup_date', now())
            ->where('bookingStat', 'Confirmed') // Adjust status as needed
            ->orderBy('pickupDate')
            ->get();

        // Get active rentals that need returning
        $pendingReturns = Booking::with(['customer', 'fleet'])
            ->where('bookingStat', 'Active') // Adjust status as needed
            ->orderBy('returnDate')
            ->get();

        return view('staff.pickup-return', [
            'todayPickups' => $todayPickups,
            'pendingReturns' => $pendingReturns,
        ]);
    }

    /**
     * Show the profile edit form.
     */
    public function editProfile(Request $request): View
    {
        // Ensure user is authenticated
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        return view('staff.profile.edit', [
            'user' => $request->user('staff'),
        ]);
    }

    /**
     * Update the staff's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        // 1. Validate the input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Use 'staffID' as the unique key to ignore
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

        // 2. Fill the user model with validated data
        $user = $request->user('staff');
        $user->fill($validated);

        // 3. Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Save to Database
        $user->save();

        return Redirect::route('staff.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Show form to add new staff.
     */
    public function create(): View
    {
        // Ensure user is authenticated
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        return view('staff.add'); // Ensure this view exists
    }

    /**
     * Store a new staff member.
     */
    public function store(Request $request): RedirectResponse
    {
        // Ensure user is authenticated
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'staffID' => ['required', 'string', 'max:10', 'unique:staff'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:staff'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        Staff::create([
            'name' => $request->name,
            'staffID' => $request->staffID,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('staff.dashboard')->with('status', 'Staff member added successfully!');
    }

    /**
     * Show reports page.
     */
    public function reports(): View
    {
        // Ensure user is authenticated
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        return view('staff.reports'); // Ensure this view exists
    }

    public function rewards()
{
    // 1. Fetch data from the database
    $activeRewards = Reward::where('rewardStatus', 'Active')->get();
    $inactiveRewards = Reward::where('rewardStatus', 'Inactive')->get();

    // 2. Prepare the $stats variable that the view is looking for
    $stats = [
        'total'  => Reward::count(),
        'active' => $activeRewards->count(),
        'slots'  => $activeRewards->sum('totalClaimable'),
    ];

    // 3. Pass all variables to the view using compact
    return view('staff.rewards', compact('activeRewards', 'inactiveRewards', 'stats'));
}

}
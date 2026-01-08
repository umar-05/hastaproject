<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Fleet;
use App\Models\Owner;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\CarbonPeriod;

class FleetController extends Controller
{
    // ... index, create, bookings, overview, maintenance ... 
    // (Keep previous methods index, create, bookings, overview, maintenance, storeMaintenance, owner as they were)

    public function index(\Illuminate\Http\Request $request)
{
    // Start the query
    $query = Fleet::orderBy('created_at', 'desc');

    // 1. Handle Search
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('plateNumber', 'like', '%'.$search.'%')
              ->orWhere('modelName', 'like', '%'.$search.'%');
        });
    }

    // 2. Handle Status Filter
    if ($request->has('status') && $request->status != 'all') {
        $status = $request->status;
        if ($status == 'rented') {
            $query->whereIn('status', ['booked', 'rented']);
        } else {
            $query->where('status', $status);
        }
    }

    // Execute query with pagination
    $fleet = $query->paginate(12)->withQueryString();

    // Stats (Always Global)
    $totalVehicles = Fleet::count();
    $availableCount = Fleet::where('status', 'available')->count();
    $rentedCount = Fleet::whereIn('status', ['booked', 'rented'])->count();
    $maintenanceCount = Fleet::where('status', 'maintenance')->count();

    return view('staff.fleet.index', compact('fleet', 'totalVehicles', 'availableCount', 'rentedCount', 'maintenanceCount'));
}

    public function create()
    {
        return view('staff.fleet.create');
    }

    public function getOwnerByIc(\Illuminate\Http\Request $request)
{
    // 1. Get IC from query string (?ic=...)
    $rawIc = $request->query('ic');

    if (!$rawIc) {
        return response()->json(['found' => false]);
    }

    // 2. Sanitize: Remove dashes, spaces, symbols. Keep ONLY numbers.
    // Example: "050716-04-0127" -> "050716040127"
    $cleanIc = preg_replace('/[^0-9]/', '', $rawIc);

    // 3. Search Database
    // We use 'like' for the first check to be safer against trailing spaces in DB
    $owner = Owner::where('ownerIC', $cleanIc)
                  ->orWhere('ownerIC', 'LIKE', $cleanIc) 
                  ->first();

    // 4. Return Data
    if ($owner) {
        return response()->json([
            'found' => true,
            'data' => [
                'ownerName' => $owner->ownerName,
                'ownerEmail' => $owner->ownerEmail,
                'ownerPhoneNum' => $owner->ownerPhoneNum,
            ]
        ]);
    }

    return response()->json(['found' => false, 'debug_searched' => $cleanIc]);
}

    public function bookings($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        $bookings = Booking::where('plateNumber', $plateNumber)
                           ->with('customer')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('staff.fleet.tabs.bookings', compact('fleet', 'bookings'));
    }

    public function overview($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        $availabilityCalendar = [];

        $bookings = Booking::where('plateNumber', $plateNumber)
            ->whereIn('bookingStat', ['approved', 'confirmed', 'active']) 
            ->get();

        foreach ($bookings as $booking) {
            if ($booking->pickupDate && $booking->returnDate) {
                $period = CarbonPeriod::create($booking->pickupDate, $booking->returnDate);
                foreach ($period as $date) {
                    $availabilityCalendar[$date->format('Y-m-d')] = ['status' => 'booked'];
                }
            }
        }

        $maintenances = Maintenance::where('plateNumber', $plateNumber)->get();
        foreach ($maintenances as $m) {
            if ($m->mDate) {
                $availabilityCalendar[$m->mDate->format('Y-m-d')] = ['status' => 'maintenance'];
            }
        }

        return view('staff.fleet.tabs.overview', compact('fleet', 'availabilityCalendar'));
    }

    public function maintenance($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        $maintenances = $fleet->maintenance()->orderBy('mDate', 'desc')->get();
        return view('staff.fleet.tabs.maintenance', compact('fleet', 'maintenances'));
    }

    public function storeMaintenance(\Illuminate\Http\Request $request, $plateNumber)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'mDate' => 'required|date',
            'mTime' => 'nullable',
            'cost' => 'required|numeric|min:0',
        ]);

        Maintenance::create([
            'maintenanceID' => 'M-' . strtoupper(uniqid()), 
            'plateNumber' => $plateNumber,
            'description' => $validated['description'],
            'mDate' => $validated['mDate'],
            'mTime' => $validated['mTime'],
            'cost' => $validated['cost'],
        ]);

        return redirect()
            ->route('staff.fleet.tabs.maintenance', $plateNumber)
            ->with('success', 'Maintenance record added successfully.');
    }

    public function owner($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        return view('staff.fleet.tabs.owner', compact('fleet'));
    }

    // --- UPDATED STORE METHOD ---
    public function store(\Illuminate\Http\Request $request)
{
    // 1. Validate ALL inputs
    $validated = $request->validate([
        // Basic
        'plateNumber' => 'required|string|unique:fleet,plateNumber',
        'modelName'   => 'required|string|max:255',
        'year'        => 'required|integer|min:1900|max:2100',
        'color'       => 'nullable|string',
        'price'       => 'required|numeric', // Added price validation
        'status'      => 'required|string',

        // Gallery (Images)
        'photo1'      => 'nullable|image|max:5120', // 5MB max
        'photo2'      => 'nullable|image|max:5120',
        'photo3'      => 'nullable|image|max:5120',

        // Docs (PDF or Images)
        'roadtaxStat'       => 'nullable|string',
        'roadtaxActiveDate' => 'nullable|date',
        'roadtaxExpiryDate' => 'nullable|date',
        'roadtaxFile'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        'insuranceStat'       => 'nullable|string',
        'insuranceActiveDate' => 'nullable|date',
        'insuranceExpiryDate' => 'nullable|date',
        'insuranceFile'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        'grantFile'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        // Owner Info (Required for Foreign Key)
        'ownerIC'       => 'required|string',
        'ownerName'     => 'required|string',
        'ownerEmail'    => 'nullable|email',
        'ownerPhoneNum' => 'nullable|string',
    ]);

    // 2. Handle Owner (Update existing or Create new)
    // This prevents the "Foreign Key Constraint" error
    $owner = Owner::updateOrCreate(
        ['ownerIC' => $validated['ownerIC']], // Search by IC
        [
            'ownerName'     => $validated['ownerName'],
            'ownerEmail'    => $validated['ownerEmail'],
            'ownerPhoneNum' => $validated['ownerPhoneNum'],
            // Add default password or address if your DB requires it and it's nullable
        ]
    );

    // 3. Prepare Data for Fleet
    $data = [
        'plateNumber' => $validated['plateNumber'],
        'modelName'   => $validated['modelName'],
        'year'        => $validated['year'],
        'color'       => $validated['color'] ?? null,
        'price'       => $validated['price'],
        'status'      => $validated['status'],
        'ownerIC'     => $validated['ownerIC'], // Link to the owner we just processed
        
        // Map Statuses and Dates
        'roadtaxStat'         => $validated['roadtaxStat'] ?? 'Inactive',
        'roadtaxActiveDate'   => $validated['roadtaxActiveDate'] ?? null,
        'roadtaxExpiryDate'   => $validated['roadtaxExpiryDate'] ?? null,
        
        'insuranceStat'       => $validated['insuranceStat'] ?? 'Inactive',
        'insuranceActiveDate' => $validated['insuranceActiveDate'] ?? null,
        'insuranceExpiryDate' => $validated['insuranceExpiryDate'] ?? null,
    ];

    // 4. Handle File Uploads (Loop to keep code clean)
    $fileFields = ['photo1', 'photo2', 'photo3', 'roadtaxFile', 'insuranceFile', 'grantFile'];

    foreach ($fileFields as $field) {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $path = $file->store('documents', 'public');
            $data[$field] = $path;
        }
    }

    // 5. Create Fleet Record
    \App\Models\Fleet::create($data);

    return redirect()->route('staff.fleet.index')->with('success', 'Vehicle registered successfully.');
}

    public function edit($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        return view('staff.fleet.edit', compact('fleet'));
    }

    public function update(\Illuminate\Http\Request $request, $plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();

        $validated = $request->validate([
            'modelName' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:1900|max:2100',
            'status' => 'nullable|string',
            'grantFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', 
            'roadtaxFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'insuranceFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        foreach (['grantFile', 'roadtaxFile', 'insuranceFile'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                if ($fleet->$fileKey && Storage::disk('public')->exists($fleet->$fileKey)) {
                    Storage::disk('public')->delete($fleet->$fileKey);
                }
                $path = $request->file($fileKey)->store('documents', 'public');
                $fleet->$fileKey = $path;
            }
        }

        $fleet->fill(array_filter(
            $request->only(['modelName', 'year', 'status']), 
            fn($value) => !is_null($value)
        ));
        
        $fleet->save();

        return back()->with('success', 'Vehicle details updated successfully.');
    }

    public function destroy($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        foreach (['grantFile', 'roadtaxFile', 'insuranceFile'] as $fileKey) {
            if ($fleet->$fileKey && Storage::disk('public')->exists($fleet->$fileKey)) {
                Storage::disk('public')->delete($fleet->$fileKey);
            }
        }

        $fleet->delete();

        return redirect()->route('staff.fleet.index')->with('success', 'Vehicle deleted.');
    }

    public function show($plateNumber)
    {
        return redirect()->route('staff.fleet.tabs.overview', $plateNumber);
    }
}
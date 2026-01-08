<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Fleet;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\CarbonPeriod;

class FleetController extends Controller
{
    public function index()
    {
        $fleet = Fleet::orderBy('created_at', 'desc')->paginate(12);
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

    public function bookings($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // FETCH DATA: Get bookings for this car, ordered by newest first
        // Ensure you have "use App\Models\Booking;" at the top of the file
        $bookings = Booking::where('plateNumber', $plateNumber)
                           ->with('customer') // Optimize query by eager loading customer data
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('staff.fleet.tabs.bookings', compact('fleet', 'bookings'));
    }

    public function overview($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // 1. Initialize Calendar Array
        $availabilityCalendar = [];

        // 2. Fetch Bookings (Approved/Active/Confirmed)
        // Adjust 'bookingStat' values based on exactly what you store in DB (e.g., 'approved', 'active')
        $bookings = Booking::where('plateNumber', $plateNumber)
            ->whereIn('bookingStat', ['approved', 'confirmed', 'active']) 
            ->get();

        foreach ($bookings as $booking) {
            // Create a range of dates from Pickup to Return
            if ($booking->pickupDate && $booking->returnDate) {
                $period = CarbonPeriod::create($booking->pickupDate, $booking->returnDate);
                
                foreach ($period as $date) {
                    $availabilityCalendar[$date->format('Y-m-d')] = ['status' => 'booked'];
                }
            }
        }

        // 3. (Optional) Fetch Maintenance Dates
        // If your Maintenance model has dates, do the same logic here with status 'maintenance'
        $maintenances = Maintenance::where('plateNumber', $plateNumber)->get();
        foreach ($maintenances as $m) {
            if ($m->mDate) {
                // Assuming single day maintenance, or use range if you have end date
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

    public function store(\Illuminate\Http\Request $request)
    {
        // 1. Validate inputs
        $validated = $request->validate([
            'plateNumber' => 'required|string|unique:fleet,plateNumber',
            'modelName'   => 'required|string|max:255',
            'year'        => 'required|integer|min:1900|max:2100',
            'color'       => 'nullable|string',
            'status'      => 'nullable|string',
            'photo1'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'grantFile'   => 'nullable|mimes:pdf,jpg,png|max:5120',
        ]);

        // 2. Prepare data array
        $data = [
            'plateNumber' => $validated['plateNumber'],
            'modelName'   => $validated['modelName'],
            'year'        => $validated['year'],
            'color'       => $validated['color'] ?? null,
            'status'      => $validated['status'] ?? 'available',
        ];

        // 3. Handle Photo Upload (Public Images)
        if ($request->hasFile('photo1')) {
            $image = $request->file('photo1');
            $imageName = $validated['plateNumber'] . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['photo1'] = $imageName;
        }

        // 4. Handle Grant File Upload (Storage)
        if ($request->hasFile('grantFile')) {
            $path = $request->file('grantFile')->store('documents', 'public');
            $data['grantFile'] = $path;
        }

        // 5. Create Record
        Fleet::create($data);

        return redirect()->route('staff.fleet.index')->with('success', 'Vehicle added successfully.');
    }

    public function edit($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        return view('staff.fleet.edit', compact('fleet'));
    }

    // --- UPDATED METHOD: Handles Document Uploads & Partial Updates ---
    public function update(\Illuminate\Http\Request $request, $plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();

        // 1. Validate (allow nullable for everything so partial updates work)
        $validated = $request->validate([
            'modelName' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:1900|max:2100',
            'status' => 'nullable|string',
            // File validations
            'grantFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'roadtaxFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'insuranceFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // 2. Handle File Uploads
        foreach (['grantFile', 'roadtaxFile', 'insuranceFile'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                // Delete old file if exists
                if ($fleet->$fileKey && Storage::disk('public')->exists($fleet->$fileKey)) {
                    Storage::disk('public')->delete($fleet->$fileKey);
                }
                
                // Store new file in 'storage/app/public/documents'
                $path = $request->file($fileKey)->store('documents', 'public');
                $fleet->$fileKey = $path;
            }
        }

        // 3. Update non-file fields if they are in the request
        $fleet->fill(array_filter(
            $request->only(['modelName', 'year', 'status']), 
            fn($value) => !is_null($value)
        ));
        
        $fleet->save();

        // Return back to the previous page (useful for staying on the specific tab)
        return back()->with('success', 'Vehicle details updated successfully.');
    }

    public function destroy($plateNumber)
    {
        $fleet = Fleet::where('plateNumber', $plateNumber)->firstOrFail();
        
        // Optional: Delete associated files when deleting vehicle
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
        // Redirect to overview tab by default as per new layout structure
        return redirect()->route('staff.fleet.tabs.overview', $plateNumber);
    }
}
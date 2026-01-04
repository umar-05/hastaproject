{{-- resources/views/staff/fleet/index.blade.php --}}

@extends('layouts.staff-layout') 
{{-- Gantikan 'layouts.staff-layout' jika nama layout anda berbeza --}}

@section('content')
<div class="container-fluid py-4">

    {{-- Title and Management Description --}}
    <h3 class="mb-1">Fleet Management</h3>
    <p class="text-muted">Manage your rental vehicle fleet</p>

    {{-- 1. TOP SUMMARY CARDS --}}
    {{-- Kita akan kira data ini dalam VehicleController@index --}}
    <div class="row">
        {{-- Card 1: Total Vehicles --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm border-start border-danger border-5">
                <div class="d-flex align-items-center">
                    <i class="fas fa-car-side fa-2x text-danger opacity-7 me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $totalVehicles ?? $fleet->total() }}</h5>
                        <p class="text-sm mb-0">Total Vehicles</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Available --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm border-start border-success border-5">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x text-success opacity-7 me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $availableCount ?? 0 }}</h5>
                        <p class="text-sm mb-0">Available</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Rented/Booked --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm border-start border-warning border-5">
                <div class="d-flex align-items-center">
                    <i class="fas fa-key fa-2x text-warning opacity-7 me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $rentedCount ?? 0 }}</h5>
                        <p class="text-sm mb-0">Rented</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Maintenance --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm border-start border-info border-5">
                <div class="d-flex align-items-center">
                    <i class="fas fa-tools fa-2x text-info opacity-7 me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $maintenanceCount ?? 0 }}</h5>
                        <p class="text-sm mb-0">Maintenance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- 2. FILTERING AND ADD VEHICLE SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            {{-- Filtering Buttons (Simplified) --}}
            <button class="btn btn-danger btn-sm me-2">All Vehicles</button>
            <button class="btn btn-outline-secondary btn-sm me-2">Available</button>
            <button class="btn btn-outline-secondary btn-sm me-2">Rented</button>
            <button class="btn btn-outline-secondary btn-sm">Maintenance</button>
        </div>
        
        <div class="d-flex align-items-center">
            {{-- Search Bar (Simple input) --}}
            <input type="text" class="form-control me-3" placeholder="Search vehicles..." style="width: 200px;">

            {{-- Add Vehicle Button --}}
            <a href="{{ route('staff.fleet.create') }}" class="btn btn-danger">
                <i class="fas fa-plus me-1"></i> Add Vehicle
            </a>
        </div>
    </div>


    {{-- 3. VEHICLE CARD LISTING --}}
    <div class="row">
        {{-- Loop through the $fleet collection (passed from controller) --}}
        @forelse ($fleet as $car)
        @php
            $status = strtolower($car->status);
            $statusClass = match($status) {
                'available' => 'bg-success',
                'booked', 'rented' => 'bg-warning',
                'maintenance' => 'bg-info',
                default => 'bg-secondary',
            };
        @endphp

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    {{-- Status Badge (Top Right) --}}
                    <span class="badge {{ $statusClass }} position-absolute top-0 end-0 mt-3 me-3">{{ strtoupper($status) }}</span>

                    <h5 class="card-title">{{ $car->model_name }}</h5>
                    <p class="card-subtitle text-muted mb-3">{{ $car->plate_number }}</p>

                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>🗓️ Year: <strong>{{ $car->year }}</strong></span>
                        <span>🛣️ Mileage: <strong>{{ number_format($car->odometer ?? 0) }} km</strong></span>
                    </div>

                    {{-- Progress Bar (Example for Fuel/Owner/Tax Status) --}}
                    <div class="mb-3">
                        <small>Fuel Level</small>
                        <div class="progress" style="height: 5px;">
                            {{-- Assuming you have a fuel_level field (0-100) --}}
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $car->fuel_level ?? 75 }}%;"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        {{-- Tax Status (Based on logic from your show.blade.php) --}}
                        <span class="badge {{ ($car->tax_active ?? true) ? 'bg-success' : 'bg-danger' }}">
                            ROAD TAX {{ ($car->tax_active ?? true) ? 'ACTIVE' : 'EXPIRED' }}
                        </span>

                        {{-- Action Buttons --}}
                        <div class="d-flex">
                            {{-- View Details (Show) --}}
                            <a href="{{ route('staff.fleet.show', $car->fleet_id) }}" class="btn btn-sm btn-outline-primary me-2">View Details</a>
                            
                            {{-- Edit (CRUD) --}}
                            <a href="{{ route('staff.fleet.edit', $car->fleet_id) }}" class="btn btn-sm btn-outline-warning me-2">Edit</a>
                            
                            {{-- Delete (CRUD) --}}
                            <form action="{{ route('staff.fleet.destroy', $car->fleet_id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this vehicle?')" class="btn btn-sm btn-outline-danger">Del</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><p class="text-center text-muted">No vehicles found in the fleet.</p></div>
        @endforelse
    </div>
    
    {{-- Pagination (If you use pagination in controller) --}}
    <div class="mt-4">
        {{ $fleet->links() }}
    </div>

</div>
@endsection
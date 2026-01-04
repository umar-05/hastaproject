{{-- resources/views/staff/fleet/index.blade.php --}}

@extends('layouts.staff')

@section('content')
<div class="container-fluid py-4">

    {{-- Title and Management Description --}}
    <h3 class="mb-1">Fleet Management</h3>
    <p class="text-muted">Manage your rental vehicle fleet</p>

    {{-- 1. TOP SUMMARY CARDS (Horizontal Cards like Mockup) --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="fas fa-car-side text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $totalVehicles ?? 0 }}</h5>
                        <small class="text-muted">Total Vehicles</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $availableCount ?? 0 }}</h5>
                        <small class="text-muted">Available</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="fas fa-key text-warning"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $rentedCount ?? 0 }}</h5>
                        <small class="text-muted">Rented</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm d-flex align-items-center">
                <div class="d-flex align-items-center w-100">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="fas fa-tools text-info"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $maintenanceCount ?? 0 }}</h5>
                        <small class="text-muted">Maintenance</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. FILTERING + SEARCH + ADD VEHICLE SECTION --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        {{-- Filter Buttons --}}
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-dark active">All Vehicles</button>
            <button type="button" class="btn btn-outline-secondary">Available</button>
            <button type="button" class="btn btn-outline-secondary">Rented</button>
            <button type="button" class="btn btn-outline-secondary">Maintenance</button>
        </div>

        {{-- Search Bar + Add Vehicle Button --}}
        <div class="d-flex align-items-center gap-2">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search vehicles...">
            </div>
            <a href="{{ route('staff.fleet.create') }}" class="btn btn-danger">
                <i class="fas fa-plus me-1"></i> Add Vehicle
            </a>
        </div>
    </div>

    {{-- 3. VEHICLE CARD LISTING --}}
    <div class="row g-4">
        @forelse ($fleet as $car)
            @php
                $status = strtolower($car->status);
                $statusClass = match($status) {
                    'available' => 'bg-success',
                    'booked', 'rented' => 'bg-warning',
                    'maintenance' => 'bg-info',
                    default => 'bg-secondary',
                };
                $taxActive = $car->tax_active ?? true;
                $insuranceActive = $car->insurance_active ?? true; // Pastikan field ini ada di model
                $fuelLevel = $car->fuel_level ?? 75;
            @endphp

            <div class="col-xl-4 col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    {{-- Gambar Mobil --}}
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . ($car->image_path ?? 'default-car.jpg')) }}" 
                             class="card-img-top" alt="{{ $car->modelName }}" 
                             style="height: 200px; object-fit: cover;">

                        {{-- Status Badge (Top Left) --}}
                        <span class="badge {{ $statusClass }} position-absolute top-0 start-0 mt-2 ms-2">
                            {{ strtoupper($status) }}
                        </span>
                    </div>

                    <div class="card-body p-3">
                        <h5 class="card-title mb-1">{{ $car->modelName }}</h5>
                        <p class="card-subtitle text-muted mb-3">{{ $car->plateNumber }}</p>

                        {{-- Owner & Year/Mileage --}}
                        <div class="d-flex justify-content-between small mb-2">
                            <span><strong>Owner:</strong> {{ $car->owner_name ?? 'N/A' }}</span>
                            <span><strong>Year:</strong> {{ $car->year }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3">
                            <span><strong>Mileage:</strong> {{ number_format($car->odometer ?? 0) }} km</span>
                        </div>

                        {{-- Fuel Level Progress Bar --}}
                        <div class="mb-3">
                            <small>Fuel Level</small>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: {{ $fuelLevel }}%;" aria-valuenow="{{ $fuelLevel }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-end d-block">{{ $fuelLevel }}%</small>
                        </div>

                        {{-- Road Tax & Insurance Badges --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $taxActive ? 'bg-success' : 'bg-danger' }} small">
                                ROAD TAX {{ $taxActive ? 'ACTIVE' : 'EXPIRED' }}
                            </span>
                            <span class="badge {{ $insuranceActive ? 'bg-success' : 'bg-danger' }} small">
                                INSURANCE {{ $insuranceActive ? 'ACTIVE' : 'EXPIRED' }}
                            </span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-between pt-2 border-top">
                            <a href="{{ route('staff.fleet.show', $car->plateNumber) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>
                            <a href="{{ route('staff.fleet.edit', $car->plateNumber) }}" 
                               class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('staff.fleet.destroy', $car->plateNumber) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this vehicle?')" 
                                        class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash me-1"></i> Del
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-car-alt me-2"></i> No vehicles found in the fleet.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $fleet->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection

@section('styles')
<style>
    /* Custom styling for card hover effect */
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
        transition: all 0.3s ease;
    }

    /* Status badge on top left */
    .badge.position-absolute.top-0.start-0 {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }

    /* Make buttons smaller on mobile */
    @media (max-width: 768px) {
        .btn-group .btn {
            font-size: 0.85rem;
            padding: 0.3rem 0.6rem;
        }
        .card-title {
            font-size: 1rem;
        }
    }
</style>
@endsection

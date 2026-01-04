<<<<<<< Updated upstream
<<<<<<< Updated upstream
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fleet->modelName }} - Fleet Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-nav { background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .gallery-thumb {
            width: 70px;
            height: 50px;
            object-fit: cover;
            margin: 3px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .gallery-thumb.active { border-color: #dc3545; }
        .spec-btn { min-width: 120px; }
        footer { background-color: #e74c3c; color: white; }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg header-nav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/staff') }}">HASTA</a>
        <div class="d-flex align-items-center">
            <div class="navbar-nav me-3">
                <a class="nav-link" href="{{ url('/staff') }}">Dashboard</a>
                <a class="nav-link active" href="#">Fleet Details</a>
            </div>
            <a href="{{ route('logout') }}" 
               class="btn btn-danger btn-sm"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
    <h2 class="text-center mb-4">Fleet Details</h2>

    <div class="row">
        <!-- LEFT: Car Image + Gallery -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                @php
                    $photos = json_decode($fleet->photos, true) ?: [];
                    $mainImage = !empty($photos) ? $photos[0] : 'https://via.placeholder.com/400x300?text=No+Image';
                @endphp

                <img id="mainImage" src="{{ $mainImage }}" class="img-fluid rounded" alt="{{ $fleet->modelName }}">
                
                <div class="d-flex justify-content-center mt-2">
                    @foreach($photos as $index => $photo)
                        <img src="{{ $photo }}" 
                             class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                             onclick="switchImage(this, '{{ $photo }}')">
                    @endforeach

                    @if(empty($photos))
                        <img src="https://via.placeholder.com/70x50?text=No+Photo" class="gallery-thumb">
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT: Vehicle Info -->
        <div class="col-md-6">
            <div class="card p-4 h-100">
                <h4>{{ $fleet->modelName }} ({{ $fleet->year ?? 'N/A' }})</h4>
                
                <table class="table table-borderless mb-3">
                    <tr><td><strong>Plate Number:</strong></td><td>{{ $fleet->plateNumber }}</td></tr>
                    <tr><td><strong>Model:</strong></td><td>{{ $fleet->modelName }}</td></tr>
                    <tr><td><strong>Year:</strong></td><td>{{ $fleet->year }}</td></tr>
                    <tr><td><strong>Status:</strong></td>
                        <td>
                            <span class="badge bg-{{ $fleet->status === 'available' ? 'success' : ($fleet->status === 'rented' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($fleet->status ?? 'Unknown') }}
                            </span>
                        </td>
                    </tr>
                    <tr><td><strong>Owner:</strong></td><td>{{ $fleet->ownerName ?? 'Company Owned' }}</td></tr>
                    @if($fleet->matricNum)
                        <tr><td><strong>Student ID:</strong></td><td>{{ $fleet->matricNum }}</td></tr>
                    @endif
                </table>

                <!-- Road Tax -->
                <div class="bg-light p-3 rounded mb-3">
                    <div class="d-flex justify-content-between">
                        <div><i class="fas fa-road me-2"></i> Road Tax</div>
                        <div>
                            <span class="badge bg-{{ $fleet->roadtaxStat === 'active' ? 'success' : 'danger' }}">
                                {{ $fleet->roadtaxStat ? ucfirst($fleet->roadtaxStat) : 'Inactive' }}
                            </span>
                            <button class="btn btn-sm btn-outline-info ms-2">Renew</button>
                        </div>
                    </div>
                    @if($fleet->taxExpirydate)
                        <small class="text-muted">Expires: {{ \Carbon\Carbon::parse($fleet->taxExpirydate)->format('d/m/Y') }}</small>
                    @endif
                </div>

                <!-- Insurance -->
                <div class="bg-light p-3 rounded mb-3">
                    <div class="d-flex justify-content-between">
                        <div><i class="fas fa-shield-alt me-2"></i> Insurance</div>
                        <div>
                            <span class="badge bg-{{ $fleet->insuranceStat === 'active' ? 'success' : 'danger' }}">
                                {{ $fleet->insuranceStat ? ucfirst($fleet->insuranceStat) : 'Inactive' }}
                            </span>
                            <button class="btn btn-sm btn-outline-info ms-2">Renew</button>
                        </div>
                    </div>
                    @if($fleet->insuranceExpirydate)
                        <small class="text-muted">Expires: {{ \Carbon\Carbon::parse($fleet->insuranceExpirydate)->format('d/m/Y') }}</small>
                    @endif
                </div>

                <!-- Notes -->
                <div class="bg-light p-3 rounded">
                    <h6>Notes</h6>
                    <p class="mb-0">{!! nl2br(e($fleet->note ?? 'No notes available.')) !!}</p>
                </div>

                <div class="mt-3">
                    <button class="btn btn-warning w-100">Manage Info</button>
=======
=======
>>>>>>> Stashed changes
@extends('layouts.app') 

@section('content')

{{-- Import Carbon for date manipulation --}}
@php use Carbon\Carbon; @endphp

<div class="container-fluid py-4">
    {{-- Top Section: Vehicle Basic Info --}}
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3"><a href="{{ url('/vehicles.index') }}" class="text-danger text-decoration-none">&lt; Back to Fleet</a></h2>
            
            <div class="card p-4 mb-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        {{-- Uses your modelName field --}}
                        <h3>{{ $car->modelName }}</h3>
                        {{-- Uses your plateNumber field --}}
                        <p class="text-muted">{{ $car->plateNumber }}</p>
                    </div>
                    {{-- Status Badge (Dynamic) --}}
                    @php
                        $status = strtolower($car->status);
                        $statusClass = match($status) {
                            'maintenance' => 'bg-warning text-dark',
                            'booked' => 'bg-danger text-white',
                            'available' => 'bg-success text-white',
                            default => 'bg-secondary text-white',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }} p-2">{{ strtoupper($status) }}</span> 
                </div>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Layout --}}
    <div class="row">
        {{-- LEFT COLUMN: Details, Specs, Other Cars --}}
        <div class="col-lg-8">
            {{-- Vehicle Specs and Image --}}
            <div class="card mb-4 p-4 shadow-sm">
                <div class="row">
                    <div class="col-md-5">
                        <div class="bg-light p-5 rounded text-center mb-4" style="height: 250px;">
                            <p class="text-muted mt-5">Image Placeholder ({{ $car->photos ?? 'N/A' }})</p>
                        </div>
                        {{-- Technical Specification --}}
                        <h4>Technical Specification</h4>
                        <div class="d-flex justify-content-between mt-3">
                            <div class="text-center p-3 border rounded">
                                <strong>{{ $car->year }}</strong><br>Year
                            </div>
                            <div class="text-center p-3 border rounded">
                                <strong>{{ $car->color ?? 'N/A' }}</strong><br>Color
                            </div>
                            <div class="text-center p-3 border rounded">
                                <strong>{{ number_format($car->odometer ?? 0) }} km</strong><br>Odometer
                            </div>
                        </div>
                    </div>

                    {{-- Right side of the top card: Owner Info, Tax, Insurance, Documents --}}
                    <div class="col-md-7 border-start ps-4">
                        <h4>Status & Documentation</h4>
                        
                        {{-- Owner Information --}}
                        <div class="detail-group">
                            <h5 class="d-flex justify-content-between">
                                Owner Information 
                                <a href="#" class="edit-link text-danger text-decoration-none">✏️ Edit</a>
                            </h5>
                            <p>Name: <strong>{{ $car->ownerName ?? 'N/A' }}</strong></p>
                            <p>Phone: <strong>{{ $car->ownerPhone ?? 'N/A' }}</strong></p>
                            <p>Email: <strong>{{ $car->ownerEmail ?? 'N/A' }}</strong></p>
                        </div>

                        {{-- Road Tax --}}
                        <div class="detail-group">
                            @php
                                $taxExpiry = $car->taxExpirydate ? Carbon::parse($car->taxExpirydate) : null;
                                $isTaxActive = $taxExpiry && $taxExpiry->isFuture();
                                $taxStatusClass = $isTaxActive ? 'bg-success' : 'bg-danger';
                                $taxStatusText = $isTaxActive ? 'ACTIVE' : 'EXPIRED';
                            @endphp
                            <h5 class="d-flex justify-content-between">
                                Road Tax <span class="badge {{ $taxStatusClass }}">{{ $taxStatusText }}</span>
                            </h5>
                            <p>Active Date: <strong>{{ $car->taxActivedate ? Carbon::parse($car->taxActivedate)->format('d/m/Y') : 'N/A' }}</strong></p>
                            <p>Expiry Date: <strong>{{ $car->taxExpirydate ? $taxExpiry->format('d/m/Y') : 'N/A' }}</strong></p>
                        </div>
                        
                        {{-- Documents --}}
                        <div class="detail-group">
                            <h5>Documents</h5>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-info me-2">Download Grant</button>
                                <button class="btn btn-sm btn-info me-2">Download Road Tax</button>
                            </div>
                        </div>

                        {{-- Note --}}
                        <div class="detail-group">
                            <h5>Note</h5>
                            <p>{{ $car->note ?? 'No notes recorded.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Maintenance History Section --}}
            <div class="card p-4 mb-4 shadow-sm">
                <h4>Maintenance History</h4>
                @if($maintenanceHistory->isNotEmpty())
                    @foreach($maintenanceHistory as $maintenance)
                    <div class="maintenance-item d-flex justify-content-between py-2 border-bottom">
                        <div>
                            {{-- Uses your description field --}}
                            <strong>{{ $maintenance->description }}</strong>
                            <p class="text-muted mb-0 small">
                                ID: {{ $maintenance->maintenanceID }} | 
                                {{-- Uses your mDate and mTime fields --}}
                                {{ Carbon::parse($maintenance->mDate)->format('Y-m-d') }} 
                                @if($maintenance->mTime) ({{ $maintenance->mTime }}) @endif
                            </p>
                        </div>
                        <div class="text-right">
                            {{-- Uses your cost field --}}
                            <span class="font-weight-bold text-danger">RM {{ number_format($maintenance->cost, 2) }}</span>
                            <br><small class="text-muted">{{ number_format($maintenance->odometerReading ?? 0) }} km</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No maintenance history found for this vehicle.</p>
                @endif
                <div class="text-right mt-3">
                    <button class="btn btn-sm btn-outline-danger">View Full History</button>
                </div>
            </div>

            {{-- Other Cars Section --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Other Cars</h4>
                    <a href="{{ url('/vehicles') }}" class="text-danger text-decoration-none">View all</a>
                </div>
                <div class="row">
                    @foreach($otherCars as $otherCar)
                    <div class="col-md-4">
                        <div class="card p-3 car-card shadow-sm">
                            <div class="car-placeholder bg-light mb-2" style="height: 80px;"></div>
                            <h5>{{ $otherCar->modelName }}</h5>
                            <p class="text-muted">{{ $otherCar->plateNumber }}</p>
                            <div class="d-flex justify-content-between small text-muted mb-3">
                                <span>🗓️ {{ $otherCar->year }}</span>
                                <span>⛽ {{ $otherCar->fuel_level ?? 0 }}%</span>
                                <span>🛣️ {{ number_format($otherCar->odometer ?? 0) }} km</span>
                            </div>
                            <a href="{{ route('staff.fleet.show', $otherCar->plateNumber) }}" class="btn btn-sm btn-danger">View Details</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        {{-- RIGHT COLUMN: Availability and Booking History --}}
        <div class="col-lg-4">
            {{-- Availability Calendar --}}
            <div class="card p-4 mb-4 shadow-sm">
                <h4>Availability Calendar</h4>
                {{-- Legend --}}
                <div class="d-flex justify-content-around mb-3 small">
                    <span class="text-success"><span class="legend-box bg-success"></span> Available</span>
                    <span class="text-danger"><span class="legend-box bg-danger"></span> Booked</span>
                    <span class="text-warning"><span class="legend-box bg-warning"></span> Maint</span>
                </div>

                {{-- Calendar Grid (Simplified) --}}
                <div class="calendar-grid">
                    @php
                        // Current month (January 2026 for context)
                        $calendarStartDay = 4; // Jan 1, 2026 is a Thursday (4th day)
                        $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    @endphp

                    @foreach($daysOfWeek as $day)
                        <div class="day-header text-center small text-muted">{{ $day }}</div>
                    @endforeach

                    @for ($i = 0; $i < $calendarStartDay; $i++)
                        <div class="calendar-cell empty"></div>
                    @endfor

                    @for ($day = 1; $day <= 31; $day++)
                        @php
                            $status = $availabilityData['calendar'][$day] ?? 'available'; 
                            $statusClass = match($status) {
                                'available' => 'cell-available',
                                'booked' => 'cell-booked',
                                'maintenance' => 'cell-maintenance',
                                default => 'cell-default',
                            };
                        @endphp
                        <div class="calendar-cell day-{{ $day }} text-center {{ $statusClass }}">
                            {{ $day }}
                        </div>
                    @endfor
                </div>

                {{-- Availability Summary --}}
                <div class="d-flex justify-content-around text-center mt-4">
                    <div><h3 class="text-success">{{ $availabilityData['available_days'] }}</h3><p class="small text-muted">Available Days</p></div>
                    <div><h3 class="text-danger">{{ $availabilityData['booked_days'] }}</h3><p class="small text-muted">Booked Days</p></div>
                    <div><h3 class="text-warning">{{ $availabilityData['maintenance_days'] }}</h3><p class="small text-muted">Maintenance Days</p></div>
                </div>
            </div>

            {{-- Booking History --}}
            <div class="card p-4 shadow-sm">
                <h4>Booking History</h4>
                @if($bookingHistory->isNotEmpty())
                    @foreach($bookingHistory as $booking)
                    <div class="booking-item py-3 border-bottom d-flex justify-content-between">
                        <div>
                            {{-- Uses your customerName field --}}
                            <strong>{{ $booking->customerName ?? 'N/A' }}</strong>
                            <p class="text-muted mb-0 small">ID: {{ $booking->bookingID }}</p>
                            {{-- Uses your pickupDate and returnDate fields --}}
                            <p class="text-muted mb-0 small">Out: {{ Carbon::parse($booking->pickupDate)->format('d/m/Y') }}</p>
                            @php
                                $duration = Carbon::parse($booking->pickupDate)->diffInDays(Carbon::parse($booking->returnDate));
                            @endphp
                            <p class="text-muted mb-0 small">Duration: {{ $duration }} days</p>
                        </div>
                        <div class="text-right">
                            {{-- Uses your totalPrice field --}}
                            <span class="font-weight-bold text-danger">RM {{ number_format($booking->totalPrice, 2) }}</span>
                            <br>
                            @php
                                $statusClass = match(strtoupper($booking->bookingStat)) {
                                    'ONGOING' => 'bg-primary text-white',
                                    'COMPLETED' => 'bg-success text-white',
                                    default => 'bg-secondary text-white',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ strtoupper($booking->bookingStat) }}</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No recent booking history found for this vehicle.</p>
                @endif
                <div class="text-right mt-3">
                    <button class="btn btn-sm btn-outline-danger">View All Bookings</button>
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
                </div>
            </div>
        </div>
    </div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream

    <!-- Technical Specification -->
    <div class="mt-5">
        <h5>Technical Specification</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="btn btn-outline-primary spec-btn"><i class="fas fa-car me-1"></i> Plant Info</a>
            <a href="#" class="btn btn-outline-primary spec-btn"><i class="fas fa-history me-1"></i> Booking History</a>
            <a href="#" class="btn btn-outline-primary spec-btn"><i class="fas fa-user-tie me-1"></i> Ownership Info</a>
            <a href="#" class="btn btn-outline-primary spec-btn"><i class="fas fa-wrench me-1"></i> Maintenance Info</a>
            <a href="#" class="btn btn-outline-primary spec-btn"><i class="fas fa-calendar-alt me-1"></i> Calendar</a>
        </div>
    </div>

    <!-- Other Cars -->
    @if($otherFleets->count() > 0)
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h5>Other cars</h5>
            <a href="{{ url('/staff') }}" class="text-decoration-none">View All →</a>
        </div>
        <div class="row mt-3">
            @foreach($otherFleets as $car)
                @php
                    $carPhotos = json_decode($car->photos, true) ?: [];
                    $carImage = !empty($carPhotos) ? $carPhotos[0] : 'https://via.placeholder.com/300x200?text=No+Image';
                @endphp
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <img src="{{ $carImage }}" class="card-img-top" alt="{{ $car->modelName }}">
                        <div class="card-body">
                            <h6 class="card-title">{{ $car->modelName }} ({{ $car->year }})</h6>
                            <p class="text-muted small">Plate: {{ $car->plateNumber }}</p>
                            <a href="{{ route('staff.fleet.show', $car->fleet_id) }}" 
                               class="btn btn-sm" 
                               style="background:#e74c3c; color:white;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<!-- Footer -->
<footer class="py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>HASTA</h5>
                <p>Jalan Universiti, 46150<br>Bandar Baru Bangi, Selangor</p>
            </div>
            <div class="col-md-4">
                <h6>Useful Links</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">About Us</a></li>
                    <li><a href="#" class="text-white">Contact</a></li>
                    <li><a href="#" class="text-white">Gallery</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Vehicles</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Cars</a></li>
                    <li><a href="#" class="text-white">Trucks</a></li>
                </ul>
                <p class="mt-2"><i class="fas fa-phone me-2"></i> +6012-345 6789</p>
            </div>
        </div>
    </div>
</footer>

<script>
function switchImage(thumb, url) {
    document.getElementById('mainImage').src = url;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>

</body>
</html>
=======
=======
>>>>>>> Stashed changes
</div>

{{-- Essential Styling (Place this in a style tag or your main CSS file) --}}
<style>
    .detail-group { margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #eee; }
    .detail-group h5 { font-size: 1.1rem; }
    .detail-group p { margin-bottom: 0.25rem; font-size: 0.9rem; }
    .edit-link { font-size: 0.8rem; }
    .car-card { border: 1px solid #ccc; }

    /* Calendar Grid Styles */
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        text-align: center;
    }
    .day-header { font-weight: bold; }
    .calendar-cell {
        padding: 5px;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-size: 0.8rem;
    }
    /* Cell Backgrounds */
    .cell-available { background-color: #e6ffe6; color: #155724; } 
    .cell-booked { background-color: #f8d7da; color: #721c24; } 
    .cell-maintenance { background-color: #fff3cd; color: #856404; } 
    .legend-box {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }
</style>

<<<<<<< Updated upstream
@endsection
>>>>>>> Stashed changes
=======
@endsection
>>>>>>> Stashed changes

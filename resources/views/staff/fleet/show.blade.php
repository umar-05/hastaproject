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
                </div>
            </div>
        </div>
    </div>

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
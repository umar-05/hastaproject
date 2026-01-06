@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('staff.fleet.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Fleet
        </a>
    </div>

    <!-- Vehicle Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $fleet->modelName }} {{ $fleet->year }}</h1>
                <p class="text-gray-600 mt-1">{{ $fleet->plateNumber }}</p>
            </div>
            <div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $fleet->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ strtoupper($fleet->status) }}
                </span>
            </div>
        </div>

        <!-- Vehicle Image and Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left: Image -->
            <div class="bg-pink-100 rounded-lg p-8 flex items-center justify-center">
                <img src="{{ asset('images/cars/' . $vehicle['image']) }}" 
                     alt="{{ $fleet->modelName }}" 
                     class="max-w-full h-auto rounded-lg">
            </div>

            <!-- Right: Information Cards -->
            <div class="space-y-4">
                <!-- Owner Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Owner Information</h3>
                        <button class="text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                    </div>
                    @if($fleet->owner)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium">{{ $fleet->owner->ownerName }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">IC Number:</span>
                            <span class="font-medium">{{ $fleet->owner->ownerIC }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium">{{ $fleet->owner->ownerPhoneNum }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $fleet->owner->ownerEmail }}</span>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">No owner information available</p>
                    @endif
                </div>

                <!-- Road Tax Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Road Tax</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $fleet->roadtaxStat === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ strtoupper($fleet->roadtaxStat ?? 'N/A') }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Date:</span>
                            <span class="font-medium">{{ $fleet->taxActivedate ? $fleet->taxActivedate->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Expiry Date:</span>
                            <span class="font-medium">{{ $fleet->taxExpirydate ? $fleet->taxExpirydate->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Insurance Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Insurance</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $fleet->insuranceStat === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ strtoupper($fleet->insuranceStat ?? 'N/A') }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Date:</span>
                            <span class="font-medium">{{ $fleet->insuranceActivedate ? $fleet->insuranceActivedate->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Expiry Date:</span>
                            <span class="font-medium">{{ $fleet->insuranceExpirydate ? $fleet->insuranceExpirydate->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Specification -->
        <div class="mt-6 pt-6 border-t">
            <h3 class="text-lg font-semibold mb-4">Technical Specification</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $fleet->year }}</p>
                    <p class="text-gray-600 text-sm mt-1">Year</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <p class="text-3xl font-bold text-gray-900">{{ $fleet->color ?? 'N/A' }}</p>
                    <p class="text-gray-600 text-sm mt-1">Color</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability Calendar -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Availability Calendar</h2>
        <p class="text-gray-600 mb-4">{{ now()->format('F Y') }}</p>

        <div class="grid grid-cols-7 gap-2 mb-4">
            <!-- Day Headers -->
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="text-center font-semibold text-gray-700 text-sm py-2">{{ $day }}</div>
            @endforeach

            <!-- Calendar Days -->
            @php
                $startOfMonth = now()->startOfMonth();
                $startDayOfWeek = $startOfMonth->dayOfWeek;
                $daysInMonth = now()->daysInMonth;
            @endphp

            <!-- Empty cells for days before month starts -->
            @for($i = 0; $i < $startDayOfWeek; $i++)
                <div></div>
            @endfor

            <!-- Calendar days -->
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDate = now()->startOfMonth()->addDays($day - 1);
                    $dateStr = $currentDate->format('Y-m-d');
                    $status = $availabilityCalendar[$dateStr]['status'] ?? 'available';
                    
                    $bgColor = 'bg-green-100';
                    if($status === 'booked') $bgColor = 'bg-red-100';
                    if($status === 'maintenance') $bgColor = 'bg-yellow-100';
                @endphp
                <div class="text-center p-3 rounded-lg {{ $bgColor }} hover:opacity-80 cursor-pointer transition">
                    <span class="text-sm font-medium">{{ $day }}</span>
                </div>
            @endfor
        </div>

        <!-- Legend -->
        <div class="flex justify-center gap-6 mt-4 text-sm">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-100 rounded mr-2"></div>
                <span>Available</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-100 rounded mr-2"></div>
                <span>Booked</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-100 rounded mr-2"></div>
                <span>Maintenance</span>
            </div>
        </div>
    </div>

    <!-- Booking History -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Booking History</h2>
        
        @if($bookings->count() > 0)
        <div class="space-y-3">
            @foreach($bookings as $booking)
            <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50 transition">
                <div>
                    <p class="font-semibold text-gray-900">{{ $booking->customer->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->customer->matricNum ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        Date: {{ $booking->pickupDate ? $booking->pickupDate->format('Y-m-d') : 'N/A' }} - 
                        {{ $booking->returnDate ? $booking->returnDate->format('Y-m-d') : 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-500">Duration: {{ $booking->rental_days ?? 0 }} days</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-lg text-gray-900">RM {{ number_format($booking->totalPrice, 2) }}</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mt-2
                        {{ $booking->bookingStat === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ strtoupper($booking->bookingStat) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">No booking history available</p>
        @endif
    </div>

    <!-- Maintenance History -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Maintenance History</h2>
        
        @if($maintenances->count() > 0)
        <div class="space-y-3">
            @foreach($maintenances as $maintenance)
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $maintenance->description }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            ID: {{ $maintenance->maintenanceID }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Date: {{ $maintenance->mDate ? $maintenance->mDate->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg text-red-600">RM {{ number_format($maintenance->cost, 2) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">No maintenance history available</p>
        @endif
    </div>

    <!-- Note Section -->
    @if($fleet->note)
    <div class="bg-blue-50 rounded-lg p-6 mt-6">
        <h3 class="font-semibold text-gray-900 mb-2">Note</h3>
        <p class="text-gray-700">{{ $fleet->note }}</p>
    </div>
    @endif
</div>
@endsection
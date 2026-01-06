{{-- resources/views/staff/fleet/show.blade.php --}}
@extends('layouts.staff')

@section('content')
@php
    use Carbon\Carbon;

    // --- 1. Dynamic Image Logic ---
    $vehicleImage = 'default-car.png';
    if (!empty($fleet->photos)) {
        $vehicleImage = $fleet->photos;
    } else {
        $model = strtolower($fleet->modelName);
        $year = $fleet->year;
        if (str_contains($model, 'axia')) { $vehicleImage = ($year >= 2023) ? 'axia-2024.png' : 'axia-2018.png'; }
        elseif (str_contains($model, 'bezza')) { $vehicleImage = 'bezza-2018.png'; }
        elseif (str_contains($model, 'myvi')) { $vehicleImage = ($year >= 2020) ? 'myvi-2020.png' : 'myvi-2015.png'; }
        elseif (str_contains($model, 'saga')) { $vehicleImage = 'saga-2017.png'; }
        elseif (str_contains($model, 'alza')) { $vehicleImage = 'alza-2019.png'; }
        elseif (str_contains($model, 'aruz')) { $vehicleImage = 'aruz-2020.png'; }
        elseif (str_contains($model, 'vellfire')) { $vehicleImage = 'vellfire-2020.png'; }
        elseif (str_contains($model, 'x50')) { $vehicleImage = 'x50-2024.png'; }
        elseif (str_contains($model, 'y15')) { $vehicleImage = 'y15zr-2023.png'; }
    }

    // --- 2. Status Colors ---
    $status = strtolower($fleet->status);
    $statusStyles = match($status) {
        'available' => 'bg-green-100 text-green-700 border-green-200',
        'booked', 'rented' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'maintenance' => 'bg-purple-100 text-purple-700 border-purple-200',
        default => 'bg-gray-100 text-gray-600 border-gray-200',
    };

    // --- 3. Fuel Logic ---
    $fuelLevel = $fleet->fuel_level ?? 75;
    $fuelColor = $fuelLevel > 50 ? 'bg-green-500' : ($fuelLevel > 20 ? 'bg-yellow-500' : 'bg-red-500');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in-down">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <a href="{{ route('staff.fleet.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Fleet
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900">{{ $fleet->modelName }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusStyles }}">
                    {{ $status }}
                </span>
            </div>
            <p class="text-gray-500 font-mono text-sm mt-1">{{ $fleet->plateNumber }}</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('staff.fleet.edit', $fleet->plateNumber) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
            <form action="{{ route('staff.fleet.destroy', $fleet->plateNumber) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- LEFT COLUMN: Vehicle Info --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Image & Key Specs Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="relative h-64 bg-gray-50 flex items-center justify-center p-6">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/5"></div>
                    <img src="{{ asset('images/' . $vehicleImage) }}" alt="{{ $fleet->modelName }}" class="max-h-full max-w-full object-contain drop-shadow-xl transform hover:scale-105 transition-transform duration-500">
                </div>
                
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Technical Specifications</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-3 bg-gray-50 rounded-xl text-center border border-gray-100">
                            <span class="block text-xs text-gray-500 uppercase tracking-wide">Year</span>
                            <span class="block text-lg font-bold text-gray-800">{{ $fleet->year }}</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl text-center border border-gray-100">
                            <span class="block text-xs text-gray-500 uppercase tracking-wide">Color</span>
                            <span class="block text-lg font-bold text-gray-800">{{ $fleet->color ?? 'N/A' }}</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl text-center border border-gray-100">
                            <span class="block text-xs text-gray-500 uppercase tracking-wide">Mileage</span>
                            <span class="block text-lg font-bold text-gray-800">{{ number_format($fleet->odometer ?? 0) }} km</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl text-center border border-gray-100">
                            <span class="block text-xs text-gray-500 uppercase tracking-wide">Fuel</span>
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2 mb-1">
                                <div class="{{ $fuelColor }} h-1.5 rounded-full" style="width: {{ $fuelLevel }}%"></div>
                            </div>
                            <span class="block text-xs font-bold">{{ $fuelLevel }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details Tabs / Sections --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Owner Info --}}
                    <div>
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Ownership</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Owner Name</p>
                                <p class="text-sm font-medium text-gray-900">{{ $fleet->ownerName ?? 'Company Owned' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Contact Number</p>
                                <p class="text-sm font-medium text-gray-900">{{ $fleet->ownerPhone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ $fleet->ownerEmail ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Documentation --}}
                    <div>
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Documentation</h4>
                        
                        {{-- Road Tax --}}
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Road Tax</p>
                                <p class="text-xs text-gray-500">Exp: {{ $fleet->taxExpirydate ? Carbon::parse($fleet->taxExpirydate)->format('d M Y') : 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-[10px] font-bold rounded {{ ($fleet->taxExpirydate && Carbon::parse($fleet->taxExpirydate)->isFuture()) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ($fleet->taxExpirydate && Carbon::parse($fleet->taxExpirydate)->isFuture()) ? 'ACTIVE' : 'EXPIRED' }}
                            </span>
                        </div>

                        {{-- Insurance --}}
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Insurance</p>
                                <p class="text-xs text-gray-500">Exp: {{ $fleet->insuranceExpirydate ? Carbon::parse($fleet->insuranceExpirydate)->format('d M Y') : 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-[10px] font-bold rounded {{ ($fleet->insuranceExpirydate && Carbon::parse($fleet->insuranceExpirydate)->isFuture()) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ($fleet->insuranceExpirydate && Carbon::parse($fleet->insuranceExpirydate)->isFuture()) ? 'ACTIVE' : 'EXPIRED' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mt-8">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Notes</h4>
                    <div class="bg-yellow-50 border border-yellow-100 p-4 rounded-xl text-sm text-yellow-800">
                        {!! nl2br(e($fleet->note ?? 'No specific notes recorded for this vehicle.')) !!}
                    </div>
                </div>
            </div>

            {{-- Maintenance History --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Maintenance History</h3>
                    <button class="text-red-600 text-sm font-medium hover:underline">Add Record</button>
                </div>

                @if($fleet->maintenance && $fleet->maintenance->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($fleet->maintenance->take(5) as $record)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                        <i class="fas fa-wrench text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $record->description }}</p>
                                        <p class="text-xs text-gray-500">{{ Carbon::parse($record->mDate)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-red-600">RM {{ number_format($record->cost, 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($record->odometerReading ?? 0) }} km</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm">No maintenance records found.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN: Bookings & Other Cars --}}
        <div class="space-y-6">
            
            {{-- Upcoming/Recent Bookings --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Bookings</h3>
                
                @if($fleet->bookings && $fleet->bookings->isNotEmpty())
                    <div class="space-y-4 relative">
                        {{-- Vertical Line --}}
                        <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-100"></div>

                        @foreach($fleet->bookings->sortByDesc('created_at')->take(5) as $booking)
                            <div class="relative pl-10">
                                {{-- Timeline Dot --}}
                                <div class="absolute left-[11px] top-1.5 w-2.5 h-2.5 rounded-full {{ $booking->bookingStat == 'completed' ? 'bg-green-400' : 'bg-blue-400' }} border-2 border-white ring-1 ring-gray-100"></div>
                                
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $booking->customerName ?? 'Customer' }}</p>
                                            <p class="text-xs text-gray-500">ID: #{{ $booking->bookingID }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-white border border-gray-200 uppercase">{{ $booking->bookingStat }}</span>
                                    </div>
                                    <div class="mt-2 flex justify-between items-end">
                                        <div class="text-xs text-gray-500">
                                            <p>{{ Carbon::parse($booking->pickupDate)->format('d M') }} - {{ Carbon::parse($booking->returnDate)->format('d M') }}</p>
                                        </div>
                                        <p class="text-sm font-bold text-red-600">RM{{ number_format($booking->totalPrice, 0) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <p class="text-gray-400 text-sm">No booking history yet.</p>
                    </div>
                @endif
            </div>

            {{-- Other Vehicles --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Other Vehicles</h3>
                    <a href="{{ route('staff.fleet.index') }}" class="text-xs font-bold text-red-600 hover:underline">View All</a>
                </div>

                <div class="space-y-3">
                    @foreach($otherFleets as $other)
                        @php
                            // Mini Image Logic for list
                            $otherImage = 'default-car.png';
                            if (!empty($other->photos)) {
                                $otherImage = $other->photos;
                            } else {
                                $m = strtolower($other->modelName);
                                if (str_contains($m, 'axia')) $otherImage = 'axia-2024.png';
                                elseif (str_contains($m, 'bezza')) $otherImage = 'bezza-2018.png';
                                elseif (str_contains($m, 'myvi')) $otherImage = 'myvi-2020.png';
                                
                            }
                        @endphp
                        <a href="{{ route('staff.fleet.show', $other->plateNumber) }}" class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors group">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('images/' . $otherImage) }}" class="w-full h-full object-contain p-1 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $other->modelName }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $other->plateNumber }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .animate-fade-in-down { animation: fadeInDown 0.5s ease-out forwards; }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
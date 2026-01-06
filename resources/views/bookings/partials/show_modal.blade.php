{{-- resources/views/bookings/partials/show_modal.blade.php --}}
@php
    $vehicleImage = 'default-car.png';
    $vehicleName = 'Vehicle';
    $vehicleType = 'Car';
    
    if ($booking->fleet) {
        $fleet = $booking->fleet;
        $vehicleName = $fleet->modelName;
        $modelName = strtolower($fleet->modelName);
        $year = $fleet->year ?? 0;
        
        if (strpos($modelName, 'axia') !== false) {
            $vehicleImage = $year == 2024 ? 'axia-2024.png' : 'axia-2018.png';
            $vehicleType = 'Hatchback';
        } elseif (strpos($modelName, 'bezza') !== false) {
            $vehicleImage = 'bezza-2018.png';
            $vehicleType = 'Sedan';
        } elseif (strpos($modelName, 'myvi') !== false) {
            $vehicleImage = $year >= 2020 ? 'myvi-2020.png' : 'myvi-2015.png';
            $vehicleType = 'Hatchback';
        } elseif (strpos($modelName, 'saga') !== false) {
            $vehicleImage = 'saga-2017.png';
            $vehicleType = 'Sedan';
        } elseif (strpos($modelName, 'alza') !== false) {
            $vehicleImage = 'alza-2019.png';
            $vehicleType = 'MPV';
        } elseif (strpos($modelName, 'aruz') !== false) {
            $vehicleImage = 'aruz-2020.png';
            $vehicleType = 'SUV';
        } elseif (strpos($modelName, 'vellfire') !== false) {
            $vehicleImage = 'vellfire-2020.png';
            $vehicleType = 'MPV';
        } elseif (strpos($modelName, 'x50') !== false) {
            $vehicleImage = 'x50-2024.png';
            $vehicleType = 'SUV';
        } elseif (strpos($modelName, 'y15') !== false) {
            $vehicleImage = 'y15zr-2023.png';
            $vehicleType = 'Motorcycle';
        }
    }

    // Determine Status Colors
    $statusColor = match($booking->bookingStat) {
        'confirmed' => 'green',
        'pending' => 'yellow',
        'completed' => 'blue',
        'cancelled' => 'red',
        default => 'gray',
    };
@endphp

<div class="space-y-8 font-sans text-gray-800 animate-fadeIn">

    {{-- 1. HERO HEADER --}}
    <div class="flex flex-col md:flex-row justify-between md:items-center bg-gradient-to-r from-gray-50 to-white px-8 py-6 rounded-3xl border border-gray-100 shadow-sm gap-4 transition-all hover:shadow-md">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="h-2 w-2 rounded-full bg-red-600 animate-ping"></span>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Booking ID</p>
            </div>
            <h2 class="text-4xl font-black text-gray-900 tracking-tighter font-mono group-hover:text-red-600 transition-colors duration-300">#{{ $booking->bookingID }}</h2>
        </div>
        <div class="flex items-center gap-4">
             <span class="px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm border flex items-center gap-2 transition-all duration-300 transform hover:scale-105
                bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 border-{{ $statusColor }}-200">
                <span class="w-2 h-2 rounded-full bg-{{ $statusColor }}-500 animate-pulse"></span>
                {{ ucfirst($booking->bookingStat) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        {{-- 2. VEHICLE CARD (Spans 7 columns) --}}
        <div class="xl:col-span-7 bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-100/50 relative overflow-hidden group flex flex-col justify-between transition-all duration-500 hover:shadow-2xl">
             <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-bl from-red-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform duration-700 ease-in-out"></div>
            
            <div class="relative z-10 flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Vehicle Selected</h4>
                    <h3 class="text-3xl font-black text-gray-900 leading-tight group-hover:text-red-600 transition-colors duration-300">{{ $vehicleName }}</h3>
                    <p class="text-sm font-semibold text-gray-500">{{ $vehicleType }}</p>
                </div>
                <div class="bg-gray-900 text-white px-4 py-2 rounded-lg border border-gray-700 shadow-lg transform group-hover:rotate-1 transition-transform duration-300">
                    <span class="font-mono text-lg font-bold tracking-widest">{{ $booking->fleet->plateNumber ?? $booking->plateNumber }}</span>
                </div>
            </div>

            <div class="relative z-10 flex-1 flex items-center justify-center py-4">
                <img src="{{ asset('images/' . $vehicleImage) }}" 
                     alt="{{ $vehicleName }}" 
                     class="w-full max-h-60 object-contain drop-shadow-2xl transform group-hover:scale-110 group-hover:-rotate-2 transition duration-500 ease-out">
            </div>
        </div>

        {{-- 3. RIGHT COLUMN: Renter & Timeline (Spans 5 columns) --}}
        <div class="xl:col-span-5 flex flex-col gap-6">
            
            {{-- Renter Card --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-lg shadow-gray-100/50 flex flex-col justify-center h-full transition-all duration-300 hover:border-gray-200 hover:-translate-y-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Primary Renter
                </p>
                <div class="border-l-4 border-red-500 pl-4 py-1 transition-all duration-300 group-hover:border-red-600">
                    <p class="text-2xl font-black text-gray-900 leading-none mb-1.5">{{ $booking->customer->name ?? $booking->matricNum }}</p>
                    <p class="text-sm font-bold text-gray-500 font-mono bg-gray-50 inline-block px-2 py-1 rounded-md border border-gray-200">
                        ID: {{ $booking->matricNum }}
                    </p>
                </div>
            </div>

            {{-- Timeline Card --}}
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200 flex-1 flex flex-col justify-center relative overflow-hidden transition-all duration-300 hover:bg-white hover:shadow-md">
                {{-- Connecting Line --}}
                <div class="absolute left-1/2 top-10 bottom-10 w-0.5 bg-gray-200 -translate-x-1/2"></div>
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-white border border-gray-200 px-3 py-1 rounded-full z-10 text-[10px] font-bold text-gray-400 uppercase shadow-sm">
                    {{ $days ?? 1 }} Days
                </div>

                <div class="relative z-10 flex justify-between gap-4 h-full">
                    {{-- Pickup --}}
                    <div class="w-1/2 pr-6 text-right flex flex-col justify-center group/pickup">
                        <p class="text-xs font-bold text-green-600 uppercase mb-1 transition-colors group-hover/pickup:text-green-700">Pickup</p>
                        <p class="text-xl font-bold text-gray-900 leading-none group-hover/pickup:scale-105 transition-transform origin-right">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M') }}</p>
                        <p class="text-sm text-gray-500 mb-2">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('h:i A') }}</p>
                        <div class="inline-flex justify-end">
                            <span class="text-[10px] font-bold bg-white border border-gray-200 px-2 py-1 rounded text-gray-500 line-clamp-1 group-hover/pickup:border-green-200 transition-colors">
                                {{ $booking->pickupLoc }}
                            </span>
                        </div>
                    </div>

                    {{-- Return --}}
                    <div class="w-1/2 pl-6 text-left flex flex-col justify-center group/return">
                        <p class="text-xs font-bold text-red-600 uppercase mb-1 transition-colors group-hover/return:text-red-700">Return</p>
                        <p class="text-xl font-bold text-gray-900 leading-none group-hover/return:scale-105 transition-transform origin-left">{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M') }}</p>
                        <p class="text-sm text-gray-500 mb-2">{{ \Carbon\Carbon::parse($booking->returnDate)->format('h:i A') }}</p>
                        <div class="inline-flex justify-start">
                            <span class="text-[10px] font-bold bg-white border border-gray-200 px-2 py-1 rounded text-gray-500 line-clamp-1 group-hover/return:border-red-200 transition-colors">
                                {{ $booking->returnLoc }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. FINANCIAL FOOTER --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-end">
        {{-- Breakdown --}}
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-lg">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Payment Summary</h4>
            <div class="space-y-3">
                <div class="flex justify-between text-sm group/item hover:bg-gray-50 p-1 rounded transition-colors">
                    <span class="text-gray-500">Base Price ({{ $days }} days)</span>
                    <span class="font-bold text-gray-900 font-mono">RM {{ number_format($basePrice ?? 0, 2) }}</span>
                </div>
                @if(($booking->deposit ?? 0) > 0)
                <div class="flex justify-between text-sm group/item hover:bg-gray-50 p-1 rounded transition-colors">
                    <span class="text-gray-500">Security Deposit</span>
                    <span class="font-bold text-gray-900 font-mono">RM {{ number_format($booking->deposit, 2) }}</span>
                </div>
                @endif
                @if(($booking->discount ?? 0) > 0)
                <div class="flex justify-between text-sm text-green-600 group/item hover:bg-green-50 p-1 rounded transition-colors">
                    <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> Discount</span>
                    <span class="font-bold font-mono">- RM {{ number_format($booking->discount, 2) }}</span>
                </div>
                @endif
                <div class="border-t border-dashed border-gray-200 pt-3 flex justify-between items-center mt-2">
                    <span class="font-bold text-gray-900">Total Paid</span>
                    <span class="text-2xl font-black text-red-600 font-mono transform hover:scale-105 transition-transform origin-right">RM {{ number_format($booking->totalPrice, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-3">
             {{-- Approve Logic --}}
            @if($booking->bookingStat === 'pending')
                <form action="{{ route('staff.fleet.bookings.approve', $booking->bookingID) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Confirm approval?')" class="w-full px-6 py-4 text-base font-bold text-white bg-gradient-to-r from-green-500 to-green-600 rounded-xl hover:from-green-600 hover:to-green-700 shadow-lg shadow-green-200/50 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl flex items-center justify-center gap-2 group">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Approve Booking
                    </button>
                </form>
            @endif

            <div class="flex gap-3">
                <a href="{{ route('bookings.show', $booking->bookingID) }}" target="_blank" class="flex-1 px-6 py-3.5 text-sm font-bold text-gray-700 bg-white border-2 border-gray-200 rounded-xl hover:border-gray-400 hover:bg-gray-50 hover:text-gray-900 transition-all duration-300 transform hover:-translate-y-0.5 text-center shadow-sm hover:shadow-md">
                    Full Receipt
                </a>
                
                @if($booking->bookingStat !== 'cancelled' && $booking->bookingStat !== 'completed')
                    <form action="{{ route('staff.fleet.bookings.cancel', $booking->bookingID) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" onclick="return confirm('Cancel this booking?')" class="w-full px-6 py-3.5 text-sm font-bold text-red-600 bg-red-50 border-2 border-transparent rounded-xl hover:bg-red-100 hover:border-red-200 transition-all duration-300 transform hover:-translate-y-0.5 text-center shadow-sm hover:shadow-md">
                            Cancel
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- resources/views/bookings/partials/show_modal.blade.php --}}
<div class="space-y-6 font-sans">
    {{-- 1. HEADER: Customer & Vehicle --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
        {{-- Customer Info --}}
        <div>
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Customer</h4>
            <div class="flex items-center">
                <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-xs mr-2">
                    {{ substr($booking->customer->name ?? $booking->matricNum, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 leading-tight">{{ $booking->customer->name ?? $booking->matricNum }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->matricNum }}</p>
                </div>
            </div>
        </div>

        {{-- Vehicle Info --}}
        <div>
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Vehicle</h4>
            <div>
                <p class="text-sm font-bold text-gray-900 leading-tight">{{ $booking->fleet->modelName ?? 'Unknown Model' }}</p>
                <div class="flex items-center mt-1">
                    <span class="bg-gray-200 text-gray-700 text-[10px] font-mono px-1.5 py-0.5 rounded mr-2">
                        {{ $booking->fleet->plateNumber ?? $booking->plateNumber }}
                    </span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold
                        {{ $booking->bookingStat === 'confirmed' ? 'bg-green-100 text-green-700' : 
                          ($booking->bookingStat === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                          ($booking->bookingStat === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700')) }}">
                        {{ ucfirst($booking->bookingStat) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. TIMELINE --}}
    <div class="relative py-2">
        <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-200"></div>
        
        {{-- Pickup --}}
        <div class="relative flex items-start mb-6">
            <div class="absolute left-2.5 -ml-1 mt-1.5 h-3 w-3 rounded-full border-2 border-white bg-green-500 shadow-sm z-10"></div>
            <div class="ml-8 w-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase">Pickup</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('h:i A') }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-1 bg-gray-50 p-2 rounded border border-gray-100">
                    <span class="font-semibold">Loc:</span> {{ $booking->pickupLoc }}
                </p>
            </div>
        </div>

        {{-- Return --}}
        <div class="relative flex items-start">
            <div class="absolute left-2.5 -ml-1 mt-1.5 h-3 w-3 rounded-full border-2 border-white bg-red-500 shadow-sm z-10"></div>
            <div class="ml-8 w-full">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase">Return</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->returnDate)->format('h:i A') }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-1 bg-gray-50 p-2 rounded border border-gray-100">
                    <span class="font-semibold">Loc:</span> {{ $booking->returnLoc }}
                </p>
            </div>
        </div>
    </div>

    {{-- 3. FINANCIALS --}}
    <div class="border-t border-gray-100 pt-4">
        <div class="flex justify-between items-center mb-1">
            <span class="text-xs text-gray-500">Rental Duration</span>
            <span class="text-xs font-medium">{{ $days ?? 1 }} Days</span>
        </div>
        <div class="flex justify-between items-center mb-1">
            <span class="text-xs text-gray-500">Base Price</span>
            <span class="text-xs font-medium">RM {{ number_format($basePrice ?? 0, 2) }}</span>
        </div>
        @if(($booking->deposit ?? 0) > 0)
        <div class="flex justify-between items-center mb-1">
            <span class="text-xs text-gray-500">Deposit</span>
            <span class="text-xs font-medium text-gray-900">RM {{ number_format($booking->deposit, 2) }}</span>
        </div>
        @endif
        <div class="flex justify-between items-center mt-2 pt-2 border-t border-dashed border-gray-200">
            <span class="text-sm font-bold text-gray-800">Total Paid</span>
            <span class="text-lg font-bold text-red-600">RM {{ number_format($booking->totalPrice, 2) }}</span>
        </div>
    </div>

    {{-- 4. FOOTER ACTIONS --}}
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
        {{-- View Full Page Button --}}
        <a href="{{ route('bookings.show', $booking->bookingID) }}" target="_blank" class="px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            Full Details
        </a>

        {{-- Approve Button (Only if Pending) --}}
        @if($booking->bookingStat === 'pending')
            <form action="{{ route('staff.fleet.bookings.approve', $booking->bookingID) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Confirm approval?')" class="px-3 py-2 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 shadow-sm">
                    Approve Booking
                </button>
            </form>
        @endif
        
        {{-- Cancel Button --}}
        @if($booking->bookingStat !== 'cancelled' && $booking->bookingStat !== 'completed')
            <form action="{{ route('staff.fleet.bookings.cancel', $booking->bookingID) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Cancel this booking?')" class="px-3 py-2 text-xs font-medium text-red-600 bg-red-50 border border-transparent rounded-md hover:bg-red-100">
                    Cancel
                </button>
            </form>
        @endif
    </div>
</div>
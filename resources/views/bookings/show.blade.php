<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>

    <main class="max-w-7xl mx-auto px-8 py-12">

        {{-- Success Message Alert --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-green-700 font-bold">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Error Message Alert --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-700 font-bold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Main Content Card --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            {{-- Header Bar --}}
            <div class="bg-red-700 text-white px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-1">Booking Details</h1>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-bold shadow-sm bg-white
                        @if($booking->bookingStat === 'confirmed' || $booking->bookingStat === 'approved') text-green-700
                        @elseif($booking->bookingStat === 'pending') text-yellow-700
                        @elseif($booking->bookingStat === 'completed') text-blue-700
                        @else text-red-700
                        @endif">
                        {{ ucfirst($booking->bookingStat) }}
                    </span>
                </div>
            </div>

            <div class="p-8">
                @php
                    $vehicleImage = 'default-car.png';
                    $vehicleName = 'Vehicle';
                    $vehicleType = 'Car';
                    
                    if ($booking->fleet) {
                        $fleet = $booking->fleet;
                        $vehicleName = $fleet->modelName;
                        
                        // CHANGE: Use photo1
                        $vehicleImage = $fleet->photo1;
                    }
                @endphp

                {{-- Vehicle Info Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <div class="bg-gray-50 rounded-xl p-8 flex items-center justify-center border border-gray-100">
                        <img src="{{ asset('images/' . $vehicleImage) }}" alt="{{ $vehicleName }}" class="w-full max-h-72 object-contain hover:scale-105 transition duration-300">
                    </div>

                    <div class="flex flex-col justify-center">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $vehicleName }}</h2>
                        <p class="text-gray-500 text-lg mb-8">{{ $vehicleType }}</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Transmission</p>
                                <p class="font-bold text-gray-700">Automatic</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Fuel Type</p>
                                <p class="font-bold text-gray-700">RON 95</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Seats</p>
                                <p class="font-bold text-gray-700">5 Passengers</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Air Cond</p>
                                <p class="font-bold text-gray-700">Available</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-8 mt-8">
                    <h3 class="text-2xl font-bold mb-6">Pricing Breakdown</h3>
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Base Price</span>
                            <span class="font-semibold">RM{{ number_format($basePrice ?? 0, 2) }}</span>
                        </div>
                        @if($booking->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span class="font-semibold">-RM{{ number_format($booking->discount, 2) }}</span>
                        </div>
                        @endif
                        @if($booking->deposit > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Deposit</span>
                            <span class="font-semibold">RM{{ number_format($booking->deposit, 2) }}</span>
                        </div>
                        @endif
                        <div class="border-t pt-4 flex justify-between items-center">
                            <span class="text-lg font-bold">Total Amount</span>
                            <span class="text-2xl font-bold text-red-600">RM{{ number_format(((float)($booking->totalPrice ?? 0)), 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Payment Status</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if(strtolower($payment->paymentStatus) === 'paid') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($payment->paymentStatus ?? 'Pending') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- --- INSPECTION FORMS SECTION --- --}}
                <div class="border-t pt-8 mt-8">
                    <h3 class="text-2xl font-bold mb-6">Vehicle Inspection Forms</h3>
                    
                    @php
                        $isPickupDone = !empty($booking->pickupForm);
                        $isReturnDone = !empty($booking->returnForm);
                    @endphp

                    {{-- CASE 1: Booking is Pending --}}
                    @if($booking->bookingStat === 'pending')
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Inspection forms will be available once the booking is <strong>Approved</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>

                    {{-- CASE 2: Booking is Cancelled --}}
                    @elseif($booking->bookingStat === 'cancelled')
                        <div class="bg-gray-100 border-l-4 border-gray-400 p-4 rounded-r-lg">
                            <p class="text-sm text-gray-500 italic">This booking has been cancelled.</p>
                        </div>

                    {{-- CASE 3: Confirmed or Completed (Show Forms) --}}
                    @elseif($booking->bookingStat === 'approved' || $booking->bookingStat === 'confirmed' || $booking->bookingStat === 'completed' || $booking->bookingStat === 'active')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Pickup Inspection Card --}}
                            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-4">Pickup Inspection</h4>
                                    
                                    <div class="h-32 bg-white border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center mb-6">
                                        @if($isPickupDone)
                                            <div class="text-center">
                                                <svg class="w-10 h-10 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <p class="text-green-600 font-bold text-sm">Form Completed</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M Y') }}</p>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <p class="text-gray-400 text-sm">No form submitted</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <a href="{{ route('bookings.pickup-form', $booking->bookingID) }}" 
                                   class="block w-full border text-center font-bold py-3 rounded-xl transition
                                   @if($isPickupDone) border-gray-300 bg-white text-gray-700 hover:bg-gray-50 
                                   @else bg-gray-900 text-white hover:bg-gray-800 shadow-md 
                                   @endif">
                                    {{ $isPickupDone ? 'View Pickup Details' : 'Submit Pickup Form' }}
                                </a>
                            </div>

                            {{-- Return Inspection Card --}}
                            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-8 flex flex-col justify-between opacity-{{ $isPickupDone ? '100' : '50' }}">
                                <div>
                                    <h4 class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-4">Return Inspection</h4>
                                    
                                    <div class="h-32 bg-white border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center mb-6">
                                        @if($isReturnDone)
                                            <div class="text-center">
                                                <svg class="w-10 h-10 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <p class="text-green-600 font-bold text-sm">Form Completed</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M Y') }}</p>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <p class="text-gray-400 text-sm">No form submitted</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Logic: Disable Return button if Pickup is not done yet --}}
                                @if($isPickupDone && $booking->returnDate->isFuture()) 
                                    <button disabled class="block w-full border border-gray-200 bg-gray-100 text-gray-400 font-bold py-3 rounded-xl cursor-not-allowed">
                                        Available on {{ $booking->returnDate->format('d M') }}
                                    </button>
                                @elseif($isPickupDone && ($booking->returnDate->isToday() || $booking->returnDate->isPast()))
                                    <a href="{{ route('bookings.return-form', $booking->bookingID) }}" 
                                       class="block w-full border text-center font-bold py-3 rounded-xl transition
                                       @if($isReturnDone) border-gray-300 bg-white text-gray-700 hover:bg-gray-50 
                                       @else bg-red-600 text-white hover:bg-red-700 shadow-md 
                                       @endif"> 
                                        {{ $isReturnDone ? 'View Return Details' : 'Submit Return Form' }}</a>
                                @else
                                    <button disabled class="block w-full border border-gray-200 bg-gray-100 text-gray-400 font-bold py-3 rounded-xl cursor-not-allowed">
                                        Complete Pickup First
                                    </button>
                                @endif
                            </div>

                        </div>
                    @endif
                </div>

                @if($booking->notes)
                <div class="border-t pt-8 mt-8">
                    <h3 class="text-2xl font-bold mb-4">Notes</h3>
                    <p class="text-gray-600 bg-gray-50 p-4 rounded-lg">{{ $booking->notes }}</p>
                </div>
                @endif

                {{-- Actions Footer --}}
                <div class="border-t border-gray-200 pt-10 mt-10 flex flex-wrap justify-between items-center gap-4">
                    <a href="{{ route('bookings.index') }}" class="text-gray-600 font-medium hover:text-gray-900 transition flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Back to Bookings
                    </a>

                    @if($booking->bookingStat !== 'completed' && $booking->bookingStat !== 'cancelled' && !$isPickupDone && !$isReturnDone )
                        <form action="{{ route('bookings.cancel', $booking->bookingID) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                            @csrf
                            <button type="submit" class="bg-white text-red-600 border border-red-200 font-bold py-2.5 px-6 rounded-lg hover:bg-red-50 hover:border-red-300 transition shadow-sm uppercase text-xs tracking-widest flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Cancel Booking
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </main>
</x-app-layout>
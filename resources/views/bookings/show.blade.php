<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigation Back --}}
            <div class="mb-6">
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-red-600 hover:text-red-800 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to My Bookings
                </a>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-green-700 font-bold">{{ session('success') }}</p>
                </div>
            @endif

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
                            <p class="text-red-100 opacity-90">Booking ID: <span class="font-mono font-bold">#{{ $booking->bookingID }}</span></p>
                        </div>
                        <span class="px-4 py-2 rounded-full text-sm font-bold shadow-sm bg-white
                            @if($booking->bookingStat === 'confirmed') text-green-700
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

                    <div class="border-t border-gray-200 my-8"></div>

                    {{-- Booking Schedule Section --}}
                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Booking Schedule
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Pickup --}}
                            <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm relative overflow-hidden group hover:border-red-200 transition">
                                <div class="absolute top-0 left-0 w-1 h-full bg-green-500"></div>
                                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-3">Pick Up</p>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-xl text-gray-900">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M Y') }}</p>
                                        <p class="text-gray-500 font-medium">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('h:i A') }}</p>
                                    </div>
                                    <div class="bg-green-50 p-2 rounded-lg text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                                    <p class="text-sm text-gray-600 flex items-start">
                                        <svg class="w-4 h-4 mr-1.5 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $booking->pickupLoc }}
                                    </p>
                                </div>
                            </div>

                            {{-- Return --}}
                            <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm relative overflow-hidden group hover:border-red-200 transition">
                                <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-3">Return</p>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-xl text-gray-900">{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M Y') }}</p>
                                        <p class="text-gray-500 font-medium">{{ \Carbon\Carbon::parse($booking->returnDate)->format('h:i A') }}</p>
                                    </div>
                                    <div class="bg-red-50 p-2 rounded-lg text-red-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                                    <p class="text-sm text-gray-600 flex items-start">
                                        <svg class="w-4 h-4 mr-1.5 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $booking->returnLoc }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing Section --}}
                    <div class="bg-gray-50 rounded-xl p-6 mb-10 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Payment Breakdown
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>Base Rental Price</span>
                                <span class="font-medium font-mono text-gray-900">RM {{ number_format($basePrice, 2) }}</span>
                            </div>

                            @if($booking->discount > 0)
                                <div class="flex justify-between items-center text-green-600 bg-green-50 p-2 rounded-lg -mx-2">
                                    <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> Discount Applied</span>
                                    <span class="font-medium font-mono">- RM {{ number_format($booking->discount, 2) }}</span>
                                </div>
                            @endif

                            @if($booking->deposit > 0)
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Security Deposit (Refundable)</span>
                                    <span class="font-medium font-mono text-gray-900">RM {{ number_format($booking->deposit, 2) }}</span>
                                </div>
                            @endif

                            <div class="border-t border-gray-300 pt-4 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-800">Total Paid</span>
                                <span class="text-3xl font-bold text-red-600 font-mono">RM {{ number_format(((float)($booking->totalPrice ?? 0)), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Inspection Forms Section --}}
                    <div class="border-t border-gray-200 pt-10">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Vehicle Inspection Forms
                        </h3>

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-red-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                        <ul class="list-disc pl-5 mt-1 text-sm text-red-700">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('bookings.upload-forms', $booking->bookingID) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Pickup Form Upload --}}
                                <div class="space-y-4">
                                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Pickup Inspection</label>
                                    <div class="bg-gray-50 p-4 rounded-xl border-2 border-dashed border-gray-300 hover:border-red-400 transition text-center group">
                                        @if($booking->pickupForm)
                                            <div class="relative mb-3">
                                                <img src="{{ asset('storage/' . $booking->pickupForm) }}" class="w-full h-40 object-cover rounded-lg shadow-sm">
                                                <a href="{{ asset('storage/' . $booking->pickupForm) }}" target="_blank" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-lg">
                                                    <span class="bg-white text-gray-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-md">View Full Size</span>
                                                </a>
                                            </div>
                                            <p class="text-xs text-green-600 font-bold mb-2 flex items-center justify-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Uploaded</p>
                                        @else
                                            <div class="h-40 flex flex-col items-center justify-center text-gray-400 mb-3">
                                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                <span class="text-sm">No image uploaded</span>
                                            </div>
                                        @endif
                                        <input type="file" name="pickupForm" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition cursor-pointer">
                                    </div>
                                </div>

                                {{-- Return Form Upload --}}
                                <div class="space-y-4">
                                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Return Inspection</label>
                                    <div class="bg-gray-50 p-4 rounded-xl border-2 border-dashed border-gray-300 hover:border-red-400 transition text-center group">
                                        @if($booking->returnForm)
                                            <div class="relative mb-3">
                                                <img src="{{ asset('storage/' . $booking->returnForm) }}" class="w-full h-40 object-cover rounded-lg shadow-sm">
                                                <a href="{{ asset('storage/' . $booking->returnForm) }}" target="_blank" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-lg">
                                                    <span class="bg-white text-gray-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-md">View Full Size</span>
                                                </a>
                                            </div>
                                            <p class="text-xs text-green-600 font-bold mb-2 flex items-center justify-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Uploaded</p>
                                        @else
                                            <div class="h-40 flex flex-col items-center justify-center text-gray-400 mb-3">
                                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                <span class="text-sm">No image uploaded</span>
                                            </div>
                                        @endif
                                        <input type="file" name="returnForm" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition cursor-pointer">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="bg-gray-900 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:-translate-y-0.5 uppercase text-xs tracking-widest flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Save Inspection Images
                                </button>
                            </div>
                        </form>
                    </div>

                    @if($booking->notes)
                        <div class="border-t border-gray-200 pt-10 mt-10">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Additional Notes</h3>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r shadow-sm">
                                <p class="text-gray-700 italic">{{ $booking->notes }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Actions Footer --}}
                    <div class="border-t border-gray-200 pt-10 mt-10 flex flex-wrap justify-between items-center gap-4">
                        <a href="{{ route('bookings.index') }}" class="text-gray-600 font-medium hover:text-gray-900 transition flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Bookings
                        </a>

                        @if($booking->bookingStat !== 'completed' && $booking->bookingStat !== 'cancelled')
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
        </div>
    </div>
</x-app-layout>
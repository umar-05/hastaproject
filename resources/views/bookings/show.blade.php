<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Details - HASTA Travel & Tours</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-8 py-12">
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-hasta-red hover:text-hasta-redHover mb-6 transition">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to My Bookings
        </a>

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

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-hasta-red text-white px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Booking Details</h1>
                        <p class="text-red-100">Booking ID: #{{ $booking->bookingID }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-white
                        @if($booking->bookingStat === 'confirmed') text-green-800
                        @elseif($booking->bookingStat === 'pending') text-yellow-800
                        @elseif($booking->bookingStat === 'completed') text-blue-800
                        @else text-red-800
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
                        } elseif (strpos($modelName, 'y15') !== false)
                            $vehicleImage = 'y15zr-2023.png';
                            $vehicleType = 'Motorcycle';
                    }
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-gray-50 rounded-xl p-8 flex items-center justify-center">
                        <img src="{{ asset('images/' . $vehicleImage) }}" alt="{{ $vehicleName }}" class="w-full h-96 object-contain">
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold mb-2">{{ $vehicleName }}</h2>
                        <p class="text-gray-600 mb-6">{{ $vehicleType }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm mb-1">Transmission</p>
                                <p class="font-semibold">Automat</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm mb-1">Fuel Type</p>
                                <p class="font-semibold">RON 95</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm mb-1">Seats</p>
                                <p class="font-semibold">5 passengers</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-sm mb-1">Air Conditioning</p>
                                <p class="font-semibold">Yes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-8">
                    <h3 class="text-xl font-bold mb-6">Booking Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-500 text-sm mb-2">Pick Up Date & Time</p>
                            <p class="font-semibold text-lg">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M Y') }}</p>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('h:i A') }}</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-500 text-sm mb-2">Return Date & Time</p>
                            <p class="font-semibold text-lg">{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M Y') }}</p>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($booking->returnDate)->format('h:i A') }}</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-500 text-sm mb-2">Pick Up Location</p>
                            <p class="font-semibold">{{ $booking->pickupLoc }}</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-500 text-sm mb-2">Return Location</p>
                            <p class="font-semibold">{{ $booking->returnLoc }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-8 mt-8">
                    <h3 class="text-xl font-bold mb-6">Pricing Breakdown</h3>
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Base Price</span>
                            <span class="font-semibold">RM{{ number_format($basePrice, 2) }}</span>
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
                            <span class="text-2xl font-bold text-hasta-red">RM{{ number_format(((float)($booking->totalPrice ?? 0)), 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Payment Status</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if(strtolower($booking->payment_status) === 'paid') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($booking->payment_status ?? 'Pending') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- --- INSPECTION FORMS SECTION --- --}}
                <div class="border-t pt-8 mt-8">
                    <h3 class="text-xl font-bold mb-6">Vehicle Inspection Forms</h3>

                    {{-- 1. DISPLAY VALIDATION ERRORS (Fixes "Just refreshes" issue) --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm leading-5 font-medium text-red-800">
                                        There were errors with your submission
                                    </h3>
                                    <div class="mt-2 text-sm leading-5 text-red-700">
                                        <ul class="list-disc pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('bookings.upload-forms', $booking->bookingID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Pickup Section --}}
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 text-center">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Pickup Inspection</label>
                                
                                @if($booking->pickupForm)
                                    <div class="mb-4 relative group">
                                        {{-- 2. IMAGE PREVIEW (Shows the saved image) --}}
                                        <img src="{{ asset('storage/' . $booking->pickupForm) }}" 
                                            class="w-full h-48 object-cover rounded-xl shadow-sm border border-gray-200">
                                        <a href="{{ asset('storage/' . $booking->pickupForm) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl text-white font-bold text-sm">
                                            View Full Size
                                        </a>
                                        <p class="text-[10px] text-green-600 font-bold mt-2 uppercase tracking-tighter">✓ Image Saved</p>
                                    </div>
                                @else
                                    <div class="mb-4 h-48 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-200">
                                        <p class="text-xs text-gray-400">No image uploaded yet</p>
                                    </div>
                                @endif

                                <input type="file" name="pickupForm" accept="image/*" class="text-xs text-gray-500 mx-auto w-full">
                            </div>

                            {{-- Return Section --}}
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 text-center">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Return Inspection</label>
                                
                                @if($booking->returnForm)
                                    <div class="mb-4 relative group">
                                        {{-- 2. IMAGE PREVIEW --}}
                                        <img src="{{ asset('storage/' . $booking->returnForm) }}" 
                                            class="w-full h-48 object-cover rounded-xl shadow-sm border border-gray-200">
                                        <a href="{{ asset('storage/' . $booking->returnForm) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl text-white font-bold text-sm">
                                            View Full Size
                                        </a>
                                        <p class="text-[10px] text-green-600 font-bold mt-2 uppercase tracking-tighter">✓ Image Saved</p>
                                    </div>
                                @else
                                    <div class="mb-4 h-48 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-200">
                                        <p class="text-xs text-gray-400">No image uploaded yet</p>
                                    </div>
                                @endif

                                <input type="file" name="returnForm" accept="image/*" class="text-xs text-gray-500 mx-auto w-full">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-gray-900 hover:bg-hasta-red text-white font-bold px-10 py-3 rounded-xl transition shadow-md uppercase text-xs tracking-widest">
                                Update Inspection Images
                            </button>
                        </div>
                    </form>
                </div>
                {{-- -------------------------------- --}}

                @if($booking->notes)
                <div class="border-t pt-8 mt-8">
                    <h3 class="text-xl font-bold mb-4">Notes</h3>
                    <p class="text-gray-600 bg-gray-50 p-4 rounded-lg">{{ $booking->notes }}</p>
                </div>
                @endif

                <div class="border-t pt-8 mt-8 flex items-start gap-4">
                    <a href="{{ route('bookings.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-6 py-3 rounded-md transition">
                        Back to Bookings
                    </a>
                    @if($booking->bookingStat !== 'completed' && $booking->bookingStat !== 'cancelled')
                        <form action="{{ route('bookings.cancel', $booking->bookingID) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                              class="inline">
                            @csrf
                            <button type="submit" 
                                    class="border-2 border-hasta-red text-hasta-red hover:bg-red-50 font-bold px-6 py-3 rounded-md transition">
                                Cancel Booking
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </main>

</body>
</html>
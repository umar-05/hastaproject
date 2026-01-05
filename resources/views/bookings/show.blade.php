


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

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-hasta-red text-white px-8 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Booking Details</h1>
                        <p class="text-red-100">Booking ID: #{{ $booking->bookingID }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-white
                        @if($booking->booking_stat === 'confirmed') text-green-800
                        @elseif($booking->booking_stat === 'pending') text-yellow-800
                        @elseif($booking->booking_stat === 'completed') text-blue-800
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
                        }
                    }
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Vehicle Image -->
                    <div class="bg-gray-50 rounded-xl p-8 flex items-center justify-center">
                        <img src="{{ asset('images/' . $vehicleImage) }}" alt="{{ $vehicleName }}" class="w-full h-96 object-contain">
                    </div>

                    <!-- Vehicle Info -->
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

                <!-- Booking Information -->
                <div class="border-t pt-8">
                    <h3 class="text-xl font-bold mb-6">Booking Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-500 text-sm mb-2">Pick Up Date & Time</p>
                            <p class="font-semibold text-lg">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}</p>
                            <p class="text-gray-600">{{ $booking->pickup_time }}</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-500 text-sm mb-2">Return Date & Time</p>
                            <p class="font-semibold text-lg">{{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}</p>
                            <p class="text-gray-600">{{ $booking->return_time }}</p>
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

                <!-- Pricing Breakdown -->
                <div class="border-t pt-8 mt-8">
                    <h3 class="text-xl font-bold mb-6">Pricing Breakdown</h3>
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Base Price</span>
                            <span class="font-semibold">RM{{ number_format($basePrice) }}</span>
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
                                @if($booking->payment_status === 'paid') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($booking->notes)
                <div class="border-t pt-8 mt-8">
                    <h3 class="text-xl font-bold mb-4">Notes</h3>
                    <p class="text-gray-600 bg-gray-50 p-4 rounded-lg">{{ $booking->notes }}</p>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="border-t pt-8 mt-8 flex items-start gap-4">
                    <a href="{{ route('bookings.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-6 py-3 rounded-md transition">
                        Back to Bookings
                    </a>
                    @if($booking->booking_stat !== 'completed' && $booking->booking_stat !== 'cancelled')
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


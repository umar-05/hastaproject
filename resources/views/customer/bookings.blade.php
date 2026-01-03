<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Bookings - HASTA Travel & Tours</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <!-- Header -->
    <header class="py-6 px-8 flex items-center justify-between max-w-7xl mx-auto bg-white">
        <div class="flex items-center">
            <div class="border-2 border-hasta-red px-2 py-1 rounded-sm">
                <span class="text-2xl font-bold text-hasta-red">HASTA</span>
            </div>
        </div>

        <nav class="hidden md:flex items-center space-x-8 font-medium">
            <a href="{{ route('vehicles.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Book Now
            </a>

            <a href="{{ route('bookings.index') }}" class="bg-hasta-red text-white px-5 py-2 rounded-md font-bold transition shadow-md">
                Bookings
            </a>

            <a href="{{ route('rewards.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Rewards
            </a>

            <a href="{{ route('faq') }}" class="text-gray-700 hover:text-hasta-red transition">
                FAQ
            </a>
        </nav>

        <div class="flex items-center space-x-6">
            @auth
                <a href="{{ route('profile.edit') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition duration-150 ease-in-out">
                    <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-hasta-red hover:bg-red-700 text-white font-bold py-2 px-8 rounded transition">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-hasta-red hover:bg-red-700 text-white font-bold py-2 px-8 rounded transition text-center">
                    Login
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-8 py-12">
        <h1 class="text-4xl font-bold mb-8">My Bookings</h1>

        <!-- Success/Error Messages -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($bookings->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl shadow-md">
                <svg class="mx-auto h-24 w-24 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-600 text-lg mb-6">You don't have any bookings yet.</p>
                <a href="{{ route('book-now') }}" class="inline-block bg-hasta-red hover:bg-red-700 text-white font-bold px-8 py-3 rounded-md transition">
                    Browse Vehicles
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    @php
                        $vehicleImage = 'default-car.png';
                        $vehicleName = 'Vehicle';
                        $vehicleType = 'Car';
                        
                        if ($booking->fleet) {
                            $fleet = $booking->fleet;
                            $vehicleName = $fleet->modelName . ($fleet->year ? ' ' . $fleet->year : '');
                            
                            // Get vehicle info
                            $modelName = strtolower($fleet->modelName);
                            $year = $fleet->year ?? 0;
                            
                            // Determine image
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
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                        <div class="flex items-center gap-6">
                            <!-- Car Image (Small) -->
                            <div class="w-24 h-24 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('images/' . $vehicleImage) }}" 
                                     alt="{{ $vehicleName }}" 
                                     class="w-full h-full object-contain p-2">
                            </div>

                            <!-- Essential Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $vehicleName }}</h3>
                                        <p class="text-sm text-gray-500">{{ $vehicleType }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold ml-4 flex-shrink-0
                                        @if(($booking->bookingStat ?? '') === 'confirmed') bg-green-100 text-green-800
                                        @elseif(($booking->bookingStat ?? '') === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif(($booking->bookingStat ?? '') === 'completed') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($booking->bookingStat ?? 'unknown') }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 text-sm">
                                    <div>
                                        <p class="text-gray-500 text-xs mb-1">Pick Up</p>
                                        <p class="font-semibold">{{ optional($booking->pickupDate)->format('d M Y') ?? (\Carbon\Carbon::parse($booking->pickupDate ?? $booking->pickup_date)->format('d M Y')) }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $booking->pickupLoc ?? $booking->pickup_loc }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs mb-1">Return</p>
                                        <p class="font-semibold">{{ optional($booking->returnDate)->format('d M Y') ?? (\Carbon\Carbon::parse($booking->returnDate ?? $booking->return_date)->format('d M Y')) }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $booking->returnLoc ?? $booking->return_loc }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs mb-1">Total Amount</p>
                                        <p class="font-semibold text-hasta-red">RM{{ number_format($booking->totalPrice ?? $booking->total_price ?? 0, 2) }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ ucfirst($booking->payment_status ?? 'pending') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-2 flex-shrink-0">
                                <a href="{{ route('bookings.show', $booking->bookingID ?? $booking->booking_id) }}" 
                                   class="bg-hasta-red hover:bg-red-700 text-white font-bold px-4 py-2 rounded-md transition text-sm text-center whitespace-nowrap">
                                    View Details
                                </a>
                                
                                @if(($booking->bookingStat ?? $booking->booking_stat) !== 'completed' && ($booking->bookingStat ?? $booking->booking_stat) !== 'cancelled')
                                    <form action="{{ route('bookings.cancel', $booking->bookingID ?? $booking->booking_id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full border-2 border-hasta-red text-hasta-red hover:bg-red-50 font-bold px-4 py-2 rounded-md transition text-sm whitespace-nowrap">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-hasta-red text-white py-12 px-8 mt-16">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="border-2 border-white px-2 py-1 rounded-sm inline-block mb-8">
                    <span class="text-2xl font-bold">HASTA</span>
                </div>
                <div class="flex items-start mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 mt-1 text-hasta-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <p class="text-sm leading-relaxed">Address<br>Student Mall UTM<br>Skudai, 81300, Johor Bahru</p>
                </div>
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-hasta-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <p class="text-sm">Email<br>hastatravel@gmail.com</p>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-hasta-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    <p class="text-sm">Phone<br>011-1090 0700</p>
                </div>
            </div>

            <div>
                <h5 class="font-bold mb-6">Useful links</h5>
                <ul class="space-y-3 text-sm opacity-80">
                    <li><a href="#" class="hover:opacity-100">About us</a></li>
                    <li><a href="#" class="hover:opacity-100">Contact us</a></li>
                    <li><a href="#" class="hover:opacity-100">Gallery</a></li>
                    <li><a href="#" class="hover:opacity-100">Blog</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:opacity-100">F.A.Q</a></li>
                </ul>
            </div>

            <div>
                <h5 class="font-bold mb-6">Vehicles</h5>
                <ul class="space-y-3 text-sm opacity-80">
                    <li><a href="#" class="hover:opacity-100">Sedan</a></li>
                    <li><a href="#" class="hover:opacity-100">Hatchback</a></li>
                    <li><a href="#" class="hover:opacity-100">MPV</a></li>
                    <li><a href="#" class="hover:opacity-100">Minivan</a></li>
                    <li><a href="#" class="hover:opacity-100">SUV</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-red-800 flex space-x-6">
            <a href="#" class="bg-black rounded-full p-2"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
            <a href="#" class="bg-black rounded-full p-2"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.012 3.584-.07 4.85c-.148 3.252-1.667 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.012-3.584.07-4.85c.148-3.252 1.667-4.771 4.919-4.919 1.266-.058 1.645-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
        </div>
    </footer>

</body>
</html>
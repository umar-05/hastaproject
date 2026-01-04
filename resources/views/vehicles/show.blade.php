<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $vehicle['name'] }} - HASTA Travel & Tours</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-8 py-12">

        <a href="{{ route('vehicles.index') }}" class="inline-flex items-center text-hasta-red hover:text-hasta-redHover mb-6 transition">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Vehicles
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
            <!-- Vehicle Image -->
            <div class="bg-gray-50 rounded-xl p-8 flex items-center justify-center">
                <img src="{{ asset('images/' . $vehicle['image']) }}" alt="{{ $vehicle['name'] }}" class="w-full h-96 object-contain">
            </div>

            <!-- Vehicle Details -->
            <div>
                <div class="mb-6">
                    <span class="inline-block bg-gray-200 text-gray-700 px-4 py-1 rounded-full text-sm font-medium mb-3">
                        {{ $vehicle['type'] }}
                    </span>
                    <h1 class="text-4xl font-extrabold mb-4">{{ $vehicle['name'] }}</h1>
                    <div class="flex items-baseline mb-6">
                        <span class="text-hasta-red text-4xl font-bold">RM{{ $vehicle['price'] }}</span>
                        <span class="text-gray-500 text-lg ml-2">per day</span>
                    </div>
                </div>

                <!-- Vehicle Specifications -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">Specifications</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3 text-hasta-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Transmission</p>
                                <p class="font-semibold">{{ $vehicle['transmission'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3 text-hasta-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Fuel Type</p>
                                <p class="font-semibold">{{ $vehicle['fuel'] }}</p>
                            </div>
                        </div>
                        @if(isset($vehicle['seats']))
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3 text-hasta-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Seats</p>
                                <p class="font-semibold">{{ $vehicle['seats'] }} passengers</p>
                            </div>
                        </div>
                        @endif
                        @if($vehicle['ac'])
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-3 text-hasta-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Air Conditioning</p>
                                <p class="font-semibold">Yes</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if(isset($vehicle['description']))
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-3">Description</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $vehicle['description'] }}</p>
                </div>
                @endif

                <!-- Book Button -->
                <a href="{{ route('bookings.create', $vehicle['id']) }}">
                    <button class="w-full bg-hasta-red hover:bg-hasta-redHover text-white font-bold py-4 rounded-lg transition text-lg">
                        Book This Vehicle
                    </button>
                </a>
            </div>
        </div>

    </main>

    <footer class="bg-hasta-red text-white py-12 px-8">
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
                    <li><a href="{{ route('home') }}" class="hover:opacity-100">Home</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:opacity-100">Contact us</a></li>
                </ul>
            </div>

            <div>
                <h5 class="font-bold mb-6">Vehicles</h5>
                <ul class="space-y-3 text-sm opacity-80">
                    <li><a href="{{ route('vehicles.index') }}" class="hover:opacity-100">All Vehicles</a></li>
                    <li><a href="#" class="hover:opacity-100">Sedan</a></li>
                    <li><a href="#" class="hover:opacity-100">Hatchback</a></li>
                    <li><a href="#" class="hover:opacity-100">MPV</a></li>
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


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicles - HASTA Travel & Tours</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    @include('layouts.navigation')

    {{-- Error Message Display --}}
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-8 mt-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-8 py-12">

        <section class="mb-12">
            <h1 class="text-4xl font-extrabold mb-4">Our Vehicle Fleet</h1>
            <p class="text-gray-600 text-lg">Choose the perfect vehicle for your journey</p>
        </section>

        <div class="flex flex-wrap gap-3 mb-8">
            <button class="filter-btn active bg-hasta-red text-white px-6 py-2 rounded-full hover:bg-red-700 transition font-medium" data-filter="all">
                All vehicles
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition font-medium" data-filter="Sedan">
                Sedan
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition font-medium" data-filter="Hatchback">
                Hatchback
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition font-medium" data-filter="MPV">
                MPV
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition font-medium" data-filter="SUV">
                SUV
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            @foreach($vehicles as $vehicle)
            {{-- NOTICE: Changed back to array syntax since controller returns arrays --}}
            <div class="vehicle-card bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] p-6 hover:shadow-[0_8px_30px_rgba(0,0,0,0.12)] transition" data-type="{{ $vehicle['type'] }}">
                <div class="mb-4">
                    <img src="{{ asset('images/'.$vehicle['image']) }}" alt="{{ $vehicle['name'] }}" class="w-full h-40 object-contain">
                </div>
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="text-xl font-bold">{{ $vehicle['name'] }}</h4>
                        <p class="text-gray-500 text-sm">{{ $vehicle['type'] }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-hasta-red text-xl font-bold">RM{{ $vehicle['price'] }}</span>
                        <p class="text-gray-500 text-xs">per day</p>
                    </div>
                </div>
                <div class="flex space-x-4 text-gray-500 text-sm mb-6">
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        {{ $vehicle['transmission'] }}
                    </span>
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        {{ $vehicle['fuel'] }}
                    </span>
                    @if($vehicle['ac'])
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        Air Cond
                    </span>
                    @endif
                </div>

                {{-- ========================================== --}}
                {{-- FIX: PASSED $vehicle['id'] TO THE ROUTE      --}}
                {{-- ========================================== --}}
                <a href="{{ route('vehicles.show', $vehicle['id']) }}">
                    <button class="w-full bg-hasta-red hover:bg-red-700 text-white font-bold py-3 rounded transition">
                        View Details
                    </button>
                </a>

            </div>
            @endforeach
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
            <span class="text-xs opacity-50">&copy; 2026 HASTA Travel & Tours. All rights reserved.</span>
        </div>
    </footer>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const vehicleCards = document.querySelectorAll('.vehicle-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;
                
                // Update active button
                filterButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-hasta-red', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });
                button.classList.add('active', 'bg-hasta-red', 'text-white');
                button.classList.remove('bg-gray-200', 'text-gray-700');

                // Filter vehicles
                vehicleCards.forEach(card => {
                    if (filter === 'all' || card.dataset.type === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
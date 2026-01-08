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

    <main class="max-w-7xl mx-auto px-8 py-12">

        <section class="mb-12">
            <h1 class="text-4xl font-bold mb-4">Our Vehicle Fleet</h1>
            <p class="text-gray-600 text-lg">Choose the perfect vehicle for your journey</p>
        </section>

        <!-- Filter Buttons -->
        <div class="flex flex-wrap gap-3 mb-8">
            <button class="filter-btn active bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition font-medium" data-filter="all">
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

        <!-- Vehicle Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            @foreach($vehicles as $vehicle)
            <div class="vehicle-card bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] p-6 hover:shadow-[0_8px_30px_rgba(0,0,0,0.12)] transition" data-type="{{ $vehicle['type'] }}">
                <div class="mb-4">
                    <img src="{{ asset('images/'.$vehicle['image']) }}" alt="{{ $vehicle['name'] }}" class="w-full h-40 object-contain">
                </div>
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="text-2xl font-bold">{{ $vehicle['name'] }}</h4>
                        <p class="text-gray-500 text-sm">{{ $vehicle['type'] }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-hasta-red text-2xl font-bold">RM{{ $vehicle['price'] }}</span>
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
                        Air Conditioner
                    </span>
                    @endif
                </div>
                <a href="{{ route('vehicles.show', $vehicle['id']) }}">
                    <button class="w-full bg-hasta-red hover:bg-hasta-redHover text-white font-bold py-3 rounded transition">
                        View Details
                    </button>
                </a>
            </div>
            @endforeach
        </div>

    </main>

    <footer class="bg-hasta-red text-white py-10 px-8 mt-16">
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center text-center">
            
            <div class="mb-4">
                <img src="{{ asset('images/HASTALOGO.svg') }}" 
                     alt="HASTA Travel & Tours" 
                     class="h-12 w-auto object-contain">
            </div>

            <div class="space-y-2">
                <p class="text-sm font-medium">HASTA Travel & Tours</p>
                <p class="text-xs opacity-75">
                    &copy; {{ date('Y') }} All rights reserved.
                </p>
            </div>
            
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


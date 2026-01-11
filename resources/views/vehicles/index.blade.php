<x-app-layout>
    {{-- Custom Styles for this page --}}
    <style>
        /* Page Load Animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        
        /* Ensure images fit within cards consistently */
        .vehicle-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>

    {{-- Error Message Display --}}
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-6 mt-6 animate-fade-up">
            <div class="bg-red-50 border-l-4 border-hasta-red text-red-700 px-6 py-4 rounded-r-lg shadow-sm">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-6 py-10">
        {{-- Filter Bar --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4 animate-fade-up delay-100">
            <div class="flex flex-wrap gap-2">
                <button class="filter-btn active px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm bg-gray-900 text-white hover:shadow-lg transform hover:-translate-y-0.5" data-filter="all">
                    All
                </button>
                <button class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-900 border border-gray-200" data-filter="Sedan">
                    Sedan
                </button>
                <button class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-900 border border-gray-200" data-filter="Hatchback">
                    Hatchback
                </button>
                <button class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-900 border border-gray-200" data-filter="MPV">
                    MPV
                </button>
                <button class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-900 border border-gray-200" data-filter="SUV">
                    SUV
                </button>
                {{-- Added Motorcycle Tab --}}
                <button class="filter-btn px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 shadow-sm bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-900 border border-gray-200" data-filter="Motorcycle">
                    Motorcycles
                </button>
            </div>
            
            <div class="text-gray-400 text-sm font-medium">
                Showing <span id="vehicle-count" class="text-gray-900 font-bold">{{ count($vehicles) }}</span> vehicles
            </div>
        </div>

        {{-- Vehicle Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20 animate-fade-up delay-200" id="vehicle-grid">
            @foreach($vehicles as $vehicle)
            <div class="vehicle-card group bg-white rounded-[2rem] p-4 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] transition-all duration-300 border border-transparent hover:border-gray-100 hover:-translate-y-2 flex flex-col h-full" data-type="{{ $vehicle['type'] }}">
                
                {{-- Image Container --}}
                <div class="relative bg-gray-50 rounded-[1.5rem] p-6 h-56 flex items-center justify-center mb-5 overflow-hidden">
                    <div class="absolute w-32 h-32 bg-gray-200/50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <img src="{{ asset('images/'.$vehicle['image']) }}" 
                         alt="{{ $vehicle['name'] }}" 
                         class="relative z-10 vehicle-img transform transition-transform duration-500 group-hover:scale-110">
                </div>

                {{-- Details Section --}}
                <div class="px-2 flex-grow flex flex-col">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <span class="inline-block py-1 px-2 rounded-md bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">
                                {{ $vehicle['type'] }}
                            </span>
                            <h4 class="text-2xl font-bold text-gray-900 leading-tight">{{ $vehicle['name'] }}</h4>
                        </div>
                        <div class="text-right">
                            <span class="block text-hasta-red text-2xl font-bold">RM{{ $vehicle['price'] }}</span>
                            <span class="text-gray-400 text-xs font-medium">/day</span>
                        </div>
                    </div>

                    {{-- Specs Badges --}}
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-50 border border-gray-100 text-xs font-semibold text-gray-600">
                            <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                            {{ $vehicle['transmission'] }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-50 border border-gray-100 text-xs font-semibold text-gray-600">
                            <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            {{ $vehicle['fuel'] }}
                        </span>
                        {{-- Only show AC for non-motorcycles --}}
                        @if($vehicle['type'] !== 'Motorcycle' && $vehicle['ac'])
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-50 border border-gray-100 text-xs font-semibold text-gray-600">
                            <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                            A/C
                        </span>
                        @endif
                    </div>

                    {{-- CTA Button (Updated to pass query params) --}}
                    <div class="mt-auto">
                        {{-- Merging route parameters with request query parameters --}}
                        <a href="{{ route('vehicles.show', array_merge([$vehicle['id']], request()->query())) }}" class="block w-full">
                            <button class="w-full bg-hasta-red text-white font-bold py-3.5 rounded-xl transition-colors duration-200 hover:bg-red-700 hover:shadow-md flex items-center justify-center">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </main>

    {{-- Footer --}}
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

    {{-- Filter Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const vehicleCards = document.querySelectorAll('.vehicle-card');
            const vehicleCount = document.getElementById('vehicle-count');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.dataset.filter;
                    let count = 0;

                    // Update button UI
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-gray-900', 'text-white');
                        btn.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-100');
                    });
                    button.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-100');
                    button.classList.add('bg-gray-900', 'text-white');

                    // Simple, instant filter logic
                    vehicleCards.forEach(card => {
                        const type = card.dataset.type;
                        
                        // Matches 'all' or specific type (e.g., 'Motorcycle')
                        if (filter === 'all' || type === filter) {
                            card.style.display = 'flex';
                            count++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Update Counter text
                    vehicleCount.innerText = count;
                });
            });
        });
    </script>
</x-app-layout>
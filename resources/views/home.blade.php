@php
    // Define location options here to keep the HTML clean
    $colleges = [
        'Kolej Perdana', 'Kolej 9', 'Kolej 10', 'Kolej Datin Seri Endon',
        'Kolej Rahman Putra', 'Kolej Tun Fatimah', 'Kolej Tun Razak',
        'Kolej Tun Hussein Onn', 'Kolej Tunku Canselor',
        'Kolej Tun Dr Ismail', 'Kolej Dato Onn Jaafar'
    ];

    $faculties = [
        'Fakulti Komputeran' => 'Fakulti Komputeran (FC)',
        'Fakulti Kejuruteraan Elektrik' => 'Fakulti Kejuruteraan Elektrik (FKE)',
        'Fakulti Pengurusan' => 'Fakulti Pengurusan (FM)'
    ];
    
    // Return locations seem restricted in your original code, so we define them separately
    $returnLocations = ['Student Mall', 'Kolej Perdana', 'Kolej 9', 'Kolej 10'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hasta Travel & Tours') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { 
            animation: fadeUp 0.8s ease-out forwards; 
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #e11d48 0%, #9f1239 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.1;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.2 34.5L50 31V19c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H28v-2c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v12l-4.2 3.5c-.5.4-.8 1-.8 1.6V42c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-1h20v1c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-5.9c0-.6-.3-1.2-.8-1.6zM22 21h4v10h-4V21zm20 10V21h4v10h-4z' fill='%23ffffff' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .label-visible {
            color: #1f2937 !important; 
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            margin-bottom: 0.5rem;
            display: block;
            text-transform: capitalize;
        }

        .search-input-text {
            color: #000000 !important; 
            font-weight: 600;
            font-size: 0.95rem;
        }

        .search-select-icon {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23e11d48'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- HERO SECTION --}}
        <section class="relative rounded-[3rem] hero-gradient mb-20 shadow-2xl flex flex-col items-center justify-center p-8 md:p-16 text-center min-h-[450px]">
            <div class="hero-pattern"></div>
            
            <div class="relative z-10 w-full max-w-6xl">
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-10 drop-shadow-lg animate-fade-up">
                    Rent A Car In Malaysia
                </h1>

                <div class="bg-white/95 backdrop-blur-md p-3 rounded-[2.5rem] shadow-2xl animate-fade-up delay-100">
                    <form action="{{ route('vehicles.index') }}" method="GET" id="searchForm" class="bg-white p-6 md:p-8 rounded-[2rem] grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        
                        {{-- Pick-up Location --}}
                        <div class="md:col-span-3 text-left">
                            <label class="label-visible">Pick-up Location</label>
                            <select name="pickup_location" required class="search-input-text search-select-icon w-full pl-4 pr-10 py-3.5 bg-gray-100 border-none rounded-2xl focus:ring-2 focus:ring-red-600 outline-none">
                                <option value="Student Mall">Student Mall</option>
                                <optgroup label="Kolej">
                                    @foreach($colleges as $kolej)
                                        <option value="{{ $kolej }}">{{ $kolej }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Fakulti">
                                    @foreach($faculties as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        {{-- Return Location --}}
                        <div class="md:col-span-3 text-left">
                            <label class="label-visible">Return Location</label>
                            <select name="return_location" required class="search-input-text search-select-icon w-full pl-4 pr-10 py-3.5 bg-gray-100 border-none rounded-2xl focus:ring-2 focus:ring-red-600 outline-none">
                                @foreach($returnLocations as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pick-up Date & Time --}}
                        <div class="md:col-span-2 text-left">
                            <label class="label-visible">Pick-up</label>
                            <div class="flex flex-col bg-gray-100 rounded-2xl p-1.5">
                                <input type="date" name="pickup_date" id="pickup_date" required class="search-input-text w-full px-2 py-1 bg-transparent border-none focus:ring-0">
                                <select name="pickup_time" id="pickup_time" class="search-input-text bg-transparent border-none px-2 py-1 text-xs focus:ring-0">
                                    @for($h=9; $h<=17; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}">{{ date('g:i A', strtotime($h.':00')) }}</option>
                                        <option value="{{ sprintf('%02d:30', $h) }}">{{ date('g:i A', strtotime($h.':30')) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Return Date & Time --}}
                        <div class="md:col-span-2 text-left">
                            <label class="label-visible">Return</label>
                            <div class="flex flex-col bg-gray-100 rounded-2xl p-1.5">
                                <input type="date" name="return_date" id="return_date" required class="search-input-text w-full px-2 py-1 bg-transparent border-none focus:ring-0">
                                <select name="return_time" id="return_time" class="search-input-text bg-transparent border-none px-2 py-1 text-xs focus:ring-0">
                                    @for($h=9; $h<=17; $h++)
                                        <option value="{{ sprintf('%02d:00', $h) }}">{{ date('g:i A', strtotime($h.':00')) }}</option>
                                        <option value="{{ sprintf('%02d:30', $h) }}">{{ date('g:i A', strtotime($h.':30')) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Search Button --}}
                        <div class="md:col-span-2 pt-6">
                            <button type="submit" class="w-full bg-red-600 text-white h-[60px] rounded-2xl hover:bg-red-700 transition-all shadow-xl flex items-center justify-center group">
                                <span class="font-bold text-lg mr-2">Search</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- FEATURED VEHICLES SECTION --}}
        <section class="mb-20 animate-fade-up delay-300">
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 tracking-tight mb-2">Choose Your Ride</h2>
                    <p class="text-gray-500">Find the perfect vehicle for your next adventure.</p>
                </div>
                <a href="{{ route('vehicles.index') }}" class="text-sm font-bold text-gray-900 hover:text-red-600 flex items-center gap-2">
                    View Full Fleet &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($featuredVehicles ?? [] as $vehicle)
                    <div class="group bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all border border-transparent hover:border-gray-100">
                        {{-- Image --}}
                        <div class="relative bg-gray-50 rounded-2xl p-6 h-56 flex items-center justify-center mb-4">
                            
                            <img src="{{ asset('images/'.$vehicle->image) }}" alt="{{ $vehicle->modelName }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                        </div>
                        
                        {{-- Details --}}
                        <div class="px-2">
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $vehicle->type }}</p>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $vehicle->modelName }}</h4>
                                </div>
                                <div class="text-right">
                                    <span class="text-red-600 text-xl font-extrabold">RM{{ $vehicle->pricePerDay }}</span>
                                    <span class="text-gray-400 text-xs">/day</span>
                                </div>
                            </div>
                            
                            {{-- Booking Button --}}
                            <a href="{{ route('vehicles.show', array_merge(['id' => $vehicle->plateNumber], request()->query())) }}" class="block w-full py-3.5 rounded-xl bg-red-600 text-white text-center font-bold text-sm hover:opacity-90 transition-opacity">
                                Book Now
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20 bg-gray-100 rounded-[2rem]">
                        <p class="text-gray-500 font-medium">No vehicles available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </section>

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
        const pickupDate = document.getElementById('pickup_date');
        const returnDate = document.getElementById('return_date');
        const pickupTime = document.getElementById('pickup_time');
        const returnTime = document.getElementById('return_time');

        // Initial setup: Set today and tomorrow as default values
        window.addEventListener('load', () => {
            const now = new Date();
            const tomorrow = new Date();
            tomorrow.setDate(now.getDate() + 1);

            const todayStr = now.toISOString().split('T')[0];
            const tomorrowStr = tomorrow.toISOString().split('T')[0];

            pickupDate.setAttribute('min', todayStr);
            returnDate.setAttribute('min', todayStr);

            // Only set if value is empty
            if(!pickupDate.value) pickupDate.value = todayStr;
            if(!returnDate.value) returnDate.value = tomorrowStr;
        });

        // Automatically update Return Date to be at least 1 day after Pickup Date
        pickupDate.addEventListener('change', function() {
            if (this.value) {
                const dateObj = new Date(this.value);
                dateObj.setDate(dateObj.getDate() + 1);
                
                const nextDay = dateObj.toISOString().split('T')[0];
                
                returnDate.value = nextDay;
                returnDate.setAttribute('min', this.value);
                returnTime.value = pickupTime.value;
            }
        });

        // Sync times for convenience
        pickupTime.addEventListener('change', function() {
            returnTime.value = this.value;
        });
    </script>
</body>
</html>
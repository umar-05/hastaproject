<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HASTA Travel & Tours</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; }
        
        /* Updated brand-consistent gradient */
        .hero-gradient {
            background: linear-gradient(135deg, #e11d48 0%, #9f1239 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.1;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- UPDATED HERO SECTION --}}
        <section class="relative rounded-[3rem] hero-gradient mb-20 shadow-2xl flex flex-col items-center justify-center p-8 md:p-16 text-center min-h-[400px]">
            
            <div class="relative z-10 w-full max-w-5xl">
                {{-- Reduced bold and size for heading --}}
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-10 drop-shadow-md animate-fade-up">
                    Rent A Car in Malaysia
                </h1>

                <div class="bg-white p-2 rounded-[2rem] shadow-2xl animate-fade-up delay-100">
                    <form action="{{ route('vehicles.index') }}" method="GET" class="bg-white p-6 md:p-8 rounded-[1.5rem] grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        
                        {{-- Pick-up Location --}}
                        <div class="md:col-span-4 relative text-left">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-1">Pick-up Location</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-hasta-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <input type="text" name="location" placeholder="Search City or Airport" class="w-full pl-9 pr-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-hasta-red outline-none text-sm">
                            </div>
                        </div>

                        {{-- Pick-up Date & Time --}}
                        <div class="md:col-span-3 text-left">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-1">Pick-up Date & Time</label>
                            <div class="flex bg-gray-50 rounded-xl overflow-hidden">
                                <input type="date" name="pickup_date" class="w-full px-3 py-3 bg-transparent border-none outline-none text-sm focus:ring-0">
                                <select name="pickup_time" class="bg-transparent border-none pr-7 py-3 text-sm outline-none focus:ring-0">
                                    <option>09:00 AM</option>
                                    <option>10:00 AM</option>
                                    <option>01:00 PM</option>
                                </select>
                            </div>
                        </div>

                        {{-- Return Date & Time --}}
                        <div class="md:col-span-3 text-left">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-1">Return Date & Time</label>
                            <div class="flex bg-gray-50 rounded-xl overflow-hidden">
                                <input type="date" name="return_date" class="w-full px-3 py-3 bg-transparent border-none outline-none text-sm focus:ring-0">
                                <select name="return_time" class="bg-transparent border-none pr-7 py-3 text-sm outline-none focus:ring-0">
                                    <option>09:00 AM</option>
                                    <option>10:00 AM</option>
                                    <option>01:00 PM</option>
                                </select>
                            </div>
                        </div>

                        {{-- Search Button --}}
                        <div class="md:col-span-2 pt-5">
                            <button type="submit" class="w-full bg-hasta-red text-white h-[48px] rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-100 flex items-center justify-center group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>
                        </div>

                        {{-- Footer Checkbox --}}
                        <div class="md:col-span-12 flex items-center gap-2 text-xs text-gray-400 mt-2">
                            <input type="checkbox" id="return_another" class="rounded border-gray-300 text-hasta-red focus:ring-hasta-red">
                            <label for="return_another" class="cursor-pointer hover:text-gray-600">Return to another location</label>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- ACTIVE BOOKING SECTION --}}
        @if(isset($activeBooking) && $activeBooking)
        <section class="mb-24 animate-fade-up">
            <div class="glass-panel p-1 rounded-3xl shadow-sm">
                <div class="bg-white rounded-[1.3rem] p-8 md:p-10 border border-gray-100">
                    <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                         <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                                <span class="block w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Trip in Progress</h2>
                                <p class="text-xs text-gray-500">Booking ID #{{ $activeBooking->bookingID }}</p>
                            </div>
                         </div>
                         <a href="{{ route('bookings.show', $activeBooking->bookingID) }}" class="text-sm font-semibold text-hasta-red hover:underline">Manage Trip &rarr;</a>
                    </div>
                    
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <div class="w-full lg:w-1/3 relative">
                            <img src="{{ asset('images/herocar.png') }}" alt="Current Car" class="relative z-10 w-full h-auto object-contain transform scale-x-[-1]">
                        </div>
                        
                        <div class="w-full lg:w-2/3">
                            <h3 class="text-3xl font-extrabold text-gray-900 mb-1">
                                {{ $activeBooking->fleet->brand ?? 'Car' }} {{ $activeBooking->fleet->model ?? 'Model' }}
                            </h3>
                            <p class="text-gray-400 font-mono text-sm tracking-widest uppercase mb-10">
                                {{ $activeBooking->fleet->plateNumber ?? 'Unknown Plate' }}
                            </p>
                            
                            <div class="relative flex items-center justify-between w-full mb-8">
                                <div class="absolute left-0 top-1/2 w-full h-1 bg-gray-100 -z-10 rounded-full"></div>
                                <div class="absolute left-0 top-1/2 w-1/2 h-1 bg-gradient-to-r from-hasta-red to-red-300 -z-10 rounded-full"></div>

                                <div class="flex flex-col items-start bg-white pr-4">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pickup</span>
                                    <div class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($activeBooking->pickupDate)->format('d M') }}</div>
                                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($activeBooking->pickupTime)->format('h:i A') }}</div>
                                </div>

                                <div class="w-8 h-8 bg-hasta-red rounded-full flex items-center justify-center text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>

                                <div class="flex flex-col items-end bg-white pl-4">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Return</span>
                                    <div class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($activeBooking->returnDate)->format('d M') }}</div>
                                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($activeBooking->returnTime)->format('h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- FEATURES GRID --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24 animate-fade-up">
            <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-2">
                <div class="w-16 h-16 bg-red-50 text-hasta-red rounded-2xl flex items-center justify-center mb-6 group-hover:bg-hasta-red group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Instant Availability</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Real-time booking engine allowing you to secure your ideal ride in seconds, 24/7.</p>
            </div>

            <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-2">
                <div class="w-16 h-16 bg-red-50 text-hasta-red rounded-2xl flex items-center justify-center mb-6 group-hover:bg-hasta-red group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Premium Comfort</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Every vehicle is sanitized and inspected to ensure a safe, pristine driving environment.</p>
            </div>

            <div class="group bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 hover:-translate-y-2">
                <div class="w-16 h-16 bg-red-50 text-hasta-red rounded-2xl flex items-center justify-center mb-6 group-hover:bg-hasta-red group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-900">Best Value</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Transparent pricing with no hidden fees. Luxury experience at competitive market rates.</p>
            </div>
        </section>

        {{-- FEATURED VEHICLES SECTION --}}
        <section class="mb-20 animate-fade-up">
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Choose Your Ride</h2>
                    <p class="text-gray-500">Find the perfect vehicle for your next adventure.</p>
                </div>
                <a href="{{ route('vehicles.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-900 hover:text-hasta-red transition-colors">
                    View Full Fleet 
                    <span class="bg-gray-100 rounded-full p-1 group-hover:bg-hasta-red group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if(isset($featuredVehicles) && count($featuredVehicles) > 0)
                    @foreach($featuredVehicles as $vehicle)
                    <div class="group bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 border border-transparent hover:border-gray-100">
                        <div class="relative bg-gray-50 rounded-2xl p-6 h-56 flex items-center justify-center mb-4 overflow-hidden">
                            <div class="absolute w-40 h-40 bg-gray-200/50 rounded-full blur-2xl group-hover:bg-red-100/50 transition-colors duration-300"></div>
                            <img src="{{ asset('images/'.$vehicle->image) }}" 
                                 alt="{{ $vehicle->name }}" 
                                 class="relative z-10 w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-500 ease-out">
                        </div>
                        
                        <div class="px-2">
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $vehicle->type }}</p>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $vehicle->name }}</h4>
                                </div>
                                <div class="text-right">
                                    <span class="text-hasta-red text-xl font-extrabold">RM{{ $vehicle->price }}</span>
                                    <span class="text-gray-400 text-xs font-medium">/day</span>
                                </div>
                            </div>

                            <div class="flex gap-3 mb-6">
                                <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">{{ $vehicle->transmission }}</span>
                                <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">{{ $vehicle->seats }} Seats</span>
                            </div>

                            <a href="{{ route('vehicles.show', $vehicle->plateNumber) }}" class="block w-full py-3.5 rounded-xl bg-hasta-red text-white text-center font-bold text-sm transition-transform active:scale-95 hover:bg-red-700">
                                Book Now
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-3 text-center py-10 bg-gray-50 rounded-3xl">
                        <p class="text-gray-500">No featured vehicles available at the moment.</p>
                        <a href="{{ route('vehicles.index') }}" class="text-hasta-red font-bold hover:underline mt-2 inline-block">Go to Fleet</a>
                    </div>
                @endif
            </div>
        </section>

    </main>

    <footer class="bg-hasta-red text-white py-10 px-8 mt-16">
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center text-center">
            <div class="mb-4">
                <img src="{{ asset('images/HASTALOGO.svg') }}" 
                     alt="HASTA Travel & Tours" 
                     class="h-12 w-auto object-contain brightness-0 invert">
            </div>

            <div class="space-y-2">
                <p class="text-sm font-medium">HASTA Travel & Tours</p>
                <p class="text-xs opacity-75">
                    &copy; {{ date('Y') }} All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
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
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; }
        .animate-slide-in { animation: slideInRight 1s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
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

        <section class="relative rounded-[3rem] overflow-hidden mb-20 shadow-2xl group">
            <div class="absolute inset-0 bg-gradient-to-br from-hasta-red via-red-700 to-red-900 transition-transform duration-700 transform group-hover:scale-105"></div>
            
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between p-8 md:p-16">
                
                <div class="w-full md:w-1/2 text-white animate-fade-up z-20">
                    <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6 drop-shadow-lg tracking-tight">
                        Drive the <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500">Extraordinary</span>
                    </h1>
                    <p class="text-lg opacity-90 mb-10 max-w-md font-light leading-relaxed">
                        Elevate your journey with our curated selection of premium vehicles. Comfort, style, and performance wrapped in one.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('vehicles.index') }}" class="group/btn relative overflow-hidden bg-white text-hasta-red font-bold py-4 px-10 rounded-full transition shadow-lg hover:shadow-xl hover:scale-105 transform duration-300">
                            <span class="relative z-10">Browse Fleet</span>
                            <div class="absolute inset-0 h-full w-full bg-gray-100 transform scale-x-0 group-hover/btn:scale-x-100 transition-transform origin-left duration-300"></div>
                        </a>
                    </div>
                </div>

                <div class="w-full md:w-1/2 mt-12 md:mt-0 relative animate-slide-in">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-white/10 blur-3xl rounded-full pointer-events-none"></div>
                    
                    <img src="{{ asset('images/herocar.png') }}" 
                         alt="Hero Car" 
                         class="relative w-full h-auto object-contain animate-float drop-shadow-2xl transform md:scale-125 md:translate-x-10">
                </div>
            </div>
        </section>

        @if(isset($activeBooking) && $activeBooking)
        <section class="mb-24 animate-fade-up delay-100">
            <div class="glass-panel p-1 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
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
                        <div class="w-full lg:w-1/3 relative group">
                            <div class="absolute inset-0 bg-gray-100 rounded-full transform scale-75 group-hover:scale-90 transition-transform duration-500"></div>
                            <img src="{{ asset('images/herocar.png') }}" alt="Current Car" class="relative z-10 w-full h-auto object-contain transform scale-x-[-1] transition-transform duration-500 group-hover:scale-x-[-1] group-hover:scale-105">
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

                                <div class="w-8 h-8 bg-hasta-red rounded-full flex items-center justify-center text-white shadow-lg shadow-red-200">
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

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24 animate-fade-up delay-200">
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

        <section class="mb-20 animate-fade-up delay-300">
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
                
                <div class="group bg-white rounded-3xl p-4 shadow-[0_2px_15px_rgb(0,0,0,0.05)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.1)] transition-all duration-300 border border-transparent hover:border-gray-100">
                    <div class="relative bg-gray-50 rounded-2xl p-6 h-56 flex items-center justify-center mb-4 overflow-hidden">
                        <div class="absolute w-40 h-40 bg-gray-200/50 rounded-full blur-2xl group-hover:bg-red-100/50 transition-colors duration-300"></div>
                        <img src="{{ asset('/images/axia-2018.png') }}" alt="Perodua Axia" class="relative z-10 w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    
                    <div class="px-2">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Compact</p>
                                <h4 class="text-lg font-bold text-gray-900">Perodua Axia 2018</h4>
                            </div>
                            <div class="text-right">
                                <span class="text-hasta-red text-xl font-extrabold">RM120</span>
                                <span class="text-gray-400 text-xs font-medium">/day</span>
                            </div>
                        </div>

                        <div class="flex gap-3 mb-6">
                            <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">Auto</span>
                            <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">4 Seats</span>
                        </div>

                        <a href="{{ route('vehicles.index') }}" class="block w-full py-3.5 rounded-xl bg-hasta-red text-white text-center font-bold text-sm transition-transform active:scale-95 hover:bg-red-700">
                            Book Now
                        </a>
                    </div>
                </div>

                <div class="group bg-white rounded-3xl p-4 shadow-[0_2px_15px_rgb(0,0,0,0.05)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.1)] transition-all duration-300 border border-transparent hover:border-gray-100">
                    <div class="relative bg-gray-50 rounded-2xl p-6 h-56 flex items-center justify-center mb-4 overflow-hidden">
                         <div class="absolute w-40 h-40 bg-gray-200/50 rounded-full blur-2xl group-hover:bg-red-100/50 transition-colors duration-300"></div>
                        <img src="{{ asset('/images/bezza-2018.png') }}" alt="Perodua Bezza" class="relative z-10 w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    
                    <div class="px-2">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Sedan</p>
                                <h4 class="text-lg font-bold text-gray-900">Perodua Bezza 2018</h4>
                            </div>
                            <div class="text-right">
                                <span class="text-hasta-red text-xl font-extrabold">RM140</span>
                                <span class="text-gray-400 text-xs font-medium">/day</span>
                            </div>
                        </div>

                        <div class="flex gap-3 mb-6">
                            <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">Auto</span>
                            <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">5 Seats</span>
                        </div>

                        <a href="{{ route('vehicles.index') }}" class="block w-full py-3.5 rounded-xl bg-hasta-red text-white text-center font-bold text-sm transition-transform active:scale-95 hover:bg-red-700">
                            Book Now
                        </a>
                    </div>
                </div>

                <div class="group bg-white rounded-3xl p-4 shadow-[0_2px_15px_rgb(0,0,0,0.05)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.1)] transition-all duration-300 border border-transparent hover:border-gray-100">
                    <div class="relative bg-gray-50 rounded-2xl p-6 h-56 flex items-center justify-center mb-4 overflow-hidden">
                         <div class="absolute w-40 h-40 bg-gray-200/50 rounded-full blur-2xl group-hover:bg-red-100/50 transition-colors duration-300"></div>
                        <img src="{{ asset('/images/myvi-2015.png') }}" alt="Perodua Myvi" class="relative z-10 w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    
                    <div class="px-2">
                        <div class="flex justify-between items-end mb-4">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Sport Hatch</p>
                                <h4 class="text-lg font-bold text-gray-900">Perodua Myvi 2015</h4>
                            </div>
                            <div class="text-right">
                                <span class="text-hasta-red text-xl font-extrabold">RM120</span>
                                <span class="text-gray-400 text-xs font-medium">/day</span>
                            </div>
                        </div>

                        <div class="flex gap-3 mb-6">
                            <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">Auto</span>
                            <span class="px-3 py-1 rounded-full bg-gray-50 text-xs font-semibold text-gray-600 border border-gray-100">5 Seats</span>
                        </div>

                        <a href="{{ route('vehicles.index') }}" class="block w-full py-3.5 rounded-xl bg-hasta-red text-white text-center font-bold text-sm transition-transform active:scale-95 hover:bg-red-700">
                            Book Now
                        </a>
                    </div>
                </div>

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

</body>
</html>
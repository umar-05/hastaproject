<x-app-layout>
    {{-- Custom Animations & Styles --}}
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideRight {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; opacity: 0; }
        .animate-slide-right { animation: slideRight 0.8s ease-out forwards; opacity: 0; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>

    <div class="bg-gray-50 min-h-screen">
        
        <main class="max-w-7xl mx-auto px-6 py-10">

            {{-- Breadcrumb / Back --}}
            <div class="mb-8 animate-fade-up">
                <a href="{{ route('vehicles.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-hasta-red transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-3 group-hover:border-hasta-red transition-colors shadow-sm">
                        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </div>
                    Back to Fleet
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                {{-- LEFT COLUMN: Immersive Image --}}
                <div class="lg:col-span-7 relative animate-slide-right">
                    <div class="sticky top-10">
                        <div class="relative bg-white rounded-[2.5rem] p-10 shadow-xl overflow-hidden min-h-[400px] flex items-center justify-center border border-gray-100 group">
                            
                            {{-- Background Decoration --}}
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] bg-gradient-to-tr from-gray-100 to-red-50 rounded-full blur-3xl opacity-70 group-hover:opacity-100 transition-opacity duration-700"></div>
                            
                            {{-- Pattern --}}
                            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 24px 24px;"></div>

                            {{-- Vehicle Image --}}
                            <img src="{{ asset('images/' . $vehicle['image']) }}" 
                                 alt="{{ $vehicle['name'] }}" 
                                 class="relative z-10 w-full h-auto object-contain drop-shadow-2xl animate-float transform transition-transform duration-500 group-hover:scale-105">
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Details & Specs --}}
                <div class="lg:col-span-5 space-y-8">
                    
                    {{-- Header Info --}}
                    <div class="animate-fade-up delay-100">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-3">
                            {{ $vehicle['name'] }}
                        </h1>
                        
                        {{-- Moved Tag Below Name --}}
                        <div class="mb-5">
                            <span class="inline-block bg-gray-100 text-gray-600 px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider border border-gray-200">
                                {{ $vehicle['type'] }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4 border-b border-gray-100 pb-6">
                            <div class="flex items-baseline">
                                <span class="text-3xl font-bold text-hasta-red">RM{{ $vehicle['price'] }}</span>
                                <span class="text-gray-400 font-medium ml-1">/ day</span>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if(isset($vehicle['description']))
                    <div class="animate-fade-up delay-200">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">About this vehicle</h3>
                        <p class="text-gray-600 leading-relaxed text-base">
                            {{ $vehicle['description'] }}
                        </p>
                    </div>
                    @endif

                    {{-- Specs Grid --}}
                    <div class="animate-fade-up delay-300">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Specifications</h3>
                        <div class="grid grid-cols-2 gap-4">
                            
                            <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="w-10 h-10 rounded-full bg-red-50 text-hasta-red flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Transmission</p>
                                    <p class="text-gray-900 font-semibold">{{ $vehicle['transmission'] }}</p>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="w-10 h-10 rounded-full bg-red-50 text-hasta-red flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Fuel Type</p>
                                    <p class="text-gray-900 font-semibold">{{ $vehicle['fuel'] }}</p>
                                </div>
                            </div>

                            @if(isset($vehicle['seats']))
                            <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="w-10 h-10 rounded-full bg-red-50 text-hasta-red flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Capacity</p>
                                    <p class="text-gray-900 font-semibold">{{ $vehicle['seats'] }} People</p>
                                </div>
                            </div>
                            @endif

                            @if($vehicle['ac'])
                            <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="w-10 h-10 rounded-full bg-red-50 text-hasta-red flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Climate</p>
                                    <p class="text-gray-900 font-semibold">Air Con</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="pt-6 animate-fade-up delay-300">
                        <a href="{{ route('bookings.create', $vehicle['id']) }}" class="block w-full group">
                            <button class="w-full bg-hasta-red text-white font-bold text-lg py-5 rounded-xl shadow-lg shadow-red-200 transition-all duration-300 hover:bg-red-700 hover:shadow-red-300 transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                Book This Vehicle
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </a>
                    </div>

                </div>
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
    </div>
</x-app-layout>
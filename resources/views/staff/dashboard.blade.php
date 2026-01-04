<x-staff-layout>
    {{-- Main Content Wrapper --}}
    <div class="py-6 bg-gray-50 min-h-screen font-poppins">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- PAGE HEADER --}}
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                    DASHBOARD
                </h2>
                {{-- Date/Time Display --}}
                <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100">
                    {{ now()->format('l, d M Y') }}
                </div>
            </div>

            {{-- 1. TOP METRICS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                {{-- Card 1: Manage Booking (LINKED) --}}
                <a href="{{ route('staff.bookingmanagement') }}">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center group-hover:shadow-md group-hover:border-red-200 transition duration-200">
                        <div class="p-4 rounded-xl bg-red-50 text-hasta-red group-hover:bg-red-100 transition">
                            {{-- Calendar Icon --}}
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h4 class="text-3xl font-bold text-gray-800">5</h4>
                            <p class="text-sm text-gray-500 font-medium">Manage Booking</p>
                        </div>
                    </div>
                </a>

                {{-- Card 2: Pickup Today --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition">
                    <div class="p-4 rounded-xl bg-gray-50 text-gray-600">
                        {{-- Car Icon --}}
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h4 class="text-3xl font-bold text-gray-800">2</h4>
                        <p class="text-sm text-gray-500 font-medium">Pickup Today</p>
                    </div>
                </div>

                {{-- Card 3: Return Today --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center hover:shadow-md transition">
                    <div class="p-4 rounded-xl bg-gray-50 text-gray-600">
                        {{-- Refresh/Return Icon --}}
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h4 class="text-3xl font-bold text-gray-800">10</h4>
                        <p class="text-sm text-gray-500 font-medium">Return Today</p>
                    </div>
                </div>

            </div>

            {{-- 2. MIDDLE SECTION: Charts & Availability --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                {{-- LEFT: Recent Bookings Chart --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-semibold text-lg text-gray-800">Recent Bookings</h3>
                        <select class="text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 text-gray-500">
                            <option>Weekly</option>
                            <option>Monthly</option>
                        </select>
                    </div>

                    {{-- Chart Container --}}
                    <div class="relative h-64 w-full">
                        {{-- Y-Axis Grid Lines --}}
                        <div class="absolute inset-0 flex flex-col justify-between text-xs text-gray-300 pointer-events-none">
                            <div class="border-b border-gray-100 h-0 w-full"></div>
                            <div class="border-b border-gray-100 h-0 w-full"></div>
                            <div class="border-b border-gray-100 h-0 w-full"></div>
                            <div class="border-b border-gray-100 h-0 w-full"></div>
                            <div class="border-b border-gray-100 h-0 w-full"></div>
                        </div>

                        {{-- Chart Bars --}}
                        <div class="relative h-full flex items-end justify-between pl-6 pb-6">
                            <div class="absolute left-0 top-0 h-full flex flex-col justify-between text-xs text-gray-400 pb-6">
                                <span>12</span><span>9</span><span>6</span><span>3</span><span>0</span>
                            </div>

                            {{-- Sample Bars --}}
                            <div class="w-full flex flex-col items-center group">
                                <div class="w-2/3 bg-[#bb1419] rounded-t-sm h-[40%] group-hover:opacity-80 transition duration-300"></div>
                                <span class="text-xs text-gray-400 mt-3">1 Jan</span>
                            </div>
                            <div class="w-full flex flex-col items-center group">
                                <div class="w-2/3 bg-[#bb1419] rounded-t-sm h-[80%] group-hover:opacity-80 transition"></div>
                                <span class="text-xs text-gray-400 mt-3">2 Jan</span>
                            </div>
                            <div class="w-full flex flex-col items-center group">
                                <div class="w-2/3 bg-[#bb1419] rounded-t-sm h-[60%] group-hover:opacity-80 transition"></div>
                                <span class="text-xs text-gray-400 mt-3">4 Jan</span>
                            </div>
                            <div class="w-full flex flex-col items-center group">
                                <div class="w-2/3 bg-[#bb1419] rounded-t-sm h-[95%] group-hover:opacity-80 transition"></div>
                                <span class="text-xs text-gray-400 mt-3">8 Jan</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Car Availability Check --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-lg text-gray-800 mb-6">Car Availability</h3>
                    <form action="#" class="space-y-4">
                        <select class="w-full border-gray-200 rounded-lg text-gray-600 focus:border-red-500 focus:ring-red-200 py-3">
                            <option>Select Car ID</option>
                            <option>P001 - Proton Saga</option>
                            <option>H002 - Honda City</option>
                        </select>
                        <input type="date" class="w-full border-gray-200 rounded-lg text-gray-600 focus:border-red-500 focus:ring-red-200 py-3">
                        <input type="date" class="w-full border-gray-200 rounded-lg text-gray-600 focus:border-red-500 focus:ring-red-200 py-3">
                        <button class="w-full bg-hasta-red hover:bg-red-700 text-white font-bold py-3 rounded-lg shadow-md transition duration-200">
                            CHECK
                        </button>
                    </form>
                </div>
            </div>

            {{-- 3. BOTTOM SECTION: Car Types & Status --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Car Type Progress --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-lg text-gray-800 mb-6">Car Type</h3>
                    <div class="space-y-6">
                        @foreach([['Proton Alza', 75], ['Sedan', 60], ['SUV', 30]] as $type)
                        <div>
                            <div class="flex justify-between text-sm font-medium text-gray-600 mb-1">
                                <span>{{ $type[0] }}</span>
                                <span>{{ $type[1] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="bg-black h-2.5 rounded-full" style="width: {{ $type[1] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status Legend --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-semibold text-lg text-gray-800 mb-6">Booking Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-gray-300 mr-3"></span>
                            <span class="text-gray-600 font-medium">Cancelled (25%)</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-hasta-red mr-3"></span>
                            <span class="text-gray-600 font-medium">Booked (60%)</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-yellow-400 mr-3"></span>
                            <span class="text-gray-600 font-medium">Pending (15%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-staff-layout>   
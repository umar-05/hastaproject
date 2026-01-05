<x-staff-layout>
    <div class="py-8 bg-[#f8f9fc] min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- PAGE TITLE --}}
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">Dashboard</h2>
                <p class="text-gray-500">Welcome back!</p>
            </div>

            {{-- 1. TOP METRICS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Pending Card --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="p-4 rounded-xl bg-orange-50 text-orange-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="ml-5">
                        <h4 class="text-2xl font-bold text-gray-800">{{ $pendingBookings }}</h4>
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-semibold">Pending Bookings</p>
                    </div>
                </div>

                {{-- Pickup Card --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="p-4 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <div class="ml-5">
                        <h4 class="text-2xl font-bold text-gray-800">{{ $pickupsToday }}</h4>
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-semibold">Pickups Today</p>
                    </div>
                </div>

                {{-- Returns Card --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
                    <div class="p-4 rounded-xl bg-green-50 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                    </div>
                    <div class="ml-5">
                        <h4 class="text-2xl font-bold text-gray-800">{{ $returnsToday }}</h4>
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-semibold">Returns Today</p>
                    </div>
                </div>
            </div>

            {{-- 2. MIDDLE SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                {{-- Chart Area --}}
                <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-xl text-gray-800 mb-8">Recent Booking Trends</h3>
                    <div class="relative h-64 w-full flex items-end justify-between px-2">
                        @foreach($chartData as $date => $count)
                            <div class="flex flex-col items-center w-full group">
                                <div class="relative w-12 bg-[#e31e24] rounded-t-lg transition-all duration-500 hover:bg-red-700" 
                                     style="height: {{ $count * 30 + 10 }}px;">
                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 text-xs font-bold text-gray-600 opacity-0 group-hover:opacity-100 transition">{{ $count }}</span>
                                </div>
                                <span class="text-xs font-medium text-gray-400 mt-4">{{ $date }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Availability Form --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-xl text-gray-800 mb-6">Check Availability</h3>
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-xl text-sm border border-green-100">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-xl text-sm border border-red-100">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('staff.fleet.check') }}" method="GET" class="space-y-5">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase mb-2 block">Select Vehicle</label>
                            <select name="car_id" required class="w-full border-gray-200 rounded-xl p-3 text-sm focus:ring-[#e31e24] focus:border-[#e31e24]">
                                <option value="">Choose a car...</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->carID }}">{{ $car->plateNumber }} - {{ $car->modelName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase mb-2 block">Pickup Date</label>
                                <input type="date" name="pickup" required class="w-full border-gray-200 rounded-xl p-3 text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase mb-2 block">Return Date</label>
                                <input type="date" name="return" required class="w-full border-gray-200 rounded-xl p-3 text-sm">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-[#1a1c2e] text-white font-bold py-4 rounded-xl hover:bg-black transition shadow-lg shadow-gray-200">
                            CHECK AVAILABILITY
                        </button>
                    </form>
                </div>
            </div>

            {{-- 3. FLEET DISTRIBUTION --}}
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-xl text-gray-800 mb-8">Fleet Distribution (by Brand)</h3>
                <div class="space-y-8">
                    @foreach($fleetDistribution as $brand => $percent)
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-700 font-bold">{{ $brand }}</span>
                            <span class="text-[#e31e24] font-black">{{ $percent }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3">
                            <div class="bg-[#e31e24] h-3 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-staff-layout>
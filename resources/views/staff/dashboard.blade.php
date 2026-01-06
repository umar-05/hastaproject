<x-staff-layout>
    <div class="py-8 bg-[#f8f9fc] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- TOP METRIC CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <a href="{{ route('staff.pickup-return') }}" class="group">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center transition hover:shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-blue-50 text-blue-600 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="text-gray-700 font-bold text-lg">Pickup today</span>
                        </div>
                        <span class="text-3xl font-black text-gray-900">{{ $pickupsToday }}</span>
                    </div>
                </a>

                <a href="{{ route('staff.pickup-return') }}" class="group">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center transition hover:shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-green-50 text-green-600 mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                            <span class="text-gray-700 font-bold text-lg">Return today</span>
                        </div>
                        <span class="text-3xl font-black text-gray-900">{{ $returnsToday }}</span>
                    </div>
                </a>
            </div>

            {{-- RECENT BOOKINGS --}}
            <div class="mb-10">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-black text-xl text-gray-800 uppercase tracking-tight">RECENT BOOKINGS</h3>
                    <a href="{{ route('staff.bookingmanagement') }}" class="text-gray-400 font-bold text-sm hover:text-red-500 transition">View All</a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @forelse($recentBookings as $index => $booking)
                        @if($index == 0)
                        {{-- Featured Card --}}
                        <div class="lg:col-span-2 bg-gradient-to-r from-blue-600 to-cyan-400 rounded-3xl p-8 relative overflow-hidden text-white shadow-lg">
                            <div class="relative z-10">
                                <h4 class="text-2xl font-bold mb-1 uppercase">{{ $booking->modelName }}</h4>
                                <p class="text-blue-50 font-medium mb-4 tracking-wider uppercase">{{ $booking->plateNumber }}</p>
                                <div class="text-4xl font-black mb-6">MYR {{ number_format($booking->totalPrice, 2) }}</div>
                                <span class="bg-white/20 px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest">{{ $booking->paymentStat ?? 'Paid' }}</span>
                            </div>
                        </div>
                        @else
                        {{-- Side Small Cards --}}
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                                <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11.05a2.5 2.5 0 014.9 0H17a1 1 0 001-1V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H4a1 1 0 00-1 1zm1 1h6.586L15 9.414V14H4V5z" /></svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800 leading-tight uppercase">{{ $booking->modelName }}</h5>
                                <p class="text-gray-400 text-xs font-bold mb-2 uppercase">{{ $booking->plateNumber }}</p>
                                <span class="text-[10px] font-black text-green-500 bg-green-50 px-2 py-0.5 rounded uppercase">PAID</span>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="lg:col-span-3 text-center py-10 bg-white rounded-3xl text-gray-400">No bookings found.</div>
                    @endforelse
                </div>
            </div>

            {{-- FOOTER SECTION: AVAILABILITY & DISTRIBUTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Car Availability Form --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-6">Check Availability</h3>
                    <form action="{{ route('staff.fleet.check') }}" method="GET" class="space-y-4">
                        <select name="car_id" required class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm font-bold text-gray-500 focus:ring-2 focus:ring-gray-100">
                            <option value="">Select Car</option>
                            @foreach($cars as $car)
                                <option value="{{ $car->carID }}">{{ $car->plateNumber }} - {{ $car->modelName }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="pickup" required class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm font-bold text-gray-500">
                        <input type="date" name="return" required class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm font-bold text-gray-500">
                        <button type="submit" class="w-full bg-[#1a1c2e] text-white font-black py-4 rounded-xl hover:bg-black transition tracking-widest shadow-lg shadow-gray-200 uppercase">Check</button>
                    </form>
                </div>

                {{-- Fleet Distribution --}}
                <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-8 tracking-tight">Fleet Distribution</h3>
                    <div class="space-y-8">
                        @foreach($fleetDistribution as $brand => $percent)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-400 font-black text-xs uppercase tracking-widest">{{ $brand }}</span>
                                <span class="font-black text-gray-900">{{ $percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full transition-all duration-700" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-staff-layout>
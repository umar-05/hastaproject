<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Vehicle Pickup & Return') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl sm:rounded-[30px] overflow-hidden border border-gray-100 min-h-[600px]" x-data="{ activeTab: 'pickup' }">

                <div class="flex border-b border-gray-200">
                    <button @click="activeTab = 'pickup'"
                            :class="{ 'border-b-4 border-hasta-red text-hasta-red bg-red-50': activeTab === 'pickup', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'pickup' }"
                            class="w-1/2 py-6 text-center font-bold text-xl transition duration-200 ease-in-out flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Scheduled Pickups
                    </button>

                    <button @click="activeTab = 'return'"
                            :class="{ 'border-b-4 border-hasta-red text-hasta-red bg-red-50': activeTab === 'return', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'return' }"
                            class="w-1/2 py-6 text-center font-bold text-xl transition duration-200 ease-in-out flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pending Returns
                    </button>
                </div>

                <div class="p-8 md:p-10 bg-gray-50/30">

                    <div x-show="activeTab === 'pickup'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        
                        <div class="flex justify-between items-center mb-6 px-2">
                            <h3 class="text-xl font-bold text-gray-800">Today's Pickups ({{ $todayPickups->count() }})</h3>
                            <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">Current Date: {{ date('d M Y') }}</span>
                        </div>
                        
                        @forelse($todayPickups as $booking)
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden mb-6">
                                
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-400 font-bold text-sm uppercase">Booking ID</span>
                                        <span class="font-mono font-bold text-gray-800 text-lg">#B-{{ str_pad($booking->booking_id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-yellow-200">
                                        {{ $booking->booking_stat }}
                                    </span>
                                </div>

                                <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
                                    
                                    <div class="lg:col-span-4 border-b lg:border-b-0 lg:border-r border-gray-100 pb-6 lg:pb-0 lg:pr-6">
                                        <h4 class="text-2xl font-extrabold text-gray-900 leading-tight">{{ $booking->fleet->model_name ?? 'Vehicle Unavailable' }}</h4>
                                        <div class="mt-2 flex items-center">
                                            <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded mr-2">{{ $booking->fleet->plate_number ?? 'N/A' }}</span>
                                            <span class="text-sm text-gray-500">{{ $booking->fleet->year ?? '' }} Model</span>
                                        </div>

                                        <div class="mt-8 flex items-center bg-blue-50 p-4 rounded-xl border border-blue-100">
                                            <div class="h-12 w-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-700 font-bold text-lg mr-4">
                                                {{ substr($booking->customer->name ?? 'Unknown', 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-xs text-blue-400 font-bold uppercase tracking-wider">Customer</p>
                                                <p class="font-bold text-gray-900 text-lg">{{ $booking->customer->name ?? 'Unknown Customer' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lg:col-span-5 flex flex-col justify-center space-y-6">
                                        <div class="flex items-start">
                                            <div class="flex flex-col items-center mr-4 mt-1">
                                                <div class="w-4 h-4 rounded-full border-[3px] border-green-500 bg-white"></div>
                                                <div class="w-0.5 h-12 bg-gray-200 my-1"></div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-green-600 font-bold uppercase mb-1">Pickup</p>
                                                <p class="font-bold text-gray-900 text-lg">
                                                    {{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }}, 
                                                    {{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}
                                                </p>
                                                <div class="flex items-center text-sm text-gray-500 mt-1">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $booking->pickup_loc }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="flex flex-col items-center mr-4 mt-1">
                                                <div class="w-4 h-4 rounded-full border-[3px] border-red-500 bg-white"></div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-red-600 font-bold uppercase mb-1">Return</p>
                                                <p class="font-bold text-gray-900 text-lg">
                                                    {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }},
                                                    {{ \Carbon\Carbon::parse($booking->return_time)->format('h:i A') }}
                                                </p>
                                                <div class="flex items-center text-sm text-gray-500 mt-1">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $booking->return_loc }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lg:col-span-3 bg-gray-50 rounded-xl p-5 border border-gray-100 flex flex-col justify-center">
                                        <p class="text-xs text-gray-400 font-bold uppercase mb-3 text-center">Payment Details</p>
                                        
                                        <div class="flex justify-between mb-2 text-sm">
                                            <span class="text-gray-600">Rental Price</span>
                                            <span class="font-bold text-gray-900">RM {{ $booking->total_price }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between mb-4 text-sm pb-4 border-b border-gray-200">
                                            <span class="text-gray-600">Deposit</span>
                                            <span class="font-bold text-gray-900">RM {{ $booking->deposit }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between items-end">
                                            <span class="text-sm font-bold text-hasta-red">Total</span>
                                            <span class="text-2xl font-extrabold text-hasta-red">RM {{ number_format($booking->total_price + $booking->deposit, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                                    <button class="bg-hasta-red hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transform active:scale-95 transition flex items-center">
                                        Process Pickup
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                                <div class="bg-gray-50 p-6 rounded-full mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-2">No Scheduled Pickups</h4>
                                <p class="text-gray-500 text-lg max-w-md mx-auto">
                                    There are no vehicles scheduled for pickup today.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <div x-show="activeTab === 'return'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        
                        <div class="flex justify-between items-center mb-6 px-2">
                            <h3 class="text-xl font-bold text-gray-800">Pending Returns ({{ $pendingReturns->count() }})</h3>
                        </div>

                        @forelse($pendingReturns as $booking)
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden mb-6">
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-400 font-bold text-sm uppercase">Booking ID</span>
                                        <span class="font-mono font-bold text-gray-800 text-lg">#B-{{ str_pad($booking->booking_id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-blue-200">
                                        Active / Return Due
                                    </span>
                                </div>
                                <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
                                    <div class="lg:col-span-4 border-b lg:border-b-0 lg:border-r border-gray-100 pb-6 lg:pb-0 lg:pr-6">
                                        <h4 class="text-2xl font-extrabold text-gray-900 leading-tight">{{ $booking->fleet->model_name ?? 'Vehicle Unavailable' }}</h4>
                                        <div class="mt-2 flex items-center">
                                            <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded mr-2">{{ $booking->fleet->plate_number ?? 'N/A' }}</span>
                                        </div>
                                        <div class="mt-8 flex items-center bg-blue-50 p-4 rounded-xl border border-blue-100">
                                            <div class="h-12 w-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-700 font-bold text-lg mr-4">
                                                {{ substr($booking->customer->name ?? '?', 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-xs text-blue-400 font-bold uppercase tracking-wider">Customer</p>
                                                <p class="font-bold text-gray-900 text-lg">{{ $booking->customer->name ?? 'Unknown' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lg:col-span-8 flex items-center justify-between">
                                         <div>
                                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Return Due</p>
                                            <p class="font-bold text-gray-900 text-2xl">
                                                {{ \Carbon\Carbon::parse($booking->return_date)->format('d M Y') }}
                                            </p>
                                            <p class="text-gray-500">{{ \Carbon\Carbon::parse($booking->return_time)->format('h:i A') }} at {{ $booking->return_loc }}</p>
                                         </div>
                                         <button class="bg-hasta-red hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition">
                                            Process Return
                                         </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                                <div class="bg-gray-50 p-6 rounded-full mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-2">No Returns Due</h4>
                                <p class="text-gray-500 text-lg max-w-md mx-auto">
                                    There are no vehicles scheduled for return at this moment.
                                </p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
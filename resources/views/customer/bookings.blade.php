<x-app-layout>
    {{-- Custom CSS for Animations --}}
    <style>
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
        
        /* Hide scrollbar for filter overflow on mobile */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- Standard Wrapper --}}
    <div class="bg-gray-50 min-h-screen">
        
        <main class="max-w-7xl mx-auto px-6 py-10">
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 animate-fade-up">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">My Bookings</h1>
                    <p class="text-gray-500">Manage your upcoming trips and view history.</p>
                </div>
                
                <a href="{{ route('vehicles.index') }}" class="mt-4 md:mt-0 group flex items-center font-bold text-hasta-red hover:text-red-800 transition">
                    <span class="bg-red-50 p-2 rounded-full mr-2 group-hover:bg-red-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </span>
                    Book New Vehicle
                </a>
            </div>

            @if(session('error'))
                <div class="animate-fade-up bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="animate-fade-up bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($bookings->isEmpty())
                <div class="animate-fade-up delay-100 flex flex-col items-center justify-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="bg-gray-50 p-6 rounded-full mb-6">
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No bookings found</h3>
                    <p class="text-gray-500 mb-8 text-center max-w-sm">Looks like you haven't started your journey with us yet.</p>
                    <a href="{{ route('vehicles.index') }}" class="bg-hasta-red hover:bg-red-700 text-white font-bold px-8 py-3 rounded-xl transition shadow-lg shadow-red-200">
                        Browse Vehicles
                    </a>
                </div>
            @else
                <div class="flex overflow-x-auto no-scrollbar gap-3 mb-8 animate-fade-up delay-100 pb-2">
                    <button class="filter-btn active px-5 py-2 rounded-full text-sm font-bold bg-gray-900 text-white shadow-md transition-all whitespace-nowrap" data-filter="all">
                        All Bookings
                    </button>
                    <button class="filter-btn px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all whitespace-nowrap" data-filter="pending">
                        Pending
                    </button>
                    <button class="filter-btn px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all whitespace-nowrap" data-filter="confirmed">
                        Approved
                    </button>
                    <button class="filter-btn px-5 py-2 rounded-full text-sm font-bold bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 transition-all whitespace-nowrap" data-filter="cancelled">
                        Cancelled
                    </button>
                </div>

                <div class="space-y-6 animate-fade-up delay-200" id="booking-list">
                    @foreach($bookings as $booking)
                        @php
                            // --- Vehicle Image Logic ---
                            $vehicleImage = 'default-car.png';
                            $vehicleName = 'Vehicle';
                            $vehicleType = 'Car';
                            
                            if ($booking->fleet) {
                                $fleet = $booking->fleet;
                                $vehicleName = $fleet->modelName . ($fleet->year ? ' ' . $fleet->year : '');
                                $modelName = strtolower($fleet->modelName);
                                $year = $fleet->year ?? 0;
                                
                                if (strpos($modelName, 'axia') !== false) {
                                    $vehicleImage = $year == 2024 ? 'axia-2024.png' : 'axia-2018.png';
                                    $vehicleType = 'Hatchback';
                                } elseif (strpos($modelName, 'bezza') !== false) {
                                    $vehicleImage = 'bezza-2018.png';
                                    $vehicleType = 'Sedan';
                                } elseif (strpos($modelName, 'myvi') !== false) {
                                    $vehicleImage = $year >= 2020 ? 'myvi-2020.png' : 'myvi-2015.png';
                                    $vehicleType = 'Hatchback';
                                } elseif (strpos($modelName, 'saga') !== false) {
                                    $vehicleImage = 'saga-2017.png';
                                    $vehicleType = 'Sedan';
                                } elseif (strpos($modelName, 'alza') !== false) {
                                    $vehicleImage = 'alza-2019.png';
                                    $vehicleType = 'MPV';
                                } elseif (strpos($modelName, 'aruz') !== false) {
                                    $vehicleImage = 'aruz-2020.png';
                                    $vehicleType = 'SUV';
                                } elseif (strpos($modelName, 'vellfire') !== false) {
                                    $vehicleImage = 'vellfire-2020.png';
                                    $vehicleType = 'MPV';
                                }
                            }
                            
                            // Determine Status Color
                            $status = strtolower($booking->bookingStat ?? 'unknown');
                            $statusColor = match($status) {
                                'confirmed', 'approved' => 'bg-green-100 text-green-700 border-green-200',
                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                'completed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                default => 'bg-gray-100 text-gray-600 border-gray-200'
                            };
                        @endphp

                        <div class="booking-card group relative bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300" 
                             data-status="{{ $status }}">
                            
                            <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">
                                
                                <div class="w-full md:w-48 h-32 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gray-200/50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <img src="{{ asset('images/' . $vehicleImage) }}" 
                                         alt="{{ $vehicleName }}" 
                                         class="w-full h-full object-contain p-2 relative z-10 transform group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <div class="flex-1 w-full">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">{{ $vehicleName }}</h3>
                                            <p class="text-sm text-gray-500 mb-2">{{ $vehicleType }}</p>

                                            {{-- --- NEW: INSPECTION FORM STATUS TAGS --- --}}
                                            <div class="flex flex-wrap gap-2 mt-1">
                                                @php
                                                    $hasPickup = !empty($booking->pickupForm);
                                                    $hasReturn = !empty($booking->returnForm);
                                                @endphp

                                                @if($hasPickup && $hasReturn)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                                        Forms: Completed
                                                    </span>
                                                @elseif($hasPickup || $hasReturn)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-purple-50 text-purple-600 border border-purple-100">
                                                        Forms: Partial
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-400 border border-slate-200">
                                                        Forms: Pending
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusColor }}">
                                            {{ ucfirst($booking->bookingStat ?? 'unknown') }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 p-1.5 bg-gray-100 rounded-md text-gray-500">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Pick Up</p>
                                                <p class="font-bold text-gray-800">{{ optional($booking->pickupDate)->format('d M Y') ?? (\Carbon\Carbon::parse($booking->pickupDate ?? $booking->pickup_date)->format('d M Y')) }}</p>
                                                <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $booking->pickupLoc ?? $booking->pickup_loc }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 p-1.5 bg-gray-100 rounded-md text-gray-500">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Return</p>
                                                <p class="font-bold text-gray-800">{{ optional($booking->returnDate)->format('d M Y') ?? (\Carbon\Carbon::parse($booking->returnDate ?? $booking->return_date)->format('d M Y')) }}</p>
                                                <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $booking->returnLoc ?? $booking->return_loc }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 p-1.5 bg-red-50 rounded-md text-hasta-red">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total</p>
                                                <p class="font-bold text-hasta-red text-lg">RM{{ number_format($booking->totalPrice ?? $booking->total_price ?? 0, 2) }}</p>
                                                
                                                {{-- 
                                                    CONDITION: ONLY Show the text "Pending" (or payment status) 
                                                    if the Booking Status itself is 'pending'.
                                                --}}
                                                @if($status === 'pending')
                                                    <p class="text-xs text-gray-500">{{ ucfirst($booking->payment_status ?? 'pending') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-gray-100">
                                    <a href="{{ route('bookings.show', $booking->bookingID ?? $booking->booking_id) }}" 
                                       class="bg-gray-900 text-white hover:bg-hasta-red font-bold px-6 py-2.5 rounded-xl transition text-sm text-center shadow-md whitespace-nowrap">
                                        View Details
                                    </a>
                                    
                                    @if($status !== 'completed' && $status !== 'cancelled')
                                        <form action="{{ route('bookings.cancel', $booking->bookingID) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                                              class="w-full">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full border border-gray-200 text-gray-500 hover:border-red-500 hover:text-red-600 hover:bg-red-50 font-bold px-6 py-2.5 rounded-xl transition text-sm whitespace-nowrap">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
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

    {{-- Filter JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const bookingCards = document.querySelectorAll('.booking-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.dataset.filter;

                    // Update button styles
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-gray-900', 'text-white', 'shadow-md');
                        btn.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200', 'hover:bg-gray-50');
                    });
                    button.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200', 'hover:bg-gray-50');
                    button.classList.add('bg-gray-900', 'text-white', 'shadow-md');

                    // Filter Logic
                    bookingCards.forEach(card => {
                        const status = card.dataset.status;
                        
                        if (filter === 'all' || status === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
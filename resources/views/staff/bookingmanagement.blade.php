<x-staff-layout>
    <div class="py-8 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- PAGE HEADER --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Bookings Management</h1>
                <p class="text-gray-500 text-sm">View and manage all rental bookings</p>
            </div>

            {{-- SEARCH & ACTION BAR --}}
            <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
                <form action="{{ route('staff.bookingmanagement') }}" method="GET" class="flex items-center gap-3 flex-1 max-w-4xl">
                    {{-- 1. Search Input --}}
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search ID, Plate, Matric, or Name..." 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    {{-- 2. Status Filter Dropdown --}}
                    <div class="relative min-w-[160px]">
                        <select name="status" 
                                onchange="this.form.submit()" 
                                class="block w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 sm:text-sm appearance-none bg-white">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        {{-- Custom Arrow for Select --}}
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>

                    @if(request()->filled('search') || request()->filled('status'))
                        <a href="{{ route('staff.bookingmanagement') }}" class="text-sm text-gray-500 hover:text-red-500 transition whitespace-nowrap">
                            Clear All
                        </a>
                    @endif
                </form>

                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            {{-- STATUS METRIC CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white p-5 rounded-xl border-l-4 border-blue-500 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $totalBookings ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                </div>
                
                <div class="bg-white p-5 rounded-xl border-l-4 border-green-500 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $confirmedCount ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Confirmed</p>
                </div>
                
                <div class="bg-white p-5 rounded-xl border-l-4 border-yellow-500 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingCount ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                </div>
                
                <div class="bg-white p-5 rounded-xl border-l-4 border-gray-400 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $completedCount ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                </div>

                <div class="bg-white p-5 rounded-xl border-l-4 border-red-500 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $cancelledCount ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Cancelled</p>
                </div>
            </div>

            {{-- BOOKINGS TABLE --}}
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl">
                <div class="max-h-[60vh] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Booking ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Car</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pickup</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Return</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                {{-- Removed Verification Column Header --}}
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($bookings) && $bookings->count())
                                @foreach($bookings as $booking)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->bookingID }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $booking->customer->name ?? $booking->matricNum }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $booking->fleet ? $booking->fleet->modelName : 'N/A' }} -<br><span class="text-gray-400">{{ $booking->fleet->plateNumber ?? $booking->plateNumber }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->pickupDate ?? $booking->pickup_date)->format('Y-m-d') }}<br><span class="font-bold">{{ \Carbon\Carbon::parse($booking->pickupDate ?? $booking->pickup_date)->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->returnDate ?? $booking->return_date)->format('Y-m-d') }}<br><span class="font-bold">{{ \Carbon\Carbon::parse($booking->returnDate ?? $booking->return_date)->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">RM {{ number_format($booking->totalPrice ?? $booking->total_price ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($booking->bookingStat ?? $booking->booking_stat) === 'confirmed' ? 'bg-green-100 text-green-800' : (($booking->bookingStat ?? $booking->booking_stat) === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($booking->bookingStat ?? $booking->booking_stat ?? 'unknown') }}
                                            </span>
                                        </td>
                                        {{-- Removed Verification Column Data --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center gap-3">
                                                {{-- 1. VIEW DETAILS --}}
                                                <a href="{{ route('bookings.show', $booking->bookingID) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100" title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                                                </a>

                                                {{-- 2. APPROVE BOOKING --}}
                                                @if(($booking->bookingStat ?? $booking->booking_stat) === 'pending')
                                                    <form action="{{ route('staff.fleet.bookings.approve', $booking->bookingID) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" onclick="return confirm('Approve this booking?')" class="text-gray-400 hover:text-green-600 transition" title="Approve Booking">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- 3. CANCEL BOOKING --}}
                                                @if(($booking->bookingStat ?? $booking->booking_stat) !== 'cancelled' && ($booking->bookingStat ?? $booking->booking_stat) !== 'completed')
                                                    <form action="{{ route('staff.fleet.bookings.cancel', $booking->bookingID) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" onclick="return confirm('Cancel this booking?')" class="text-gray-400 hover:text-red-600 transition" title="Cancel booking">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    {{-- Reduced colspan from 9 to 8 since verification column is gone --}}
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No bookings found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            @if(isset($bookings))
                <div class="mt-4 px-6 py-4 bg-white border-t">
                    {{ $bookings->appends(['search' => request('search')])->links() }}
                </div>
            @endif
        </div>
    </div>
</x-staff-layout>
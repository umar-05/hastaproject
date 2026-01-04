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
                <div class="flex items-center gap-3 flex-1 max-w-2xl">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" placeholder="Search bookings..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>
                    <button class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
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
                <div class="bg-white p-5 rounded-xl border-l-4 border-purple-500 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingVerificationCount ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Pending Verification</p>
                </div>
                <div class="bg-white p-5 rounded-xl border-l-4 border-gray-400 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $completedCount ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Completed</p>
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
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Verification</th>
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
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ ($booking->bookingStat ?? $booking->booking_stat) === 'confirmed' ? 'bg-green-100 text-green-800' : (($booking->bookingStat ?? $booking->booking_stat) === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($booking->bookingStat ?? $booking->booking_stat ?? 'unknown') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                        <button class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-bold inline-flex items-center">Pickup</button>
                                        <button class="bg-gray-100 text-gray-400 px-3 py-1 rounded text-xs font-bold inline-flex items-center">Return</button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('bookings.show', $booking->bookingID) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg></a>
                                            <button class="text-gray-400 hover:text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                            <button class="text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">No bookings found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                </div>
            </div>
            @if(isset($bookings))
                <div class="mt-4 px-6 py-4 bg-white border-t">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-staff-layout>
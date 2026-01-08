@extends('staff.fleet.layout')

@section('tab-content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Booking History</h3>
        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">
            {{ $bookings->count() }} Records
        </span>
    </div>
    
    @if($bookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-mono font-bold text-gray-500">#{{ $booking->bookingID }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $booking->customer->name ?? 'Unknown Customer' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $booking->customer->matricNum ?? $booking->matricNum ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M Y') }}
                                <span class="text-gray-300 mx-1">➜</span> 
                                {{ \Carbon\Carbon::parse($booking->returnDate)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($booking->pickupTime)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($booking->returnTime)->format('h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = match(strtolower($booking->bookingStat)) {
                                    'approved', 'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'completed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $statusClasses }}">
                                {{ ucfirst($booking->bookingStat) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-gray-900">RM {{ number_format($booking->totalPrice, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            {{-- CHANGE: Button trigger for Modal --}}
                            <button onclick="openBookingModal('{{ route('bookings.show', $booking->bookingID) }}')" 
                                    class="text-indigo-600 hover:text-indigo-900 font-bold text-xs hover:underline bg-transparent border-0 cursor-pointer">
                                View
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-gray-900 font-bold text-lg">No Bookings Found</h3>
            <p class="text-gray-500 text-sm mt-1">This vehicle has not been booked yet.</p>
        </div>
    @endif
</div>

{{-- MODAL STRUCTURE --}}
<div id="bookingModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeBookingModal()"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl border border-gray-200">
                
                {{-- Modal Header --}}
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 z-20">
                    <h3 class="text-lg font-bold text-gray-800">Booking Details</h3>
                    <button type="button" onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 transition bg-gray-100 hover:bg-gray-200 rounded-full p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Content Area --}}
                <div class="bg-white px-4 pb-4 pt-5 sm:p-8 min-h-[400px]" id="modalContent">
                    {{-- Loading Spinner --}}
                    <div class="flex flex-col justify-center items-center h-64 text-gray-500">
                        <svg class="animate-spin mb-3 h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="font-medium animate-pulse">Loading booking details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openBookingModal(url) {
        const modal = document.getElementById('bookingModal');
        const content = document.getElementById('modalContent');
        
        // Show Modal
        modal.classList.remove('hidden');

        // Reset to Loading State
        content.innerHTML = `
            <div class="flex flex-col justify-center items-center h-64 text-gray-500">
                <svg class="animate-spin mb-3 h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="font-medium animate-pulse">Loading booking details...</p>
            </div>
        `;

        // Fetch Content
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            credentials: 'include'
        })
        .then(async response => {
            if (response.status === 401 || response.status === 419) {
                return '<div class="text-center py-12"><p class="text-red-500 font-bold">Session expired.</p><p class="text-gray-500 text-sm mt-2">Please refresh the page and login again.</p></div>';
            }
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            content.innerHTML = html;
        })
        .catch(error => {
            content.innerHTML = '<div class="text-center py-12 text-red-500">Error loading details. Please try again later.</div>';
            console.error('Error:', error);
        });
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }

    // Close on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeBookingModal();
        }
    });
</script>
@endsection
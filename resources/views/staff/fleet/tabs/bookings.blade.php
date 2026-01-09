@extends('staff.fleet.layout')

@section('tab-content')
<div class="animate-fade-in-up relative">
    
    {{-- Decorative Background Blob --}}
    <div class="absolute top-0 left-0 w-64 h-64 bg-red-50 rounded-full blur-3xl -ml-20 -mt-20 opacity-50 pointer-events-none"></div>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden relative z-10">
        
        {{-- Header Section --}}
        <div class="px-8 py-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-white via-transparent to-red-50/30">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-900 text-white rounded-2xl shadow-lg shadow-gray-300/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Booking History</h3>
                    <p class="text-sm text-gray-500 font-medium">Track usage and trip records for this vehicle.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-4 py-2 bg-red-100 text-red-700 rounded-xl text-sm font-bold border border-red-200 shadow-sm">
                    {{ $bookings->count() }} Total Trips
                </span>
            </div>
        </div>
        
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50/50 text-left">
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Booking ID</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Customer</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Schedule</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Amount</th>
                            <th class="px-8 py-5 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                        <tr class="group hover:bg-red-50/30 transition-all duration-300">
                            {{-- ID --}}
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-mono font-bold border border-gray-200 group-hover:border-red-200 group-hover:bg-white transition-colors">
                                    #{{ $booking->bookingID }}
                                </span>
                            </td>

                            {{-- Customer --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-bold shadow-sm">
                                        {{ substr($booking->customer->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 leading-tight">
                                            {{ $booking->customer->name ?? 'Unknown Customer' }}
                                        </div>
                                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-0.5">
                                            {{ $booking->customer->matricNum ?? $booking->matricNum ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Duration --}}
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2 text-sm font-bold text-gray-700">
                                        <span>{{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M') }}</span>
                                        <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        <span>{{ \Carbon\Carbon::parse($booking->returnDate)->format('d M') }}</span>
                                    </div>
                                    <div class="text-xs text-gray-400 font-medium">
                                        {{ \Carbon\Carbon::parse($booking->pickupTime)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($booking->returnTime)->format('h:i A') }}
                                    </div>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-8 py-6 whitespace-nowrap">
                                @php
                                    $statusStyles = match(strtolower($booking->bookingStat)) {
                                        'approved', 'confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                        'active' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        default => 'bg-gray-50 text-gray-500 border-gray-100',
                                    };
                                @endphp
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg uppercase tracking-wide border {{ $statusStyles }}">
                                    {{ ucfirst($booking->bookingStat) }}
                                </span>
                            </td>

                            {{-- Total --}}
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="text-sm font-black text-gray-900">RM {{ number_format($booking->totalPrice, 2) }}</div>
                            </td>

                            {{-- Action --}}
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <button onclick="openBookingModal('{{ route('bookings.show', $booking->bookingID) }}')" 
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-200 hover:bg-red-50 hover:shadow-lg hover:shadow-red-100 transition-all duration-300 group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-24 text-center flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 border-2 border-dashed border-gray-200">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900">No Booking History</h3>
                <p class="text-gray-500 mt-2 max-w-sm">This vehicle hasn't been booked yet. Once trips are completed, they will appear here.</p>
            </div>
        @endif
    </div>
</div>

{{-- MODAL STRUCTURE --}}
<div id="bookingModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeBookingModal()"></div>
    
    {{-- Modal Container --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border border-gray-100">
                
                {{-- Modal Header --}}
                <div class="bg-white px-8 py-6 border-b border-gray-100 flex justify-between items-center sticky top-0 z-20">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">Booking Details</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Full transaction record</p>
                    </div>
                    <button type="button" onclick="closeBookingModal()" class="group bg-gray-50 hover:bg-red-50 p-2 rounded-xl transition-colors border border-transparent hover:border-red-100">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Content Area --}}
                <div class="bg-gray-50/30 px-8 py-8 min-h-[400px]" id="modalContent">
                    {{-- Loading Spinner --}}
                    <div class="flex flex-col justify-center items-center h-64 text-gray-400">
                        <div class="relative w-16 h-16 mb-4">
                            <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-red-600 rounded-full border-t-transparent animate-spin"></div>
                        </div>
                        <p class="font-bold text-sm uppercase tracking-widest animate-pulse">Loading data...</p>
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
        
        modal.classList.remove('hidden');

        // Reset to Loading State
        content.innerHTML = `
            <div class="flex flex-col justify-center items-center h-64 text-gray-400">
                <div class="relative w-16 h-16 mb-4">
                    <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-red-600 rounded-full border-t-transparent animate-spin"></div>
                </div>
                <p class="font-bold text-sm uppercase tracking-widest animate-pulse">Loading data...</p>
            </div>
        `;

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
                return '<div class="text-center py-12"><p class="text-red-600 font-bold text-lg">Session expired.</p><p class="text-gray-500 mt-2">Please refresh and login again.</p></div>';
            }
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            content.innerHTML = html;
        })
        .catch(error => {
            content.innerHTML = '<div class="text-center py-12"><p class="text-red-500 font-bold">Error loading details.</p><button onclick="closeBookingModal()" class="mt-4 text-sm underline text-gray-500">Close</button></div>';
            console.error('Error:', error);
        });
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") closeBookingModal();
    });
</script>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
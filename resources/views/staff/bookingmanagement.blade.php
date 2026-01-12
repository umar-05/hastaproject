<x-staff-layout>
    {{-- Add SheetJS Library --}}
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

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
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Plate, Matric, or Name..." class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>

                    <div class="relative min-w-[160px]">
                        <select name="status" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 sm:text-sm appearance-none bg-white">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </form>

                <div class="flex items-center gap-3">
                    <button onclick="exportBookingsToExcel()" class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            {{-- STATUS METRICS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white p-5 rounded-xl border-l-4 border-blue-500 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $totalBookings ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                </div>
                <div class="bg-white p-5 rounded-xl border-l-4 border-green-500 shadow-sm">
                    <p class="text-3xl font-bold text-gray-800">{{ $approvedCount ?? 0 }}</p>
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
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Car</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pickup</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Return</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->customer->name ?? $booking->matricNum }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $booking->fleet->modelName ?? 'N/A' }}
                                        <br>
                                        <span class="text-xs text-gray-400">{{ $booking->fleet->plateNumber ?? $booking->plateNumber }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->pickupDate)->format('Y-m-d') }}
                                        <br>
                                        <span class="font-bold">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($booking->returnDate)->format('Y-m-d') }}
                                        <br>
                                        <span class="font-bold">{{ \Carbon\Carbon::parse($booking->returnDate)->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        RM {{ number_format($booking->totalPrice, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = strtolower($booking->bookingStat);
                                            $badgeClass = match($status) {
                                                'approved', 'confirmed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'completed' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-2">
                                            
                                            {{-- 1. VIEW DETAILS (MODAL) --}}
                                            <button onclick="openBookingModal('{{ route('bookings.show', $booking->bookingID) }}')" 
                                                    class="p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition" 
                                                    title="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </button>

                                            {{-- 2. APPROVE (Only if pending) --}}
                                            @if($status === 'pending')
                                                <form action="{{ route('staff.fleet.bookings.approve', $booking->bookingID) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Approve this booking?')" class="p-1.5 hover:bg-green-50 text-gray-400 hover:text-green-600 rounded-md transition" title="Approve">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- 3. CANCEL (Unless already cancelled/completed) --}}
                                            @if(!in_array($status, ['cancelled', 'completed']))
                                                <form action="{{ route('staff.fleet.bookings.cancel', $booking->bookingID) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Cancel this booking?')" class="p-1.5 hover:bg-red-50 text-gray-400 hover:text-red-600 rounded-md transition" title="Cancel">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <span class="text-lg font-medium">No bookings found</span>
                                            <p class="text-sm text-gray-400 mt-1">Try adjusting your search filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(isset($bookings) && $bookings->count())
            <div class="mt-4 px-6 py-4 bg-white border-t rounded-xl shadow-sm">
                {{ $bookings->appends(['search' => request('search'), 'status' => request('status')])->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- MODAL --}}
    <div id="bookingModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeBookingModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl border border-gray-200">
                    <div class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 z-20">
                        <h3 class="text-lg font-bold text-gray-800">Booking Details</h3>
                        <button type="button" onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 transition bg-gray-100 hover:bg-gray-200 rounded-full p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-8 min-h-[400px]" id="modalContent">
                        {{-- Content loaded via JS --}}
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
            content.innerHTML = `
                <div class="flex flex-col justify-center items-center h-64 text-gray-500">
                    <svg class="animate-spin mb-3 h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="font-medium animate-pulse">Loading booking details...</p>
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
                    return '<div class="text-center py-12"><p class="text-red-500 font-bold">Session expired.</p><p class="text-gray-500 text-sm mt-2">Please refresh the page and login again.</p></div>';
                }
                if (!response.ok) throw new Error('Network response was not ok');
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

        // Excel Export Function
        function exportBookingsToExcel() {
            // Get statistics
            const totalBookings = {{ $totalBookings ?? 0 }};
            const approvedCount = {{ $approvedCount ?? 0 }};
            const pendingCount = {{ $pendingCount ?? 0 }};
            const completedCount = {{ $completedCount ?? 0 }};
            const cancelledCount = {{ $cancelledCount ?? 0 }};
            
            // Get bookings data
            const bookingsData = @json($bookings->items());
            
            // Create workbook
            const wb = XLSX.utils.book_new();
            
            // Sheet 1: Summary
            const summaryData = [
                ['Bookings Management Report'],
                ['Generated on:', new Date().toLocaleString('en-MY', { timeZone: 'Asia/Kuala_Lumpur' })],
                ['Search Filter:', '{{ request("search") ?? "All" }}'],
                ['Status Filter:', '{{ request("status") ? ucfirst(request("status")) : "All Status" }}'],
                [],
                ['Metric', 'Count'],
                ['Total Bookings', totalBookings],
                ['Confirmed', approvedCount],
                ['Pending', pendingCount],
                ['Completed', completedCount],
                ['Cancelled', cancelledCount]
            ];
            
            const ws1 = XLSX.utils.aoa_to_sheet(summaryData);
            ws1['!cols'] = [
                { wch: 20 },
                { wch: 25 }
            ];
            XLSX.utils.book_append_sheet(wb, ws1, 'Summary');
            
            // Sheet 2: All Bookings
            const bookingsTableData = [
                ['Booking ID', 'Customer', 'Matric Number', 'Car Model', 'Plate Number', 'Pickup Date', 'Pickup Time', 'Return Date', 'Return Time', 'Amount (RM)', 'Status']
            ];
            
            bookingsData.forEach(booking => {
                const pickupDate = new Date(booking.pickupDate);
                const returnDate = new Date(booking.returnDate);
                
                bookingsTableData.push([
                    booking.bookingID || '',
                    booking.customer?.name || booking.matricNum || '',
                    booking.matricNum || '',
                    booking.fleet?.modelName || 'N/A',
                    booking.fleet?.plateNumber || booking.plateNumber || '',
                    pickupDate.toLocaleDateString('en-MY'),
                    pickupDate.toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit' }),
                    returnDate.toLocaleDateString('en-MY'),
                    returnDate.toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit' }),
                    parseFloat(booking.totalPrice).toFixed(2),
                    (booking.bookingStat || '').charAt(0).toUpperCase() + (booking.bookingStat || '').slice(1).toLowerCase()
                ]);
            });
            
            const ws2 = XLSX.utils.aoa_to_sheet(bookingsTableData);
            ws2['!cols'] = [
                { wch: 12 },  // Booking ID
                { wch: 20 },  // Customer
                { wch: 15 },  // Matric
                { wch: 18 },  // Car Model
                { wch: 12 },  // Plate Number
                { wch: 12 },  // Pickup Date
                { wch: 10 },  // Pickup Time
                { wch: 12 },  // Return Date
                { wch: 10 },  // Return Time
                { wch: 12 },  // Amount
                { wch: 12 }   // Status
            ];
            XLSX.utils.book_append_sheet(wb, ws2, 'All Bookings');
            
            // Sheet 3: By Status
            const statusGroups = {
                'Approved': [],
                'Pending': [],
                'Completed': [],
                'Cancelled': []
            };
            
            bookingsData.forEach(booking => {
                const status = (booking.bookingStat || '').charAt(0).toUpperCase() + (booking.bookingStat || '').slice(1).toLowerCase();
                if (status === 'Confirmed') {
                    statusGroups['Approved'].push(booking);
                } else if (statusGroups[status]) {
                    statusGroups[status].push(booking);
                }
            });
            
            Object.entries(statusGroups).forEach(([status, bookings]) => {
                if (bookings.length > 0) {
                    const statusData = [
                        [`${status} Bookings`],
                        [],
                        ['Booking ID', 'Customer', 'Car Model', 'Pickup Date', 'Return Date', 'Amount (RM)']
                    ];
                    
                    bookings.forEach(booking => {
                        statusData.push([
                            booking.bookingID || '',
                            booking.customer?.name || booking.matricNum || '',
                            booking.fleet?.modelName || 'N/A',
                            new Date(booking.pickupDate).toLocaleDateString('en-MY'),
                            new Date(booking.returnDate).toLocaleDateString('en-MY'),
                            parseFloat(booking.totalPrice).toFixed(2)
                        ]);
                    });
                    
                    const ws = XLSX.utils.aoa_to_sheet(statusData);
                    ws['!cols'] = [
                        { wch: 12 },
                        { wch: 20 },
                        { wch: 18 },
                        { wch: 12 },
                        { wch: 12 },
                        { wch: 12 }
                    ];
                    XLSX.utils.book_append_sheet(wb, ws, status);
                }
            });
            
            // Generate filename with current date
            const today = new Date();
            const filename = `Bookings_Management_${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}.xlsx`;
            
            // Save file
            XLSX.writeFile(wb, filename);
            
            // Show success message
            alert('Excel file downloaded successfully!');
        }
    </script>
</x-staff-layout>
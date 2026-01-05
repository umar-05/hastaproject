<x-staff-layout>
    {{-- Page Header --}}
    <div class="mb-8">
        <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Vehicle Inspection</h2>
        <p class="text-gray-500 text-sm mt-1">Pickup and return inspection records</p>
    </div>

    {{-- 1. STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Card 1: Total Inspections --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase">Total Tasks</p>
                <h4 class="text-2xl font-bold text-gray-800">{{ $todayPickups->count() + $pendingReturns->count() }}</h4>
                <p class="text-[10px] text-gray-400">All active</p>
            </div>
        </div>

        {{-- Card 2: Pickup Inspections --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase">Pickups Due</p>
                <h4 class="text-2xl font-bold text-gray-800">{{ $todayPickups->count() }}</h4>
                <p class="text-[10px] text-gray-400">Ready for rental</p>
            </div>
        </div>

        {{-- Card 3: Return Inspections --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase">Returns Due</p>
                <h4 class="text-2xl font-bold text-gray-800">{{ $pendingReturns->count() }}</h4>
                <p class="text-[10px] text-gray-400">Completed rentals</p>
            </div>
        </div>

        {{-- Card 4: Active Inspectors --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase">Active Staff</p>
                <h4 class="text-2xl font-bold text-gray-800">3</h4>
                <p class="text-[10px] text-gray-400">Staff members</p>
            </div>
        </div>
    </div>

    {{-- 2. ACTIONS TOOLBAR --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        
        <div class="flex items-center gap-4 w-full sm:w-auto">
            {{-- Search Bar --}}
            <div class="relative w-full sm:w-80">
                <input type="text" placeholder="Search by ID, vehicle, or customer..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-red-500 focus:ring-red-200 transition shadow-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            {{-- Filter Dropdown --}}
            <select class="border border-gray-200 rounded-lg text-sm text-gray-600 py-2.5 px-4 focus:border-red-500 focus:ring-red-200 shadow-sm cursor-pointer">
                <option>All Types</option>
                <option>Pickup</option>
                <option>Return</option>
            </select>
        </div>

    </div>

    {{-- 3. INSPECTION TABLE --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Inspection ID</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Vehicle</th>
                        <th class="px-6 py-4">Customer / Mileage</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Inspector</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    
                    {{-- Loop Pickups --}}
                    @foreach($todayPickups as $booking)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-700 font-medium">INS-{{ date('Y') }}-{{ $booking->booking_id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-green-200">
                                Pickup
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800">{{ $booking->fleet->modelName }}</span>
                                <span class="text-xs text-gray-500">{{ $booking->fleet->plateNumber }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex flex-col">
                                <span class="font-medium text-gray-800">{{ $booking->customer->name }}</span>
                                <span class="text-xs text-gray-400 flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Initial Check
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('Y-m-d') }}</span>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600 mr-2">
                                    {{ substr(Auth::guard('staff')->user()->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-sm text-gray-600">{{ Auth::guard('staff')->user()->name ?? 'Staff' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3 opacity-60 group-hover:opacity-100 transition">
                                <button class="p-1 hover:text-blue-600 hover:bg-blue-50 rounded" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button class="p-1 hover:text-green-600 hover:bg-green-50 rounded" title="Process Pickup">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Loop Returns --}}
                    @foreach($pendingReturns as $booking)
                    <tr class="hover:bg-gray-50 transition group border-l-4 border-l-transparent hover:border-l-orange-500">
                        <td class="px-6 py-4">
                            <span class="font-mono text-gray-700 font-medium">INS-{{ date('Y') }}-{{ $booking->booking_id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-orange-200">
                                Return
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800">{{ $booking->fleet->model_name }}</span>
                                <span class="text-xs text-gray-500">{{ $booking->fleet->plate_number }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex flex-col">
                                <span class="font-medium text-gray-800">{{ $booking->customer->name }}</span>
                                <span class="text-xs text-gray-400 flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Wait for Check
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->return_date)->format('Y-m-d') }}</span>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->return_time)->format('h:i A') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600 mr-2">
                                    {{ substr(Auth::guard('staff')->user()->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-sm text-gray-600">{{ Auth::guard('staff')->user()->name ?? 'Staff' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3 opacity-60 group-hover:opacity-100 transition">
                                <button class="p-1 hover:text-blue-600 hover:bg-blue-50 rounded" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button class="p-1 hover:text-orange-600 hover:bg-orange-50 rounded" title="Process Return">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($todayPickups->isEmpty() && $pendingReturns->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p>No active pickup or return tasks found.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        {{-- Pagination (Static for UI) --}}
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Showing 1 to {{ $todayPickups->count() + $pendingReturns->count() }} of {{ $todayPickups->count() + $pendingReturns->count() }} results</span>
            <div class="flex space-x-1">
                <button class="px-3 py-1 rounded border border-gray-200 bg-white text-xs text-gray-600 hover:bg-gray-50 disabled:opacity-50">Previous</button>
                <button class="px-3 py-1 rounded border border-gray-200 bg-[#bb1419] text-xs text-white">1</button>
                <button class="px-3 py-1 rounded border border-gray-200 bg-white text-xs text-gray-600 hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>
</x-staff-layout>
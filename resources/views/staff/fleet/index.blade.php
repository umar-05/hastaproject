<x-staff-layout>
    {{-- Main Container --}}
    <div class="p-6 bg-gray-50 min-h-screen">

        {{-- Success Message --}}
        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('status') }}
            </div>
        @endif

        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Fleet Management</h1>
            <p class="text-gray-600 mt-1">Manage your rental vehicle fleet efficiently.</p>
        </div>

        {{-- Top Stats Banners (Dynamic Data) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card 1: Total Vehicles --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition-shadow hover:shadow-md">
                <div class="p-4 bg-blue-50 rounded-full text-blue-600 mr-5">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Total Vehicles</p>
                </div>
            </div>

            {{-- Card 2: Available --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition-shadow hover:shadow-md">
                <div class="p-4 bg-green-50 rounded-full text-green-600 mr-5">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $stats['available'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Available</p>
                </div>
            </div>

            {{-- Card 3: Rented --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition-shadow hover:shadow-md">
                <div class="p-4 bg-red-50 rounded-full text-red-600 mr-5">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $stats['rented'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Rented</p>
                </div>
            </div>

            {{-- Card 4: Maintenance --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center transition-shadow hover:shadow-md">
                <div class="p-4 bg-yellow-50 rounded-full text-yellow-600 mr-5">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $stats['maintenance'] ?? 0 }}</p>
                    <p class="text-sm font-medium text-gray-500">Maintenance</p>
                </div>
            </div>
        </div>


        {{-- Filters and Action Toolbar --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 space-y-4 md:space-y-0">
            {{-- Functional Filter Buttons using Links --}}
            @php
                $currentFilter = request('filter', 'all');
                $baseClass = "px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-all";
                $activeClass = "bg-gray-900 text-white hover:bg-gray-800";
                $inactiveClass = "bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300";
            @endphp

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('staff.fleet.index', ['filter' => 'all']) }}" 
                   class="{{ $baseClass }} {{ $currentFilter === 'all' ? $activeClass : $inactiveClass }}">
                    All Vehicles
                </a>
                <a href="{{ route('staff.fleet.index', ['filter' => 'available']) }}" 
                   class="{{ $baseClass }} {{ $currentFilter === 'available' ? $activeClass : $inactiveClass }}">
                    Available
                </a>
                <a href="{{ route('staff.fleet.index', ['filter' => 'rented']) }}" 
                   class="{{ $baseClass }} {{ $currentFilter === 'rented' ? $activeClass : $inactiveClass }}">
                    Rented
                </a>
                <a href="{{ route('staff.fleet.index', ['filter' => 'maintenance']) }}" 
                   class="{{ $baseClass }} {{ $currentFilter === 'maintenance' ? $activeClass : $inactiveClass }}">
                    Maintenance
                </a>
            </div>

            {{-- Search Form and Add Button --}}
            <div class="flex items-center space-x-4 w-full md:w-auto">
                <form action="{{ route('staff.fleet.index') }}" method="GET" class="relative w-full md:w-72">
                    {{-- Preserve filter when searching --}}
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vehicles..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm transition-all">
                </form>

                <a href="{{ route('staff.fleet.create') }}" class="flex items-center justify-center px-5 py-2.5 bg-red-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all active:transform active:scale-95 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Vehicle
                </a>
            </div>
        </div>

        {{-- Vehicle Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse ($vehicles as $vehicle)
                @php
                    // Helper logic for status styling
                    $statusColor = match(strtolower($vehicle->status)) {
                        'available' => 'bg-green-100 text-green-800',
                        'rented' => 'bg-red-100 text-red-800',
                        'maintenance' => 'bg-yellow-100 text-yellow-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    {{-- Image Container --}}
                    <div class="relative h-56 overflow-hidden bg-gray-100">
                        {{-- Status Badge --}}
                        <span class="absolute top-4 right-4 px-3 py-1 {{ $statusColor }} text-xs font-bold rounded-full z-10 shadow-sm capitalize">
                            {{ $vehicle->status }}
                        </span>
                        
                        {{-- Vehicle Image --}}
                        @if($vehicle->image)
                            <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->model }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">{{ $vehicle->plate_number }}</p>
                        </div>

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-6">
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase mb-1">Year</p>
                                <p class="text-sm font-bold text-gray-800">{{ $vehicle->year }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase mb-1">Mileage</p>
                                <p class="text-sm font-bold text-gray-800">{{ number_format($vehicle->mileage) }} km</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-400 font-medium uppercase mb-1">Owner</p>
                                <div class="flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-gray-200 mr-2 flex-shrink-0 flex items-center justify-center text-xs font-bold text-gray-500">
                                        {{ substr($vehicle->owner_name ?? 'H', 0, 1) }}
                                    </div>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $vehicle->owner_name ?? 'HASTA Fleet' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Fuel Level Progress Bar --}}
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-xs text-gray-400 font-medium uppercase">Fuel Level</p>
                                <p class="text-xs font-bold text-gray-700">{{ $vehicle->fuel_level }}%</p>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $vehicle->fuel_level }}%"></div>
                            </div>
                        </div>

                        {{-- Footer: Expiry Info & Actions --}}
                        <div class="flex flex-col space-y-4">
                            <div class="flex justify-between items-center text-xs border-t border-b border-gray-50 py-3">
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-2 font-medium">Road Tax:</span>
                                    {{-- You can add logic here if you have roadtax_expiry dates --}}
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-md font-bold">active</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-2 font-medium">Insurance:</span>
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-md font-bold">active</span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center justify-between gap-4">
                                {{-- View Details Link --}}
                                {{-- 
                                    CRITICAL FIX: Using $vehicle->vehicleID instead of $vehicle->id 
                                    If your DB column is different, change 'vehicleID' below.
                                --}}
                                <a href="{{ route('vehicles.show', $vehicle->plateNumber) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-gray-50 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    View Details
                                </a>

                                {{-- Delete Button (Using Form for Security) --}}
                                <form action="{{ route('staff.fleet.destroy', $vehicle->plateNumber) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-100" title="Delete Vehicle">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-4">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No vehicles found</h3>
                    <p class="text-gray-500 mt-1">Adjust your search or filters, or add a new vehicle.</p>
                </div>
            @endforelse

        </div>
        
        {{-- Pagination Links --}}
        <div class="mt-8">
            {{ $vehicles->links() }}
        </div>

    </div>
</x-staff-layout>
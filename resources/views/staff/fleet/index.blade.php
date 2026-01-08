{{-- resources/views/staff/fleet/index.blade.php --}}
@extends('layouts.staff')

@section('content')
<div class="max-w-7xl mx-auto p-2" x-data="{ activeTab: 'all' }">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 animate-fade-in-down">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Fleet Management</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor vehicle status, maintenance, and availability.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('staff.fleet.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium text-sm rounded-xl shadow-lg shadow-red-500/30 transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Vehicle
            </a>
        </div>
    </div>

    {{-- 1. STATISTICS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10 animate-fade-in-up">
        <div class="bg-white rounded-2xl p-5 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 flex items-center justify-between hover:border-red-100 transition-colors">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Fleet</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $totalVehicles ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-car-side text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 flex items-center justify-between hover:border-green-100 transition-colors">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Available</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $availableCount ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 flex items-center justify-between hover:border-yellow-100 transition-colors">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">On Road</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $rentedCount ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
                <i class="fas fa-key text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 flex items-center justify-between hover:border-purple-100 transition-colors">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Service</p>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $maintenanceCount ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                <i class="fas fa-tools text-xl"></i>
            </div>
        </div>
    </div>

    {{-- 2. FILTERS & SEARCH --}}
    <div class="bg-white rounded-2xl p-4 mb-8 shadow-sm border border-gray-100 flex flex-col lg:flex-row justify-between items-center gap-4 animate-fade-in-up delay-100">
        {{-- Filter Tabs --}}
        <div class="flex space-x-1 bg-gray-100/50 p-1 rounded-xl w-full lg:w-auto overflow-x-auto">
            <button class="px-4 py-2 text-sm font-semibold rounded-lg transition-all duration-200 focus:outline-none bg-white text-gray-800 shadow-sm">
                All Vehicles
            </button>
            <button class="px-4 py-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-white hover:shadow-sm hover:text-gray-700 transition-all duration-200">
                Available
            </button>
            <button class="px-4 py-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-white hover:shadow-sm hover:text-gray-700 transition-all duration-200">
                Rented
            </button>
            <button class="px-4 py-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-white hover:shadow-sm hover:text-gray-700 transition-all duration-200">
                Maintenance
            </button>
        </div>

        {{-- Search --}}
        <div class="relative w-full lg:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition duration-150 sm:text-sm" placeholder="Search by plate or model...">
        </div>
    </div>

    {{-- 3. VEHICLE GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6 animate-fade-in-up delay-200">
        @forelse ($fleet as $car)
            @php
                $status = strtolower($car->status);
                // Tailwind Status Styles
                $statusStyles = match($status) {
                    'available' => 'bg-green-100 text-green-700 border-green-200',
                    'booked', 'rented' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'maintenance' => 'bg-purple-100 text-purple-700 border-purple-200',
                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                };
                
                $fuelLevel = $car->fuel_level ?? 75;
                $fuelColor = $fuelLevel > 50 ? 'bg-green-500' : ($fuelLevel > 20 ? 'bg-yellow-500' : 'bg-red-500');
                
                // IMAGE LOGIC (Dynamic selection based on model/year)
                $vehicleImage = 'default-car.png';
                if (!empty($car->photos)) {
                    $vehicleImage = $car->photos;
                } else {
                    $model = strtolower($car->modelName);
                    $year = $car->year;
                    if (str_contains($model, 'axia')) { $vehicleImage = ($year >= 2023) ? 'axia-2024.png' : 'axia-2018.png'; }
                    elseif (str_contains($model, 'bezza')) { $vehicleImage = 'bezza-2018.png'; }
                    elseif (str_contains($model, 'myvi')) { $vehicleImage = ($year >= 2020) ? 'myvi-2020.png' : 'myvi-2015.png'; }
                    elseif (str_contains($model, 'saga')) { $vehicleImage = 'saga-2017.png'; }
                    elseif (str_contains($model, 'alza')) { $vehicleImage = 'alza-2019.png'; }
                    elseif (str_contains($model, 'aruz')) { $vehicleImage = 'aruz-2020.png'; }
                    elseif (str_contains($model, 'vellfire')) { $vehicleImage = 'vellfire-2020.png'; }
                    elseif (str_contains($model, 'x50')) { $vehicleImage = 'x50-2024.png'; }
                    elseif (str_contains($model, 'y15')) { $vehicleImage = 'y15zr-2023.png'; }
                }
            @endphp

            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full overflow-hidden">
                {{-- Image Section --}}
                <div class="relative h-48 bg-gray-50 overflow-hidden flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-100/50 to-transparent z-10"></div>
                    
                    <img src="{{ asset('images/' . $vehicleImage) }}" 
                         class="w-full h-full object-contain p-4 transform group-hover:scale-110 transition-transform duration-700 relative z-0" 
                         alt="{{ $car->modelName }}">
                    
                    {{-- Floating Badge --}}
                    <div class="absolute top-3 left-3 z-20">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusStyles }} shadow-sm">
                            {{ $status }}
                        </span>
                    </div>

                    {{-- Plate Number Overlay --}}
                    <div class="absolute bottom-3 left-3 z-20">
                        <h4 class="text-gray-900 font-mono text-lg font-bold tracking-wider bg-white/80 backdrop-blur-sm px-2 py-1 rounded-lg shadow-sm">{{ $car->plateNumber }}</h4>
                    </div>
                </div>

                {{-- Content Body --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-red-600 transition-colors">{{ $car->modelName }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $car->owner_name ?? 'Company Owned' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $car->year }}</span>
                        </div>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-3 mb-4 text-xs text-gray-500 bg-gray-50 p-3 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-tachometer-alt mr-2 text-gray-400"></i>
                            {{ number_format($car->odometer ?? 0) }} km
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-gas-pump mr-2 text-gray-400"></i>
                            {{ $fuelLevel }}% Fuel
                        </div>
                    </div>

                    {{-- Fuel Bar --}}
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-4 overflow-hidden">
                        <div class="{{ $fuelColor }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $fuelLevel }}%"></div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center gap-2">
                        {{-- MAIN BUTTON: Re-routed to EDIT to preserve functionality since icon is now VIEW --}}
                        <a href="{{ route('staff.fleet.edit', $car->plateNumber) }}" class="flex-1 text-center py-2 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition-colors">
                            Edit
                        </a>
                        
                        <div class="flex gap-1">
                            {{-- ICON BUTTON: Changed from Pencil (Edit) to Eye (View/Show) --}}
                            <a href="{{ route('staff.fleet.show', $car->plateNumber) }}" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            
                            <form action="{{ route('staff.fleet.destroy', $car->plateNumber) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-car-crash text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No Vehicles Found</h3>
                <p class="text-gray-500 text-sm mt-1 max-w-sm">There are no vehicles in the fleet matching your criteria. Try adjusting filters or add a new vehicle.</p>
                <a href="{{ route('staff.fleet.create') }}" class="mt-4 text-red-600 font-semibold text-sm hover:underline">Add First Vehicle &rarr;</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex justify-center">
        {{ $fleet->links() }}
    </div>

</div>

<style>
    .animate-fade-in-down {
        animation: fadeInDown 0.6s ease-out forwards;
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
@extends('layouts.staff')

@section('content')
<div class="w-full px-6 py-8">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 animate-fade-in-down">
        <div>
            <h1 class="text-4xl font-semibold text-gray-900 tracking-tight">Fleet Management</h1>
            <p class="text-base text-gray-500 mt-2 font-medium">Monitor vehicle status, maintenance, and availability.</p>
        </div>
        <div class="mt-6 md:mt-0">
            <a href="{{ route('staff.fleet.create') }}" class="inline-flex items-center px-8 py-3.5 bg-gray-900 hover:bg-black text-white font-medium text-sm rounded-2xl shadow-lg shadow-gray-900/20 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Vehicle
            </a>
        </div>
    </div>

    {{-- 1. STATISTICS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 mb-16 animate-fade-in-up">
        {{-- (Statistics cards remain unchanged for cleanliness) --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all duration-500 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Fleet</p>
                    <h3 class="text-4xl font-bold text-gray-900 group-hover:scale-105 transition-transform origin-left">{{ $totalVehicles ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl text-gray-400 group-hover:bg-gray-900 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-car-side text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:border-emerald-100 hover:shadow-lg hover:shadow-emerald-500/5 transition-all duration-500 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-emerald-600/60 uppercase tracking-widest mb-2">Available</p>
                    <h3 class="text-4xl font-bold text-gray-900 group-hover:scale-105 transition-transform origin-left">{{ $availableCount ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-emerald-50 rounded-2xl text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-500 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-blue-600/60 uppercase tracking-widest mb-2">On Road</p>
                    <h3 class="text-4xl font-bold text-gray-900 group-hover:scale-105 transition-transform origin-left">{{ $rentedCount ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-blue-50 rounded-2xl text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-key text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:border-orange-100 hover:shadow-lg hover:shadow-orange-500/5 transition-all duration-500 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-orange-600/60 uppercase tracking-widest mb-2">Service</p>
                    <h3 class="text-4xl font-bold text-gray-900 group-hover:scale-105 transition-transform origin-left">{{ $maintenanceCount ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-orange-50 rounded-2xl text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-tools text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. FILTERS & SEARCH --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-3 mb-12 shadow-sm border border-gray-100 flex flex-col lg:flex-row justify-between items-center gap-6 animate-fade-in-up delay-100">
        {{-- Functional Filter Tabs --}}
        <div class="flex p-1.5 bg-gray-100/50 rounded-2xl w-full lg:w-auto overflow-x-auto gap-2">
            @php
                $currentStatus = request('status', 'all');
                $tabs = [
                    'all' => 'All Vehicles', 
                    'available' => 'Available', 
                    'rented' => 'Rented', 
                    'maintenance' => 'Maintenance'
                ];
            @endphp

            @foreach($tabs as $key => $label)
                <a href="{{ route('staff.fleet.index', ['status' => $key, 'search' => request('search')]) }}" 
                   class="px-6 py-3 text-sm font-semibold rounded-xl transition-all duration-200 whitespace-nowrap
                   {{ $currentStatus === $key ? 'bg-white text-gray-900 shadow-sm scale-[1.02]' : 'text-gray-500 hover:bg-white/50 hover:text-gray-900' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Functional Search Form --}}
        <form method="GET" action="{{ route('staff.fleet.index') }}" class="relative w-full lg:w-96 px-2 lg:px-0 lg:pr-2 pb-2 lg:pb-0">
            {{-- Keep current status when searching --}}
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            
            <div class="absolute inset-y-0 left-0 pl-6 lg:pl-5 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-base"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="block w-full pl-14 pr-6 py-3.5 bg-gray-50 border-0 rounded-2xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-gray-900/5 focus:bg-white transition-all text-sm font-medium shadow-inner" 
                   placeholder="Search by plate or model...">
        </form>
    </div>

    {{-- 3. VEHICLE GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-8 animate-fade-in-up delay-200">
        @forelse ($fleet as $car)
            @php
                $status = strtolower($car->status);
                $statusStyles = match($status) {
                    'available' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'booked', 'rented' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'maintenance' => 'bg-orange-100 text-orange-800 border-orange-200',
                    default => 'bg-gray-100 text-gray-800 border-gray-200',
                };
                
                $vehicleImage = $car->photo1;
                $imagePath = $vehicleImage && str_contains($vehicleImage, '/') 
                    ? asset('storage/' . $vehicleImage) 
                    : asset('images/' . ($vehicleImage ?? 'default.png'));
            @endphp

            <div class="group bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-gray-200 transition-all duration-500 flex flex-col h-full overflow-hidden hover:-translate-y-1">
                
                {{-- Image Section --}}
                <div class="relative h-64 bg-gradient-to-b from-gray-50 to-white overflow-hidden flex items-center justify-center">
                    <img src="{{ $imagePath }}" 
                         class="w-full h-full object-contain p-8 transform group-hover:scale-110 transition-transform duration-700 ease-out" 
                         alt="{{ $car->modelName }}">
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-5 left-5">
                        <span class="px-4 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider border {{ $statusStyles }} shadow-sm backdrop-blur-md bg-opacity-90">
                            {{ $status }}
                        </span>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="absolute top-5 right-5 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                        <a href="{{ route('staff.fleet.tabs.overview', $car->plateNumber) }}" class="w-10 h-10 flex items-center justify-center bg-white text-gray-900 rounded-full shadow-lg hover:bg-black hover:text-white transition-all transform hover:scale-110" title="Quick View">
                            <i class="fas fa-eye text-sm"></i>
                        </a>
                        <form action="{{ route('staff.fleet.destroy', $car->plateNumber) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="w-10 h-10 flex items-center justify-center bg-white text-red-500 rounded-full shadow-lg hover:bg-red-500 hover:text-white transition-all transform hover:scale-110" title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Content Body --}}
                <div class="p-8 pt-2 flex-1 flex flex-col">
                    <div class="mb-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-gray-900 leading-tight group-hover:text-red-600 transition-colors">{{ $car->modelName }}</h3>
                            <span class="text-xs font-bold bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg">{{ $car->year }}</span>
                        </div>
                        
                        {{-- HIGHLIGHTED PLATE NUMBER --}}
                        <div class="inline-block bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 shadow-sm mt-1">
                            <p class="text-sm text-gray-800 font-bold font-mono tracking-wider">{{ $car->plateNumber }}</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-auto pt-6 border-t border-gray-50">
                        <a href="{{ route('staff.fleet.edit', $car->plateNumber) }}" 
                           class="block w-full py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-2xl text-center hover:bg-black transition-all shadow-lg shadow-gray-900/10 group-hover:shadow-gray-900/20 transform group-hover:translate-y-0.5">
                            Manage Vehicle
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 flex flex-col items-center justify-center text-center">
                <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mb-8 animate-pulse">
                    <i class="fas fa-car text-gray-300 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Vehicles Found</h3>
                <p class="text-gray-500 text-base max-w-md">Your fleet is currently empty or no vehicles match your search criteria.</p>
                @if(request('search') || request('status'))
                    <a href="{{ route('staff.fleet.index') }}" class="mt-8 px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all">Clear Filters</a>
                @else
                    <a href="{{ route('staff.fleet.create') }}" class="mt-8 px-8 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition-all shadow-lg">Register First Vehicle</a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-16 flex justify-center">
        {{ $fleet->links() }}
    </div>

</div>

<style>
    .animate-fade-in-down {
        animation: fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.15s; }

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
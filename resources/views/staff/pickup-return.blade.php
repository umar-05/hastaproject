@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    {{-- Header --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pickup & Return Schedule</h1>
            <p class="text-gray-500 mt-1">Manage today's vehicle movements.</p>
        </div>
        <div class="text-right">
            <span class="block text-sm font-bold text-gray-500 uppercase">Today's Date</span>
            <span class="text-2xl font-bold text-red-600">{{ now()->format('d M Y') }}</span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        {{-- Pickups Card --}}
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-green-500 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase">Pending Pickups</p>
                    <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $todayPickups->count() }}</h2>
                </div>
                <div class="bg-green-100 p-3 rounded-full text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                </div>
            </div>
        </div>

        {{-- Returns Card --}}
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-gray-500 uppercase">Pending Returns</p>
                    <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $todayReturns->count() }}</h2>
                </div>
                <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- LEFT COLUMN: PICKUPS --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-green-50 px-6 py-4 border-b border-green-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-green-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Departures (Pickups)
                </h3>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($todayPickups as $booking)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-mono text-xs font-bold text-gray-400">#{{ $booking->bookingID }}</span>
                            <h4 class="font-bold text-gray-900">{{ $booking->fleet->modelName }} <span class="font-normal text-gray-500 text-sm">({{ $booking->fleet->plateNumber }})</span></h4>
                        </div>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded uppercase">{{ $booking->bookingStat }}</span>
                    </div>
                    
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ \Carbon\Carbon::parse($booking->pickupDate)->format('h:i A') }}
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $booking->pickupLoc }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs">
                                {{ substr($booking->customer->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="text-xs">
                                <p class="font-bold text-gray-900">{{ $booking->customer->name ?? 'Unknown' }}</p>
                                <p class="text-gray-500">{{ $booking->customer->phone ?? '-' }}</p>
                            </div>
                        </div>
                        
                        {{-- Action: Confirm Pickup --}}
                        @if($booking->bookingStat == 'confirmed')
                        <form action="{{ route('staff.confirmPickup', $booking->bookingID) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                Confirm Pickup
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <p>No pickups scheduled for today.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: RETURNS --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                    Arrivals (Returns)
                </h3>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($todayReturns as $booking)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="font-mono text-xs font-bold text-gray-400">#{{ $booking->bookingID }}</span>
                            <h4 class="font-bold text-gray-900">{{ $booking->fleet->modelName }} <span class="font-normal text-gray-500 text-sm">({{ $booking->fleet->plateNumber }})</span></h4>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded uppercase">Active</span>
                    </div>
                    
                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ \Carbon\Carbon::parse($booking->returnDate)->format('h:i A') }}
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $booking->returnLoc }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs">
                                {{ substr($booking->customer->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="text-xs">
                                <p class="font-bold text-gray-900">{{ $booking->customer->name ?? 'Unknown' }}</p>
                                <p class="text-gray-500">{{ $booking->customer->phone ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Action: Complete Return --}}
                        <form action="{{ route('staff.completeReturn', $booking->bookingID) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                Complete Return
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">
                    <p>No returns scheduled for today.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
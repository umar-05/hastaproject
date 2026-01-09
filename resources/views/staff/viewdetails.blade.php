<x-staff-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Booking Details</h1>
                    <p class="text-sm text-gray-500">Managing Booking #{{ $booking->bookingID }}</p>
                </div>
                <a href="{{ route('staff.bookingmanagement') }}" class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 bg-white border border-gray-300 px-4 py-2 rounded-lg shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to List
                </a>
            </div>

            {{-- 1. MAIN BOOKING INFO --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Overview</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $booking->bookingStat == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $booking->bookingStat }}
                    </span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Customer</p>
                        <p class="font-bold text-gray-900">{{ $booking->customer->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->customer->phone ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Vehicle</p>
                        <p class="font-bold text-gray-900">{{ $booking->fleet->modelName ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->fleet->plateNumber ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Schedule</p>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="font-medium text-green-700">Pick: {{ \Carbon\Carbon::parse($booking->pickupDate)->format('d M Y, h:i A') }}</span>
                            <span class="text-gray-300">|</span>
                            <span class="font-medium text-blue-700">Return: {{ \Carbon\Carbon::parse($booking->returnDate)->format('d M Y, h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. INSPECTION FORMS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- PICKUP FORM --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-green-50 flex justify-between items-center">
                        <h3 class="font-bold text-green-900">Pickup Inspection</h3>
                        @if($pickupInspection)
                            <span class="text-xs font-bold text-green-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Submitted
                            </span>
                        @else
                            <span class="text-xs font-bold text-gray-400">Not Started</span>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        @if($pickupInspection)
                            {{-- Pickup Photos --}}
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                @foreach(['frontViewImage', 'backViewImage', 'leftViewImage', 'rightViewImage'] as $img)
                                    @if($pickupInspection->$img)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/'.$pickupInspection->$img) }}" class="w-full h-24 object-cover rounded-lg border border-gray-100 cursor-pointer hover:opacity-75 transition" onclick="window.open(this.src, '_blank')">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            
                            {{-- Pickup Details --}}
                            <div class="space-y-2 text-sm border-t pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Fuel Level:</span>
                                    <span class="font-bold">{{ $pickupInspection->fuelBar }} Bars</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Mileage:</span>
                                    <span class="font-bold">{{ $pickupInspection->mileage }} km</span>
                                </div>
                                @if($pickupInspection->signature)
                                <div class="mt-2">
                                    <span class="text-gray-500 block mb-1">Signature:</span>
                                    <img src="{{ asset('storage/'.$pickupInspection->signature) }}" class="h-12 border rounded bg-white p-1">
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <p class="text-sm mb-4">No pickup inspection data found.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- RETURN FORM --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex justify-between items-center">
                        <h3 class="font-bold text-blue-900">Return Inspection</h3>
                        @if($returnInspection)
                            <span class="text-xs font-bold text-blue-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Submitted
                            </span>
                        @else
                            <span class="text-xs font-bold text-gray-400">Not Started</span>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        @if($returnInspection)
                            {{-- Return Photos --}}
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                @foreach(['frontViewImage', 'backViewImage', 'leftViewImage', 'rightViewImage'] as $img)
                                    @if($returnInspection->$img)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/'.$returnInspection->$img) }}" class="w-full h-24 object-cover rounded-lg border border-gray-100 cursor-pointer hover:opacity-75 transition" onclick="window.open(this.src, '_blank')">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            
                            {{-- Return Details --}}
                            <div class="space-y-2 text-sm border-t pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Fuel Level:</span>
                                    <span class="font-bold">{{ $returnInspection->fuelBar }} Bars</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Mileage:</span>
                                    <span class="font-bold">{{ $returnInspection->mileage }} km</span>
                                </div>
                                @if($returnInspection->signature)
                                <div class="mt-2">
                                    <span class="text-gray-500 block mb-1">Signature:</span>
                                    <img src="{{ asset('storage/'.$returnInspection->signature) }}" class="h-12 border rounded bg-white p-1">
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <p class="text-sm mb-4">No return inspection data found.</p>
                            
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-staff-layout>
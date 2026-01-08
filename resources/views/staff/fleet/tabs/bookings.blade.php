@extends('staff.fleet.layout')

@section('tab-content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Booking History</h3>
    </div>
    
    @if($bookings->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($bookings as $booking)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->customer->name ?? 'Unknown' }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->customer->matricNum ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $booking->pickupDate ? $booking->pickupDate->format('d M') : 'N/A' }} 
                        <span class="text-gray-300 mx-1">→</span> 
                        {{ $booking->returnDate ? $booking->returnDate->format('d M Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $booking->bookingStat === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($booking->bookingStat) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                        RM {{ number_format($booking->totalPrice, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="p-12 text-center text-gray-500">
            No bookings found for this vehicle.
        </div>
    @endif
</div>
@endsection
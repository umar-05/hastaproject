<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">My Bookings</h1>
        
        @if($bookings->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600 mb-4">No bookings yet.</p>
                <a href="{{ route('bookings.create', 1) }}" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">
                    Create Your First Booking
                </a>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-bold text-xl mb-2">{{ $booking->fleet->name ?? 'Vehicle' }}</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Pickup:</span> 
                                <span class="font-semibold">{{ $booking->pickup_date }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Return:</span> 
                                <span class="font-semibold">{{ $booking->return_date }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Status:</span> 
                                <span class="font-semibold">{{ $booking->booking_stat }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Total:</span> 
                                <span class="font-semibold text-red-600">RM {{ number_format($booking->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @endif
        
        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
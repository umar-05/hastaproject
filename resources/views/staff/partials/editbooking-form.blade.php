{{-- resources/views/staff/partials/editbooking-form.blade.php --}}
<form action="{{ route('staff.bookings.update', $booking->bookingID) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Vehicle Selection: Allowing the car to be switched --}}
        <div class="col-span-2">
            <label class="block text-sm font-bold text-gray-700 mb-2">Assign Vehicle (Switch Car)</label>
            <select name="plateNumber" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                @foreach($fleets as $car)
                    <option value="{{ $car->plateNumber }}" {{ $booking->plateNumber == $car->plateNumber ? 'selected' : '' }}>
                        {{ $car->modelName }} ({{ $car->plateNumber }}) - {{ ucfirst($car->status) }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Switching the car will not automatically recalculate the price.</p>
        </div>

        {{-- Pickup & Return Dates --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Pickup Date & Time</label>
            <input type="datetime-local" name="pickupDate" 
                   value="{{ \Carbon\Carbon::parse($booking->pickupDate)->format('Y-m-d\TH:i') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Return Date & Time</label>
            <input type="datetime-local" name="returnDate" 
                   value="{{ \Carbon\Carbon::parse($booking->returnDate)->format('Y-m-d\TH:i') }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Booking Status</label>
            <select name="bookingStat" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                @foreach(['pending', 'approved', 'completed', 'cancelled'] as $status)
                    <option value="{{ $status }}" {{ strtolower($booking->bookingStat) == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Price: Hidden or Read-only if you want to prevent changes --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Total Price (RM)</label>
            <input type="number" step="0.01" name="totalPrice" value="{{ $booking->totalPrice }}"
                   class="w-full bg-gray-50 border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500" readonly>
            <p class="text-xs text-gray-400 mt-1">Price is locked for this edit.</p>
        </div>
    </div>

    <div class="mt-8 flex justify-end gap-3 border-t pt-6">
        <button type="button" onclick="closeBookingModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            Cancel
        </button>
        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-md transition font-bold">
            Update Booking
        </button>
    </div>
</form>
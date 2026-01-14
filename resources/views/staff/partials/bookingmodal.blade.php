<div class="space-y-4">
    {{-- Overview --}}
    <div class="bg-white rounded-lg p-4 border">
        <div class="flex justify-between items-start">
            <div>
                <h4 class="font-bold text-lg">Booking #{{ $booking->bookingID }}</h4>
                <p class="text-sm text-gray-500">Customer: {{ $booking->customer->name ?? 'N/A' }} ({{ $booking->matricNum }})</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ strtolower($booking->bookingStat) == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $booking->bookingStat }}</span>
            </div>
        </div>

        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
            <div>
                <p class="font-medium">Vehicle</p>
                <p>{{ $booking->fleet->modelName ?? 'N/A' }} <span class="text-xs text-gray-400">{{ $booking->fleet->plateNumber ?? $booking->plateNumber }}</span></p>
            </div>
            <div>
                <p class="font-medium">Schedule</p>
                <p>Pickup: {{ \Carbon\Carbon::parse($booking->pickupDate)->format('Y-m-d H:i') }}</p>
                <p>Return: {{ \Carbon\Carbon::parse($booking->returnDate)->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Edit Form --}}
    <div class="bg-white rounded-lg p-4 border">
        <h5 class="font-bold mb-3">Edit Booking</h5>

        <form method="POST" action="{{ route('staff.staff.bookingmanagement.update', $booking->bookingID) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600">Vehicle (Plate)</label>
                    <select name="plateNumber" class="mt-1 block w-full border rounded px-2 py-2 text-sm">
                        @foreach($fleets as $fleet)
                            <option value="{{ $fleet->plateNumber }}" {{ $fleet->plateNumber == ($booking->plateNumber ?? $booking->fleet->plateNumber) ? 'selected' : '' }}>
                                {{ $fleet->plateNumber }} — {{ $fleet->modelName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600">Status</label>
                    <select name="bookingStat" class="mt-1 block w-full border rounded px-2 py-2 text-sm">
                        <option value="pending" {{ strtolower($booking->bookingStat) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ strtolower($booking->bookingStat) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ strtolower($booking->bookingStat) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ strtolower($booking->bookingStat) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600">Pickup Date & Time</label>
                    <input type="datetime-local" name="pickupDate" value="{{ optional($booking->pickupDate)->format('Y-m-d\TH:i') }}" class="mt-1 block w-full border rounded px-2 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600">Return Date & Time</label>
                    <input type="datetime-local" name="returnDate" value="{{ optional($booking->returnDate)->format('Y-m-d\TH:i') }}" class="mt-1 block w-full border rounded px-2 py-2 text-sm">
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="closeBookingModal()" class="px-4 py-2 bg-gray-100 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

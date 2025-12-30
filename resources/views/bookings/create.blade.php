{{-- resources/views/bookings/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                <h1 class="text-3xl font-bold text-red-600">Book Your Car</h1>
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                @csrf

                {{-- Car Details --}}
                <div class="bg-white border rounded-lg p-4 mb-6 flex items-center gap-4">
                    <img src="{{ $car->image_url }}" alt="{{ $car->name }}" class="w-32 h-32 object-contain">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-800">{{ $car->name }}</h2>
                        <p class="text-gray-600">{{ $car->plate_number }}</p>
                        <div class="flex gap-4 mt-2">
                            <span class="text-red-600 font-semibold">RM{{ $car->price_per_day }}/day</span>
                            <span class="text-gray-600">Deposit: RM{{ $car->deposit }}</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="fleet_id" value="{{ $car->id }}">

                {{-- Date Selection --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Start Date --}}
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                        <div class="flex gap-2">
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   required>
                            <input type="time" 
                                   name="start_time" 
                                   value="11:00"
                                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>

                    {{-- End Date --}}
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">End Date</label>
                        <div class="flex gap-2">
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   required>
                            <input type="time" 
                                   name="end_time" 
                                   value="11:00"
                                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- Pickup Location --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Pickup Location</label>
                    <div class="flex gap-2">
                        <button type="button" 
                                class="location-btn px-6 py-2 bg-red-100 text-red-800 rounded-full hover:bg-red-200"
                                data-value="STUDENT MALL">
                            STUDENT MALL
                        </button>
                        <input type="text" 
                               name="pickup_location" 
                               id="pickup_location"
                               placeholder="OTHER :"
                               class="flex-1 border border-gray-300 rounded-full px-6 py-2 bg-red-100 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               required>
                    </div>
                </div>

                {{-- Return Location --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Return Location</label>
                    <div class="flex gap-2">
                        <button type="button" 
                                class="location-btn px-6 py-2 bg-red-100 text-red-800 rounded-full hover:bg-red-200"
                                data-value="STUDENT MALL">
                            STUDENT MALL
                        </button>
                        <input type="text" 
                               name="return_location" 
                               id="return_location"
                               placeholder="OTHER :"
                               class="flex-1 border border-gray-300 rounded-full px-6 py-2 bg-red-100 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               required>
                    </div>
                </div>

                {{-- Loyalty Reward --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Loyalty Reward</label>
                    <div class="flex gap-4 overflow-x-auto">
                        @foreach($rewards as $reward)
                        <div class="flex-shrink-0 relative">
                            <div class="bg-gradient-to-r from-gray-800 to-red-600 text-white p-6 rounded-lg w-64">
                                <div class="text-3xl font-bold">{{ $reward->discount }}% Off</div>
                                <div class="text-sm mt-2">{{ $reward->company }}</div>
                                <div class="text-xs">Valid until {{ $reward->valid_until }}</div>
                            </div>
                            <button type="button" 
                                    class="absolute bottom-4 right-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full text-sm font-semibold"
                                    onclick="applyReward({{ $reward->id }}, {{ $reward->discount }})">
                                Claim
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="reward_id" id="reward_id">

                {{-- Voucher Code --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Voucher Code</label>
                    <div class="flex gap-2">
                        <input type="text" 
                               name="voucher_code" 
                               id="voucher_code"
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="Enter voucher code">
                        <button type="button" 
                                onclick="applyVoucher()"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold">
                            APPLY
                        </button>
                    </div>
                </div>

                {{-- Total Amount --}}
                <div class="bg-red-50 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700 font-medium">Total Amount:</span>
                        <span class="text-3xl font-bold text-red-600">RM <span id="total_amount">{{ $car->price_per_day }}</span></span>
                    </div>
                    <p class="text-sm text-gray-600">*Deposit will be refunded within 10 working days after return</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4">
                    <a href="{{ route('home') }}" 
                       class="flex-1 bg-white border-2 border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-lg text-center font-semibold">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold">
                        Next
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle location button clicks
document.querySelectorAll('.location-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.nextElementSibling;
        input.value = this.dataset.value;
    });
});

// Calculate total amount based on dates
function calculateTotal() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        
        if (days > 0) {
            const pricePerDay = {{ $car->price_per_day }};
            const total = days * pricePerDay;
            document.getElementById('total_amount').textContent = total;
        }
    }
}

document.getElementById('start_date').addEventListener('change', calculateTotal);
document.getElementById('end_date').addEventListener('change', calculateTotal);

// Apply reward
function applyReward(rewardId, discount) {
    document.getElementById('reward_id').value = rewardId;
    const currentTotal = parseFloat(document.getElementById('total_amount').textContent);
    const discountedTotal = currentTotal * (1 - discount / 100);
    document.getElementById('total_amount').textContent = discountedTotal.toFixed(2);
}

// Apply voucher
function applyVoucher() {
    const voucherCode = document.getElementById('voucher_code').value;
    
    fetch('{{ route("voucher.validate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ voucher_code: voucherCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            const currentTotal = parseFloat(document.getElementById('total_amount').textContent);
            const discountedTotal = currentTotal * (1 - data.discount / 100);
            document.getElementById('total_amount').textContent = discountedTotal.toFixed(2);
            alert('Voucher applied successfully!');
        } else {
            alert('Invalid voucher code');
        }
    });
}
</script>
@endsection
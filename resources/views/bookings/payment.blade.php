@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-4xl mx-auto px-6">
        <h1 class="text-4xl font-bold text-center mb-10">Payment</h1>

        <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="fleet_id" value="{{ $booking_data['fleet_id'] }}">
            <input type="hidden" name="start_date" value="{{ $booking_data['start_date'] }}">
            <input type="hidden" name="start_time" value="{{ $booking_data['start_time'] }}">
            <input type="hidden" name="end_date" value="{{ $booking_data['end_date'] }}">
            <input type="hidden" name="end_time" value="{{ $booking_data['end_time'] }}">
            <input type="hidden" name="pickup_location" value="{{ $booking_data['pickup_location'] }}">
            <input type="hidden" name="return_location" value="{{ $booking_data['return_location'] }}">
            {{-- Hidden fields from previous step --}}
            @foreach($booking_data as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <div class="space-y-6 mb-10">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Name</label>
                    <input type="text" 
                        name="payer_name" 
                        value="{{ auth()->user()->name }}" 
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Account No.</label>
                    <input type="text" name="payer_account" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Bank Name</label>
                    <input type="text" name="payer_bank" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 outline-none" required>
                </div>
            </div>

            {{-- PAYMENT SUMMARY: always full payment + deposit --}}
            <div class="mb-10">
                <h3 class="font-bold text-lg mb-4">Payment Summary</h3>

                <div class="bg-red-50 rounded-2xl py-4 px-8 flex justify-between items-center">
                    <span class="text-gray-600 text-xl">Total Amount (rental + deposit):</span>
                    <span class="text-red-500 text-3xl font-bold" id="total_display">
                        RM {{ number_format(($full_amount + $deposit_amount), 2) }}
                    </span>
                </div>

                <input type="hidden" name="total_amount" id="final_amount_input" value="{{ ($full_amount + $deposit_amount) }}">
                <input type="hidden" name="deposit_amount" value="{{ $deposit_amount }}">
                <input type="hidden" name="price_per_day" value="{{ $full_amount / max(1, ( (isset($booking_data['end_date']) && isset($booking_data['start_date'])) ? ( (new \DateTime($booking_data['end_date']))->diff(new \DateTime($booking_data['start_date']))->days ?: 1 ) : 1 )) }}">

            </div>

            {{-- PAYMENT METHOD --}}
            <div class="mb-10">
                <h3 class="font-bold text-lg mb-4">Payment Method</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Bank --}}
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment_method" value="bank_transfer" class="hidden" checked>

                        <div class="border-2 border-gray-200 rounded-2xl p-8 h-full flex flex-col items-center justify-center transition
                                    group-has-[input:checked]:border-black">
                            <div class="border border-gray-300 rounded-xl p-6 mb-4 w-full text-sm">
                                <p><strong>Name :</strong> Hasta Travel & Tour</p>
                                <p><strong>Account No. :</strong> 139748362455166</p>
                                <p><strong>Bank Name :</strong> Maybank</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center
                                            group-has-[input:checked]:border-black">
                                    <div class="w-2.5 h-2.5 bg-black rounded-full scale-0 transition
                                                group-has-[input:checked]:scale-100"></div>
                                </div>
                                <span class="font-medium">Bank Transfer</span>
                            </div>
                        </div>
                    </label>

                    {{-- DuitNow --}}
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment_method" value="duitnow" class="hidden">

                        <div class="border-2 border-gray-200 rounded-2xl p-8 h-full flex flex-col items-center justify-center transition
                                    group-has-[input:checked]:border-black">
                            <div class="bg-white border-4 border-pink-500 rounded-2xl p-4 mb-4">
                                <img src="{{ asset('images/qr-code.png') }}" class="w-48 h-48">
                                <p class="text-center font-bold text-xs mt-2 italic">Hasta Travel & Tours</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 border-2 border-gray-400 rounded-full flex items-center justify-center
                                            group-has-[input:checked]:border-black">
                                    <div class="w-2.5 h-2.5 bg-black rounded-full scale-0 transition
                                                group-has-[input:checked]:scale-100"></div>
                                </div>
                                <span class="font-medium">DuitNow QR</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- RECEIPT --}}
            <div class="mb-12">
                <h3 class="font-bold text-lg mb-4">Payment Receipt</h3>

                <label class="flex items-center gap-3 border-2 border-gray-300 rounded-xl px-6 py-3 cursor-pointer hover:bg-gray-50 transition">
                    <input type="file" name="receipt" accept="image/png, image/jpeg, image/jpg">
                </label>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="window.history.back()"
                    class="flex-1 border-2 border-gray-300 py-4 rounded-xl font-bold">
                    Back
                </button>
                <button type="submit"
                    class="flex-1 bg-red-600 text-white py-4 rounded-xl font-bold hover:bg-red-700">
                    Finish
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const display = document.getElementById('total_display');
    // total shown is full_amount + deposit_amount (no toggle)
    const hiddenInput = document.getElementById('final_amount_input');
    if (hiddenInput) {
        display.innerText = 'RM ' + parseFloat(hiddenInput.value).toFixed(2);
    }
});
</script>

@endsection

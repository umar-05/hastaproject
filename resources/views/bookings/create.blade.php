<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #pickup_map:not(.hidden), 
        #return_map:not(.hidden) {
            height: 350px !important;
            width: 100%;
            display: block;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .leaflet-container {
            height: 100%;
            width: 100%;
            z-index: 1;
        }
    </style>

    @php
        // 1. Capture the data from the URL (Home page search)
        // We check both 'pickup_date' and 'start_date' just in case
        $prePickupDate = request('pickup_date') ?? request('start_date');
        $preReturnDate = request('return_date') ?? request('end_date');
        $prePickupTime = request('pickup_time') ?? request('start_time') ?? '09:00';
        $preReturnTime = request('return_time') ?? request('end_time') ?? '09:00';
        $prePickupLoc = request('pickup_location');
        $preReturnLoc = request('return_location');

        // Vehicle Image Logic
        $vehicleImage = 'default-car.png'; 
        if (isset($car)) {
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
        }
    @endphp

    <div class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-6">
                
                <div class="flex justify-between items-center mb-6 pb-4 border-b">
                    <h1 class="text-3xl font-bold text-red-600">Book Your Car</h1>
                    <a href="{{ route('vehicles.index') }}" class="text-gray-600 hover:text-gray-800">Back</a>
                </div>

                <form action="{{ route('bookings.payment') }}" method="POST" id="bookingForm">
                    @csrf

                    {{-- Car Details --}}
                    <div class="bg-white border rounded-lg p-4 mb-6 flex items-center gap-4">
                        <img src="{{ asset('images/' . $vehicleImage) }}" class="w-32 h-32 object-contain">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-800">{{ $vehicleName }}</h2>
                            <p class="text-gray-600">{{ $car->plateNumber }}</p>
                            <div class="flex gap-4 mt-2">
                                <span class="text-red-600 font-semibold">RM{{ $pricePerDay }}/day</span>
                                <span class="text-gray-600">Deposit: RM{{ $car->deposit ?? 50 }}</span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="plateNumber" value="{{ $car->plateNumber }}">
                    <input type="hidden" name="price_per_day" id="price_per_day_input" value="{{ $pricePerDay }}">
                    <input type="hidden" name="total_amount" id="total_amount_input" value="">
                    <input type="hidden" name="deposit_amount" id="deposit_amount_input" value="{{ $car->deposit ?? 50 }}">

                    {{-- Date & Time Selection --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                            <div class="flex gap-2">
                                <input type="date" name="start_date" id="start_date" 
                                       value="{{ $prePickupDate }}" 
                                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                                
                                <select name="start_time" id="start_time" class="border border-gray-300 rounded-lg px-4 py-2" required>
                                    @for($h=9; $h<=17; $h++)
                                        @foreach(['00', '30'] as $m)
                                            @php $t = sprintf('%02d:%s', $h, $m); @endphp
                                            <option value="{{ $t }}" {{ $prePickupTime == $t ? 'selected' : '' }}>
                                                {{ date('g:i A', strtotime($t)) }}
                                            </option>
                                        @endforeach
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">End Date</label>
                            <div class="flex gap-2">
                                <input type="date" name="end_date" id="end_date" 
                                       value="{{ $preReturnDate }}"
                                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                                
                                <select name="end_time" id="end_time" class="border border-gray-300 rounded-lg px-4 py-2" required>
                                    @for($h=9; $h<=17; $h++)
                                        @foreach(['00', '30'] as $m)
                                            @php $t = sprintf('%02d:%s', $h, $m); @endphp
                                            <option value="{{ $t }}" {{ $preReturnTime == $t ? 'selected' : '' }}>
                                                {{ date('g:i A', strtotime($t)) }}
                                            </option>
                                        @endforeach
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Pickup Location --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Pickup Location</label>
                        <div class="flex gap-2 mb-3">
                            <button type="button" class="location-btn px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold" data-value="STUDENT MALL" data-target="pickup_location">STUDENT MALL</button>
                            <input type="text" name="pickup_location" id="pickup_location" 
                                   value="{{ $prePickupLoc }}"
                                   placeholder="Or type custom location..." 
                                   class="flex-1 border border-gray-300 rounded-full px-6 py-2 focus:ring-2 focus:ring-red-500" required>
                            <button type="button" onclick="openMap('pickup')" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600">Map</button>
                        </div>
                        <div id="pickup_map" class="hidden rounded-lg relative shadow-inner"></div>
                    </div>

                    {{-- Return Location --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Return Location</label>
                        <div class="flex gap-2 mb-3">
                            <button type="button" class="location-btn px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold" data-value="STUDENT MALL" data-target="return_location">STUDENT MALL</button>
                            <input type="text" name="return_location" id="return_location" 
                                   value="{{ $preReturnLoc }}"
                                   placeholder="Or type custom location..." 
                                   class="flex-1 border border-gray-300 rounded-full px-6 py-2 focus:ring-2 focus:ring-red-500" required>
                            <button type="button" onclick="openMap('return')" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600">Map</button>
                        </div>
                        <div id="return_map" class="hidden rounded-lg relative shadow-inner"></div>
                    </div>

                    {{-- Price Display --}}
                    <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Estimated Total:</span>
                            <span class="text-3xl font-bold text-red-600">RM <span id="total_amount_display">0.00</span></span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-right">*Includes RM{{ $car->deposit ?? 50 }} refundable deposit</p>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('vehicles.index') }}" class="flex-1 bg-white border-2 border-gray-300 py-3 rounded-lg text-center font-bold">Cancel</a>
                        <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold">Proceed to Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    // Price Calculation Logic
    function calculateTotal() {
        const startVal = document.getElementById('start_date').value;
        const endVal = document.getElementById('end_date').value;
        const pricePerDay = parseFloat("{{ $pricePerDay }}");
        const deposit = parseFloat("{{ $car->deposit ?? 50 }}");

        if (startVal && endVal) {
            const start = new Date(startVal);
            const end = new Date(endVal);
            const diffTime = end - start;
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            
            if (diffDays <= 0) diffDays = 1;

            const total = (diffDays * pricePerDay) + deposit;
            document.getElementById('total_amount_display').innerText = total.toFixed(2);
            document.getElementById('total_amount_input').value = total.toFixed(2);
        }
    }

    // Initialize Page
    document.addEventListener('DOMContentLoaded', () => {
        calculateTotal(); // Run once on load
        
        document.getElementById('start_date').addEventListener('change', calculateTotal);
        document.getElementById('end_date').addEventListener('change', calculateTotal);
        
        // Location button logic
        document.querySelectorAll('.location-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById(this.dataset.target).value = this.dataset.value;
            });
        });
    });

    // Map logic (simplified for flow)
    let pickupMap, returnMap;
    function openMap(type) {
        const mapDiv = document.getElementById(type + '_map');
        mapDiv.classList.toggle('hidden');
        if (!mapDiv.classList.contains('hidden')) {
            setTimeout(() => {
                if (type === 'pickup' && !pickupMap) {
                    pickupMap = L.map('pickup_map').setView([1.56, 103.64], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(pickupMap);
                } else if (type === 'return' && !returnMap) {
                    returnMap = L.map('return_map').setView([1.56, 103.64], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(returnMap);
                }
            }, 200);
        }
    }
    </script>
</x-app-layout>
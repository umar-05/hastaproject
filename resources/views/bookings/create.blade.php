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
        $vehicleImage = 'default-car.png'; 
        
        if (isset($car)) {
            if (!empty($car->photos1)) {
                $vehicleImage = $car->photos1;
            } else {
                $model = strtolower($car->modelName);
                $year = $car->year;

                if (str_contains($model, 'axia')) {
                    $vehicleImage = ($year >= 2023) ? 'axia-2024.png' : 'axia-2018.png';
                } elseif (str_contains($model, 'bezza')) {
                    $vehicleImage = 'bezza-2018.png';
                } elseif (str_contains($model, 'myvi')) {
                    $vehicleImage = ($year >= 2020) ? 'myvi-2020.png' : 'myvi-2015.png';
                } elseif (str_contains($model, 'saga')) {
                    $vehicleImage = 'saga-2017.png';
                } elseif (str_contains($model, 'alza')) {
                    $vehicleImage = 'alza-2019.png';
                } elseif (str_contains($model, 'aruz')) {
                    $vehicleImage = 'aruz-2020.png';
                } elseif (str_contains($model, 'vellfire')) {
                    $vehicleImage = 'vellfire-2020.png';
                } elseif (str_contains($model, 'x50')) {
                    $vehicleImage = 'x50-2024.png'; 
                } elseif (str_contains($model, 'y15')) {
                    $vehicleImage = 'y15zr-2023.png';
                }
            }
        }
    @endphp

    <div class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-6">
                
                {{-- Header --}}
                <div class="flex justify-between items-center mb-6 pb-4 border-b">
                    <h1 class="text-3xl font-bold text-red-600">Book Your Car</h1>
                    <a href="{{ route('vehicles.index') }}" class="text-gray-600 hover:text-gray-800">Back</a>
                </div>

                <form action="{{ route('bookings.payment') }}" method="POST" id="bookingForm">
                    @csrf

                    {{-- Car Details --}}
                    <div class="bg-white border rounded-lg p-4 mb-6 flex items-center gap-4">
                        <img src="{{ asset('images/' . $vehicleImage) }}" 
                             alt="{{ $vehicleName }}" 
                             class="w-32 h-32 object-contain"
                             onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/3202/3202926.png';">
                        
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $vehicleName }}</h2>
                            <p class="text-gray-600">{{ $car->plateNumber }}</p>
                            <div class="flex gap-4 mt-2">
                                <span class="text-red-600 font-semibold">RM{{ $pricePerDay }}/day</span>
                                <span class="text-gray-600">Deposit: RM{{ $car->deposit ?? 50 }}</span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="plateNumber" value="{{ $car->plateNumber }}">
                    <input type="hidden" name="price_per_day" id="price_per_day_input" value="{{ $pricePerDay }}">
                    <input type="hidden" name="total_amount" id="total_amount_input" value="{{ $pricePerDay }}">
                    <input type="hidden" name="deposit_amount" id="deposit_amount_input" value="{{ $car->deposit ?? 50 }}">

                    {{-- Date Selection --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                            <div class="flex gap-2">
                                <input type="date" name="start_date" id="start_date" class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                                <input type="time" name="start_time" value="10:00" class="border border-gray-300 rounded-lg px-4 py-2" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">End Date</label>
                            <div class="flex gap-2">
                                <input type="date" name="end_date" id="end_date" class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                                <input type="time" name="end_time" value="10:00" class="border border-gray-300 rounded-lg px-4 py-2" required>
                            </div>
                        </div>
                    </div>

                    {{-- Pickup Location --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Pickup Location</label>
                        <div class="flex gap-2 mb-3">
                            <button type="button" class="location-btn px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold" data-value="STUDENT MALL" data-target="pickup_location">STUDENT MALL</button>
                            <input type="text" name="pickup_location" id="pickup_location" placeholder="Or type custom location..." class="flex-1 border border-gray-300 rounded-full px-6 py-2 focus:ring-2 focus:ring-red-500" required>
                            <button type="button" onclick="openMap('pickup')" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Map
                            </button>
                        </div>
                        <div id="pickup_map" class="hidden rounded-lg relative shadow-inner"></div>
                    </div>

                    {{-- Return Location --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Return Location</label>
                        <div class="flex gap-2 mb-3">
                            <button type="button" class="location-btn px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold" data-value="STUDENT MALL" data-target="return_location">STUDENT MALL</button>
                            <input type="text" name="return_location" id="return_location" placeholder="Or type custom location..." class="flex-1 border border-gray-300 rounded-full px-6 py-2 focus:ring-2 focus:ring-red-500" required>
                            <button type="button" onclick="openMap('return')" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Map
                            </button>
                        </div>
                        <div id="return_map" class="hidden rounded-lg relative shadow-inner"></div>
                    </div>

                    {{-- Estimated Total (Client-side calc only) --}}
                    <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Estimated Total:</span>
                            {{-- Corrected ID here: total_amount_display --}}
                            <span class="text-3xl font-bold text-red-600">RM <span id="total_amount_display">{{ $pricePerDay }}</span></span>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('vehicles.index') }}" class="flex-1 bg-white border-2 border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-lg text-center font-bold">Cancel</a>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-bold shadow-lg shadow-red-200">
                            Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    let pickupMap, returnMap;
    let pickupMarker, returnMarker;
    const defaultCoords = [1.5600, 103.6400]; // UTM Coordinates

    // Map Initialization
    function openMap(type) {
        const mapId = type + '_map';
        const mapDiv = document.getElementById(mapId);
        const input = document.getElementById(type + '_location');
        
        if (mapDiv.classList.contains('hidden')) {
            mapDiv.classList.remove('hidden');
            
            setTimeout(() => {
                if (type === 'pickup') {
                    if (!pickupMap) {
                        pickupMap = L.map('pickup_map').setView(defaultCoords, 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(pickupMap);
                        pickupMarker = L.marker(defaultCoords, { draggable: true }).addTo(pickupMap);
                        
                        pickupMap.on('click', e => updateLocation(e.latlng, pickupMarker, input));
                        pickupMarker.on('dragend', e => updateLocation(e.target.getLatLng(), pickupMarker, input));
                    }
                    pickupMap.invalidateSize();
                } else {
                    if (!returnMap) {
                        returnMap = L.map('return_map').setView(defaultCoords, 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(returnMap);
                        returnMarker = L.marker(defaultCoords, { draggable: true }).addTo(returnMap);
                        
                        returnMap.on('click', e => updateLocation(e.latlng, returnMarker, input));
                        returnMarker.on('dragend', e => updateLocation(e.target.getLatLng(), returnMarker, input));
                    }
                    returnMap.invalidateSize();
                }
            }, 200);
        } else {
            mapDiv.classList.add('hidden');
        }
    }

    function updateLocation(latlng, marker, input) {
        marker.setLatLng(latlng);
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
            .then(res => res.json())
            .then(data => {
                input.value = data.display_name || `${latlng.lat.toFixed(5)}, ${latlng.lng.toFixed(5)}`;
            })
            .catch(() => {
                input.value = `${latlng.lat.toFixed(5)}, ${latlng.lng.toFixed(5)}`;
            });
    }

    function geocodeAddress(address, map, marker, input) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&countrycodes=my`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const loc = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                    marker.setLatLng(loc);
                    map.setView(loc, 15);
                    input.value = data[0].display_name;
                }
            });
    }

    // Quick select buttons
    document.querySelectorAll('.location-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById(this.dataset.target).value = this.dataset.value;
        });
    });

    // Date & Price Calculation
    function calculateTotal() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const totalDisplay = document.getElementById('total_amount_display');
        const totalInput = document.getElementById('total_amount_input');
        
        const pricePerDay = {{ $pricePerDay }};
        const deposit = {{ $car->deposit ?? 50 }};

        if (startDateInput.value) {
            let nextDay = new Date(startDateInput.value);
            nextDay.setDate(nextDay.getDate() + 1);
            const minEndDate = nextDay.toISOString().split('T')[0];
            endDateInput.min = minEndDate;

            if (endDateInput.value && endDateInput.value <= startDateInput.value) {
                endDateInput.value = minEndDate;
            }
        }

        if (startDateInput.value && endDateInput.value) {
            const start = new Date(startDateInput.value);
            const end = new Date(endDateInput.value);
            
            if (end < start) {
                endDateInput.value = startDateInput.value;
                return;
            }

            const diffTime = Math.abs(end - start);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            if (diffDays <= 0) diffDays = 1;

            const total = (diffDays * pricePerDay) + deposit;
            const totalFixed = total.toFixed(2);
            
            if (totalDisplay) totalDisplay.textContent = totalFixed;
            if (totalInput) totalInput.value = totalFixed;
        }
    }

    // Event Listeners Initialization
    document.addEventListener('DOMContentLoaded', () => {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const today = new Date().toISOString().split('T')[0];
        
        startInput.min = today;
        startInput.addEventListener('change', calculateTotal);
        endInput.addEventListener('change', calculateTotal);

        // Geocoding inputs listeners
        ['pickup', 'return'].forEach(type => {
            const input = document.getElementById(type + '_location');
            input.addEventListener('keypress', function(e) {
                const map = type === 'pickup' ? pickupMap : returnMap;
                const marker = type === 'pickup' ? pickupMarker : returnMarker;
                if (e.key === 'Enter' && this.value && map) {
                    e.preventDefault();
                    geocodeAddress(this.value, map, marker, this);
                }
            });
        });

        // Initial Calculation
        calculateTotal();
    });
    </script>
</x-app-layout>
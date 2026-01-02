{{-- resources/views/bookings/create.blade.php --}}
@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* 1. Only apply height and borders when the map is NOT hidden. 
       This removes the empty white placeholder box on page load. */
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

    /* 2. Standard Leaflet container rules */
    .leaflet-container {
        height: 100%;
        width: 100%;
        z-index: 1;
    }

    /* 3. Ensure the instruction text stays visible above the map tiles */
    .map-instruction {
        z-index: 1000;
    }
</style>

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
                    <img src="{{ asset('images/' . $image) }}" alt="{{ $vehicleName }}" class="w-32 h-32 object-contain">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-800">{{ $vehicleName }}</h2>
                        <p class="text-gray-600">{{ $car->plate_number }}</p>
                        <div class="flex gap-4 mt-2">
                            <span class="text-red-600 font-semibold">RM{{ $pricePerDay }}/day</span>
                            <span class="text-gray-600">Deposit: RM{{ $car->deposit ?? 200 }}</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="fleet_id" value="{{ $car->fleet_id }}">

                {{-- Date Selection --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Start Date</label>
                        <div class="flex gap-2">
                            <input type="date" name="start_date" id="start_date" class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                            <input type="time" name="start_time" value="11:00" class="border border-gray-300 rounded-lg px-4 py-2" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">End Date</label>
                        <div class="flex gap-2">
                            <input type="date" name="end_date" id="end_date" class="flex-1 border border-gray-300 rounded-lg px-4 py-2" required>
                            <input type="time" name="end_time" value="11:00" class="border border-gray-300 rounded-lg px-4 py-2" required>
                        </div>
                    </div>
                </div>

                {{-- Pickup Location --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Pickup Location</label>
                    <div class="flex gap-2 mb-3">
                        <button type="button" class="location-btn px-6 py-2 bg-red-100 text-red-800 rounded-full hover:bg-red-200" data-value="STUDENT MALL" data-target="pickup_location">STUDENT MALL</button>
                        <input type="text" name="pickup_location" id="pickup_location" placeholder="OTHER :" class="flex-1 border border-gray-300 rounded-full px-6 py-2 bg-red-100 focus:ring-2 focus:ring-red-500" required>
                        <button type="button" onclick="openMap('pickup')" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            Map
                        </button>
                    </div>
                    <div id="pickup_map" class="hidden rounded-lg relative">
                        <div class="map-instruction absolute top-2 left-2 bg-white p-2 rounded shadow-md text-xs">
                            <p class="font-semibold">Click on map or drag marker to set location</p>
                        </div>
                    </div>
                </div>

                {{-- Return Location --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Return Location</label>
                    <div class="flex gap-2 mb-3">
                        <button type="button" class="location-btn px-6 py-2 bg-red-100 text-red-800 rounded-full hover:bg-red-200" data-value="STUDENT MALL" data-target="return_location">STUDENT MALL</button>
                        <input type="text" name="return_location" id="return_location" placeholder="OTHER :" class="flex-1 border border-gray-300 rounded-full px-6 py-2 bg-red-100 focus:ring-2 focus:ring-red-500" required>
                        <button type="button" onclick="openMap('return')" class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            Map
                        </button>
                    </div>
                    <div id="return_map" class="hidden rounded-lg relative">
                        <div class="map-instruction absolute top-2 left-2 bg-white p-2 rounded shadow-md text-xs">
                            <p class="font-semibold">Click on map or drag marker to set location</p>
                        </div>
                    </div>
                </div>

                {{-- Reward, Voucher, Total & Buttons sections stay the same --}}
                <div class="bg-red-50 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700 font-medium">Total Amount:</span>
                        <span class="text-3xl font-bold text-red-600">RM <span id="total_amount">{{ $pricePerDay }}</span></span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('home') }}" class="flex-1 bg-white border-2 border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-lg text-center font-semibold">Cancel</a>
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let pickupMap, returnMap;
let pickupMarker, returnMarker;
const studentMallCoords = [1.5600, 103.6400];

function openMap(type) {
    const mapDiv = document.getElementById(type + '_map');
    const input = document.getElementById(type + '_location');
    
    if (typeof L === 'undefined') return;

    if (mapDiv.classList.contains('hidden')) {
        mapDiv.classList.remove('hidden');

        // Delay ensures the div is visible so Leaflet can calculate its 350px height
        setTimeout(() => {
            if (type === 'pickup') {
                if (!pickupMap) {
                    pickupMap = L.map('pickup_map').setView(studentMallCoords, 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(pickupMap);
                    pickupMarker = L.marker(studentMallCoords, { draggable: true }).addTo(pickupMap);
                    
                    pickupMap.on('click', e => placeMarkerAndGetAddress(e.latlng, pickupMarker, pickupMap, input));
                    pickupMarker.on('dragend', e => placeMarkerAndGetAddress(e.target.getLatLng(), pickupMarker, pickupMap, input));
                }
                pickupMap.invalidateSize(); // Fixes "thin" rendering
            } else {
                if (!returnMap) {
                    returnMap = L.map('return_map').setView(studentMallCoords, 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(returnMap);
                    returnMarker = L.marker(studentMallCoords, { draggable: true }).addTo(returnMap);
                    
                    returnMap.on('click', e => placeMarkerAndGetAddress(e.latlng, returnMarker, returnMap, input));
                    returnMarker.on('dragend', e => placeMarkerAndGetAddress(e.target.getLatLng(), returnMarker, returnMap, input));
                }
                returnMap.invalidateSize();
            }
        }, 100);
    } else {
        mapDiv.classList.add('hidden');
    }
}

function placeMarkerAndGetAddress(latlng, marker, map, input) {
    marker.setLatLng(latlng);
    map.setView(latlng, 15);
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&zoom=18&addressdetails=1`)
        .then(res => res.json())
        .then(data => {
            input.value = data.display_name || `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
        })
        .catch(() => {
            input.value = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
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

// Event Listeners for shortcut buttons and enter key
document.querySelectorAll('.location-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = document.getElementById(this.dataset.target);
        input.value = this.dataset.value;
    });
});

document.addEventListener('DOMContentLoaded', () => {
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
});

function calculateTotal() {
    const start = new Date(document.getElementById('start_date').value);
    const end = new Date(document.getElementById('end_date').value);
    if (start && end && end > start) {
        const days = Math.ceil((end - start) / (86400000));
        document.getElementById('total_amount').textContent = (days * {{ $pricePerDay }}).toFixed(2);
    }
}
document.getElementById('start_date').addEventListener('change', calculateTotal);
document.getElementById('end_date').addEventListener('change', calculateTotal);
</script>
@endsection
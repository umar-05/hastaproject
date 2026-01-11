<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease-in-out; }
        .fade-enter, .fade-leave-to { opacity: 0; }
    </style>

    @php
        // 1. Capture Data
        $prePickupDate = request('pickup_date') ?? request('start_date') ?? date('Y-m-d');
        $preReturnDate = request('return_date') ?? request('end_date') ?? date('Y-m-d', strtotime('+1 day'));
        $prePickupTime = request('pickup_time') ?? request('start_time') ?? '09:00';
        $preReturnTime = request('return_time') ?? request('end_time') ?? '09:00';
        $prePickupLocRaw = request('pickup_location') ?? '';
        $preReturnLocRaw = request('return_location') ?? '';

        // Vehicle Image Logic
        $vehicleImage = 'default-car.png'; 
        if (isset($car)) {
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
    @endphp

    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                
                {{-- Header --}}
                <div class="bg-red-600 px-6 py-4 flex justify-between items-center">
                    <h1 class="text-xl font-bold text-white">Complete Booking</h1>
                    <a href="{{ route('vehicles.index') }}" class="text-white/80 hover:text-white text-sm">Cancel</a>
                </div>

                <form action="{{ route('bookings.payment') }}" method="POST" id="bookingForm" class="p-6">
                    @csrf

                    {{-- Compact Vehicle Summary --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 mb-6">
                        <img src="{{ asset('images/' . $vehicleImage) }}" class="w-24 h-16 object-contain">
                        <div>
                            <h2 class="text-lg font-black text-gray-800 leading-tight">{{ $car->modelName }}</h2>
                            <p class="text-red-600 font-bold text-xs uppercase">{{ $car->plateNumber }}</p>
                        </div>
                        <div class="ml-auto text-right text-sm">
                            <div class="text-gray-500">Rate: <span class="font-bold text-gray-800">RM{{ $pricePerDay }}</span>/day</div>
                        </div>
                    </div>

                    <input type="hidden" name="plateNumber" value="{{ $car->plateNumber }}">
                    <input type="hidden" name="price_per_day" value="{{ $pricePerDay }}">
                    <input type="hidden" name="total_amount" id="total_amount_input">
                    <input type="hidden" name="deposit_amount" value="{{ $car->deposit ?? 50 }}">

                    {{-- Compact Time & Date Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        {{-- Start Date --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pickup Schedule</label>
                            <div class="flex gap-2">
                                <input type="date" name="start_date" id="start_date" value="{{ $prePickupDate }}" 
                                    class="flex-1 border-gray-200 rounded-lg text-sm py-2 px-3 focus:ring-red-500 focus:border-red-500" required onchange="calculateTotal()">
                                <select name="start_time" class="border-gray-200 rounded-lg text-sm py-2 px-3">
                                    @for($h=9; $h<=17; $h++)
                                        @foreach(['00', '30'] as $m)
                                            @php $t = sprintf('%02d:%s', $h, $m); @endphp
                                            <option value="{{ $t }}" {{ $prePickupTime == $t ? 'selected' : '' }}>{{ date('g:i A', strtotime($t)) }}</option>
                                        @endforeach
                                    @endfor
                                </select>
                            </div>
                        </div>
                        {{-- End Date --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Return Schedule</label>
                            <div class="flex gap-2">
                                <input type="date" name="end_date" id="end_date" value="{{ $preReturnDate }}" 
                                    class="flex-1 border-gray-200 rounded-lg text-sm py-2 px-3 focus:ring-red-500 focus:border-red-500" required onchange="calculateTotal()">
                                <select name="end_time" class="border-gray-200 rounded-lg text-sm py-2 px-3">
                                    @for($h=9; $h<=17; $h++)
                                        @foreach(['00', '30'] as $m)
                                            @php $t = sprintf('%02d:%s', $h, $m); @endphp
                                            <option value="{{ $t }}" {{ $preReturnTime == $t ? 'selected' : '' }}>{{ date('g:i A', strtotime($t)) }}</option>
                                        @endforeach
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-6 border-gray-100">

                    {{-- 
                        MASTER LOCATION SECTION (Merged x-data) 
                    --}}
                    <div x-data="{
                        lists: {
                            'College': ['Kolej Datin Seri Endon', 'Kolej Dato Onn Jaafar', 'Kolej Tuanku Canselor', 'Kolej Tun Dr Ismail', 'Kolej Tun Fatimah', 'Kolej Tun Hussein Onn', 'Kolej Tun Razak', 'Kolej 9', 'Kolej 10'],
                            'Faculty': ['Faculty of Computing', 'Faculty of Science', 'Faculty of Engineering', 'Faculty of Built Environment', 'Faculty of Management'],
                            'Office': ['Student Mall']
                        },
                        pickup: { category: '', location: '', specifics: '{{ $prePickupLocRaw }}' },
                        dropoff: { category: '', location: '', specifics: '{{ $preReturnLocRaw }}' }
                    }" class="space-y-6">

                        {{-- 1. Pickup Location --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                                1. Pickup Location <span class="text-red-500">*</span>
                            </label>
                            
                            {{-- Category Buttons --}}
                            <div class="flex gap-2 mb-2">
                                <template x-for="(items, key) in lists" :key="key">
                                    <button type="button" @click="pickup.category = key; pickup.location = ''"
                                        :class="pickup.category === key ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200 hover:border-red-300'"
                                        class="flex-1 py-1.5 rounded-lg border text-xs font-bold transition-all"
                                        x-text="key"></button>
                                </template>
                            </div>

                            {{-- Dropdown --}}
                            <div x-show="pickup.category" x-transition.opacity.duration.300ms class="mb-2">
                                <select x-model="pickup.location" class="w-full border-gray-200 rounded-lg py-2 px-3 text-sm bg-gray-50">
                                    <option value="" disabled selected>Select <span x-text="pickup.category"></span></option>
                                    <template x-for="item in lists[pickup.category]" :key="item">
                                        <option :value="item" x-text="item"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Specifics --}}
                            <input type="text" x-model="pickup.specifics" placeholder="Specific details (e.g. Lobby)" 
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                            
                            <input type="hidden" name="pickup_location" :value="(pickup.location ? pickup.location : pickup.category) + (pickup.specifics ? ' - ' + pickup.specifics : '')">
                        </div>

                        {{-- 2. Return Location (Depends on Pickup) --}}
                        <div x-show="pickup.category !== ''" x-transition.duration.500ms>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">
                                2. Return Location <span class="text-red-500">*</span>
                            </label>
                            
                            {{-- Category Buttons --}}
                            <div class="flex gap-2 mb-2">
                                <template x-for="(items, key) in lists" :key="key">
                                    <button type="button" @click="dropoff.category = key; dropoff.location = ''"
                                        :class="dropoff.category === key ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200 hover:border-red-300'"
                                        class="flex-1 py-1.5 rounded-lg border text-xs font-bold transition-all"
                                        x-text="key"></button>
                                </template>
                            </div>

                            {{-- Dropdown --}}
                            <div x-show="dropoff.category" x-transition class="mb-2">
                                <select x-model="dropoff.location" class="w-full border-gray-200 rounded-lg py-2 px-3 text-sm bg-gray-50">
                                    <option value="" disabled selected>Select <span x-text="dropoff.category"></span></option>
                                    <template x-for="item in lists[dropoff.category]" :key="item">
                                        <option :value="item" x-text="item"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Specifics --}}
                            <input type="text" x-model="dropoff.specifics" placeholder="Specific details (e.g. Car Park)" 
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                            
                            <input type="hidden" name="return_location" :value="(dropoff.location ? dropoff.location : dropoff.category) + (dropoff.specifics ? ' - ' + dropoff.specifics : '')">
                        </div>
                    </div>

                    {{-- Compact Total Display --}}
                    <div class="mt-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="flex justify-between items-center text-sm">
                            <div class="space-y-1">
                                <div class="text-xs font-bold text-gray-400">Estimated Total</div>
                                <div class="text-gray-500">Rental (RM{{ $pricePerDay }})</div>
                                <div class="text-gray-500">Deposit (RM50)</div>
                                <div class="text-gray-500">Delivery Fee (RM15)</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold text-gray-400 uppercase">Total Payable</div>
                                <span class="text-2xl font-black text-red-600">RM <span id="total_amount_display">0.00</span></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold text-lg transition shadow-md">
                        Proceed to Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function calculateTotal() {
            const startVal = document.getElementById('start_date').value;
            const endVal = document.getElementById('end_date').value;
            const pricePerDay = parseFloat("{{ $pricePerDay }}");
            const deposit = parseFloat("{{ $car->deposit ?? 50 }}");
            const deliveryFee = 15;

            if (startVal && endVal) {
                const start = new Date(startVal);
                const end = new Date(endVal);
                const diffTime = end - start;
                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                
                if (diffDays <= 0) diffDays = 1;

                const total = (diffDays * pricePerDay) + deposit + deliveryFee;
                document.getElementById('total_amount_display').innerText = total.toFixed(2);
                document.getElementById('total_amount_input').value = total.toFixed(2);
            }
        }
        window.onload = calculateTotal;
    </script>
</x-app-layout>
{{-- resources/views/bookings/partials/show_modal.blade.php --}}
@php
    $vehicleImage = 'default-car.png';
    $vehicleName = 'Vehicle';
    $vehicleType = 'Car';
    
    if ($booking->fleet) {
        $fleet = $booking->fleet;
        $vehicleName = $fleet->modelName;
        $modelName = strtolower($fleet->modelName);
        $year = $fleet->year ?? 0;
        
        if (strpos($modelName, 'axia') !== false) {
            $vehicleImage = $year == 2024 ? 'axia-2024.png' : 'axia-2018.png';
            $vehicleType = 'Hatchback';
        } elseif (strpos($modelName, 'bezza') !== false) {
            $vehicleImage = 'bezza-2018.png';
            $vehicleType = 'Sedan';
        } elseif (strpos($modelName, 'myvi') !== false) {
            $vehicleImage = $year >= 2020 ? 'myvi-2020.png' : 'myvi-2015.png';
            $vehicleType = 'Hatchback';
        } elseif (strpos($modelName, 'saga') !== false) {
            $vehicleImage = 'saga-2017.png';
            $vehicleType = 'Sedan';
        } elseif (strpos($modelName, 'alza') !== false) {
            $vehicleImage = 'alza-2019.png';
            $vehicleType = 'MPV';
        } elseif (strpos($modelName, 'aruz') !== false) {
            $vehicleImage = 'aruz-2020.png';
            $vehicleType = 'SUV';
        } elseif (strpos($modelName, 'vellfire') !== false) {
            $vehicleImage = 'vellfire-2020.png';
            $vehicleType = 'MPV';
        } elseif (strpos($modelName, 'x50') !== false) {
            $vehicleImage = 'x50-2024.png';
            $vehicleType = 'SUV';
        } elseif (strpos($modelName, 'y15') !== false) {
            $vehicleImage = 'y15zr-2023.png';
            $vehicleType = 'Motorcycle';
        }
    }

    // Status Styling
    $statusStyles = match($booking->bookingStat) {
        'confirmed' => ['bg' => 'bg-green-500', 'text' => 'text-green-100', 'label' => 'Confirmed'],
        'pending'   => ['bg' => 'bg-yellow-500', 'text' => 'text-yellow-100', 'label' => 'Pending Approval'],
        'completed' => ['bg' => 'bg-blue-600', 'text' => 'text-blue-100', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-red-500', 'text' => 'text-red-100', 'label' => 'Cancelled'],
        default     => ['bg' => 'bg-gray-500', 'text' => 'text-white', 'label' => 'Unknown'],
    };
@endphp

<div class="relative bg-white font-sans text-gray-800 overflow-hidden">
    
    {{-- 1. HEADER STRIP --}}
    <div class="bg-gray-900 px-8 py-6 text-white flex justify-between items-center relative overflow-hidden">
        {{-- Decorative accent --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-600 rounded-full blur-3xl opacity-20 -mr-10 -mt-10"></div>
        
        <div class="relative z-10">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Booking Reference</p>
            <h2 class="text-3xl font-black tracking-tighter font-mono text-white">#{{ $booking->bookingID }}</h2>
        </div>
        
        <div class="relative z-10 flex items-center gap-3">
            <div class="px-4 py-1.5 rounded-full {{ $statusStyles['bg'] }} bg-opacity-20 border border-white/10 backdrop-blur-sm">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['bg'] }} animate-pulse"></span>
                    <span class="text-xs font-bold {{ $statusStyles['text'] }} uppercase tracking-wider">{{ $statusStyles['label'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- 2. LEFT COLUMN: Vehicle Spotlight (7 Cols) --}}
            <div class="lg:col-span-7 flex flex-col justify-between">
                <div>
                    <div class="flex items-baseline justify-between border-b border-gray-100 pb-4 mb-6">
                        <div>
                            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Vehicle Selected</p>
                            <h3 class="text-3xl font-black text-gray-900">{{ $vehicleName }}</h3>
                        </div>
                        <span class="text-sm font-medium text-gray-400 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">{{ $vehicleType }}</span>
                    </div>

                    {{-- Image Container --}}
                    <div class="relative group w-full h-64 flex items-center justify-center bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden mb-6">
                        <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-30"></div>
                        <img src="{{ asset('images/' . $vehicleImage) }}" 
                             alt="{{ $vehicleName }}" 
                             class="relative z-10 w-4/5 object-contain transform group-hover:scale-105 transition-transform duration-500 drop-shadow-2xl">
                    </div>
                </div>

                {{-- Plate Number --}}
                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100 w-fit">
                    <div class="w-8 h-8 rounded-lg bg-gray-900 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">License Plate</p>
                        <p class="text-sm font-black font-mono text-gray-900">{{ $booking->fleet->plateNumber ?? $booking->plateNumber }}</p>
                    </div>
                </div>
            </div>

            {{-- 3. RIGHT COLUMN: Details & Timeline (5 Cols) --}}
            <div class="lg:col-span-5 space-y-8">
                
                {{-- Renter Info --}}
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600 border border-red-100 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Rented By</p>
                        <p class="text-lg font-bold text-gray-900 leading-tight">{{ $booking->customer->name ?? $booking->matricNum }}</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $booking->matricNum }}</p>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Vertical Timeline (BIGGER & BOLDER) --}}
                <div class="relative pl-4 py-2">
                    {{-- Vertical Line --}}
                    <div class="absolute left-[22px] top-6 bottom-10 w-1 bg-gray-200"></div>

                    {{-- Pickup --}}
                    <div class="relative flex items-start gap-6 mb-10 group">
                        <div class="relative z-10 w-12 h-12 rounded-full border-4 border-white bg-gray-900 shadow-lg flex items-center justify-center group-hover:bg-red-600 transition-colors shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Pickup</p>
                            <p class="text-2xl font-black text-gray-900 leading-none">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('D, d M Y') }}</p>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-base text-gray-600 mt-2">
                                <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-sm font-bold w-fit">{{ \Carbon\Carbon::parse($booking->pickupDate)->format('H:i') }}</span>
                                <span class="hidden sm:inline text-gray-300">|</span>
                                <span class="font-medium">{{ $booking->pickupLoc }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Return --}}
                    <div class="relative flex items-start gap-6 group">
                        <div class="relative z-10 w-12 h-12 rounded-full border-4 border-white bg-gray-200 shadow-lg flex items-center justify-center group-hover:bg-red-600 group-hover:text-white text-gray-500 transition-all shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Return</p>
                            <p class="text-2xl font-black text-gray-900 leading-none">{{ \Carbon\Carbon::parse($booking->returnDate)->format('D, d M Y') }}</p>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-base text-gray-600 mt-2">
                                <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-sm font-bold w-fit">{{ \Carbon\Carbon::parse($booking->returnDate)->format('H:i') }}</span>
                                <span class="hidden sm:inline text-gray-300">|</span>
                                <span class="font-medium">{{ $booking->returnLoc }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. FOOTER: Action Bar --}}
    {{-- CUSTOMER DOCS & INSPECTIONS --}}
    <div class="p-6 border-t border-gray-100 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-lg border border-gray-100">
                <h4 class="text-sm font-bold mb-2">Customer</h4>
                <p class="text-sm text-gray-700 font-bold">{{ $booking->customer->name ?? $booking->matricNum }}</p>
                <p class="text-xs text-gray-500">{{ $booking->customer->email ?? 'N/A' }}</p>
                <p class="text-xs text-gray-500">{{ $booking->customer->phoneNum ?? 'N/A' }}</p>

                <div class="mt-3">
                    <p class="text-xs font-bold text-gray-400 uppercase">Documents</p>
                    <div class="mt-2 flex flex-col gap-2">
                        @if(!empty($booking->customer->doc_ic_passport))
                            <a target="_blank" href="{{ asset('storage/' . $booking->customer->doc_ic_passport) }}" class="text-xs text-blue-600 hover:underline">View IC / Passport</a>
                        @endif
                        @if(!empty($booking->customer->doc_matric))
                            <a target="_blank" href="{{ asset('storage/' . $booking->customer->doc_matric) }}" class="text-xs text-blue-600 hover:underline">View Matric Card</a>
                        @endif
                        @if(!empty($booking->customer->doc_license))
                            <a target="_blank" href="{{ asset('storage/' . $booking->customer->doc_license) }}" class="text-xs text-blue-600 hover:underline">View Driving License</a>
                        @endif
                        @if(empty($booking->customer->doc_ic_passport) && empty($booking->customer->doc_matric) && empty($booking->customer->doc_license))
                            <div class="text-xs text-gray-400">No documents uploaded</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg border border-gray-100">
                <h4 class="text-sm font-bold mb-2">Inspections</h4>
                @php
                    $pickup = $booking->inspections->firstWhere('type', 'pickup') ?? null;
                    $return = $booking->inspections->firstWhere('type', 'return') ?? null;
                @endphp
                <div class="space-y-3 text-sm text-gray-700">
                    <div>
                        <div class="font-bold">Pickup</div>
                        @if($pickup)
                            <div class="mt-2">
                                @if(!empty($pickup->fuelImage))
                                    <a target="_blank" href="{{ asset('storage/' . $pickup->fuelImage) }}">
                                        <img src="{{ asset('storage/' . $pickup->fuelImage) }}" alt="pickup-fuel" class="w-32 h-20 object-contain border rounded" />
                                    </a>
                                @endif
                                @if(!empty($pickup->signature))
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-400">Signature</p>
                                        <img src="{{ asset('storage/' . $pickup->signature) }}" alt="pickup-signature" class="w-48 h-28 object-contain border rounded mt-1">
                                    </div>
                                @endif
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach(['frontViewImage','backViewImage','leftViewImage','rightViewImage'] as $img)
                                        @if(!empty($pickup->$img))
                                            <a target="_blank" href="{{ asset('storage/' . $pickup->$img) }}">
                                                <img src="{{ asset('storage/' . $pickup->$img) }}" alt="{{ $img }}" class="w-24 h-16 object-contain border rounded" />
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-xs text-gray-400 mt-1">No pickup form submitted</div>
                        @endif
                    </div>

                    <div>
                        <div class="font-bold">Return</div>
                        @if($return)
                            <div class="mt-2">
                                @if(!empty($return->fuelImage))
                                    <a target="_blank" href="{{ asset('storage/' . $return->fuelImage) }}">
                                        <img src="{{ asset('storage/' . $return->fuelImage) }}" alt="return-fuel" class="w-32 h-20 object-contain border rounded" />
                                    </a>
                                @endif
                                @if(!empty($return->signature))
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-400">Signature</p>
                                        <img src="{{ asset('storage/' . $return->signature) }}" alt="return-signature" class="w-48 h-28 object-contain border rounded mt-1">
                                    </div>
                                @endif
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach(['frontViewImage','backViewImage','leftViewImage','rightViewImage'] as $img)
                                        @if(!empty($return->$img))
                                            <a target="_blank" href="{{ asset('storage/' . $return->$img) }}">
                                                <img src="{{ asset('storage/' . $return->$img) }}" alt="{{ $img }}" class="w-24 h-16 object-contain border rounded" />
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-xs text-gray-400 mt-1">No return form submitted</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-50 border-t border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
        
        {{-- Price Display --}}
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div class="text-center md:text-left">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Total Amount</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-sm font-semibold text-gray-500">RM</span>
                    <span class="text-2xl font-black text-gray-900 tracking-tight">{{ number_format($booking->totalPrice, 2) }}</span>
                </div>
            </div>
            
            @if(($booking->deposit ?? 0) > 0)
                <div class="hidden md:block w-px h-8 bg-gray-200"></div>
                <div class="text-center md:text-left">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Deposit Included</p>
                    <p class="text-sm font-bold text-gray-600 font-mono">RM {{ number_format($booking->deposit, 2) }}</p>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 w-full md:w-auto">
            
            {{-- VIEW RECEIPT BUTTON --}}
            @if($booking->paymentReceipt)
                <a href="{{ asset('storage/' . $booking->paymentReceipt) }}" target="_blank" 
                   class="flex-1 md:flex-none px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:border-gray-400 hover:text-gray-900 transition-all shadow-sm flex items-center justify-center gap-2 group">
                   <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                   View Receipt
                </a>
            @else
                <button disabled class="flex-1 md:flex-none px-5 py-2.5 text-sm font-bold text-gray-400 bg-gray-50 border border-gray-100 rounded-lg cursor-not-allowed">
                   No Receipt
                </button>
            @endif

            @if($booking->bookingStat !== 'cancelled' && $booking->bookingStat !== 'completed')
                <form action="{{ route('staff.fleet.bookings.cancel', $booking->bookingID) }}" method="POST" class="flex-1 md:flex-none">
                    @csrf
                    <button type="submit" onclick="return confirm('Cancel this booking?')" 
                            class="w-full px-5 py-2.5 text-sm font-bold text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-all">
                        Cancel
                    </button>
                </form>
            @endif

            @if($booking->bookingStat === 'pending')
                <form action="{{ route('staff.fleet.bookings.approve', $booking->bookingID) }}" method="POST" class="flex-1 md:flex-none">
                    @csrf
                    <button type="submit" onclick="return confirm('Confirm approval?')" 
                            class="w-full px-6 py-2.5 text-sm font-bold text-white bg-gray-900 border border-gray-900 rounded-lg hover:bg-red-600 hover:border-red-600 transition-all shadow-md flex items-center justify-center gap-2">
                        Approve
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
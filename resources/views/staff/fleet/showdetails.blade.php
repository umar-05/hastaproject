<x-staff-layout>
    <div class="py-10 bg-gray-50 min-h-screen font-sans" x-data="{ 
        activeTab: 'overview',
        calendarMonth: '{{ now()->format('F Y') }}'
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <a href="{{ route('staff.fleet.index') }}" class="group inline-flex items-center text-sm font-medium text-gray-400 hover:text-indigo-600 transition-colors mb-2">
                        <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back to Fleet
                    </a>
                    <div class="flex items-baseline gap-4">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">{{ $fleet->modelName }}</h1>
                        <span class="text-xl md:text-2xl font-bold text-gray-300">#{{ $fleet->plateNumber }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="px-4 py-2 rounded-full flex items-center gap-2 shadow-sm border border-gray-100 bg-white">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 
                                {{ $fleet->status === 'available' ? 'bg-green-400' : ($fleet->status === 'maintenance' ? 'bg-yellow-400' : 'bg-red-400') }}"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 
                                {{ $fleet->status === 'available' ? 'bg-green-500' : ($fleet->status === 'maintenance' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                        </span>
                        <span class="uppercase font-bold tracking-wider text-xs text-gray-700">{{ $fleet->status }}</span>
                    </div>

                    <a href="{{ route('staff.fleet.edit', $fleet->plateNumber) }}" class="p-2.5 text-gray-400 bg-white border border-gray-200 hover:text-indigo-600 hover:border-indigo-200 rounded-xl transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                
                <div class="xl:col-span-2 space-y-8">
                    
                    <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100">
                        <div class="aspect-video bg-gray-50 rounded-2xl overflow-hidden relative flex items-center justify-center mb-4 group">
                             <img src="{{ asset('images/cars/' . ($fleet->image ?? 'default_car.png')) }}" 
                                 alt="{{ $fleet->modelName }}" 
                                 class="w-full h-full object-contain transform transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <div class="flex gap-4 overflow-x-auto pb-2">
                            <button class="w-20 h-16 rounded-xl border-2 border-indigo-500 overflow-hidden flex-shrink-0">
                                <img src="{{ asset('images/cars/' . ($fleet->image ?? 'default_car.png')) }}" class="w-full h-full object-cover">
                            </button>
                            <button class="w-20 h-16 rounded-xl border border-gray-200 overflow-hidden flex-shrink-0 opacity-60 hover:opacity-100 transition-opacity">
                                <img src="{{ asset('images/cars/' . ($fleet->image ?? 'default_car.png')) }}" class="w-full h-full object-cover">
                            </button>
                            <button class="w-20 h-16 rounded-xl border border-gray-200 overflow-hidden flex-shrink-0 opacity-60 hover:opacity-100 transition-opacity">
                                <img src="{{ asset('images/cars/' . ($fleet->image ?? 'default_car.png')) }}" class="w-full h-full object-cover">
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Year</p>
                            <p class="text-lg font-bold text-gray-900">{{ $fleet->year }}</p>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Color</p>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full border border-gray-200" style="background-color: {{ $fleet->color ?? '#000' }}"></div>
                                <p class="text-lg font-bold text-gray-900">{{ $fleet->color ?? 'N/A' }}</p>
                            </div>
                        </div>
                    
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-bold text-gray-900">Availability Calendar</h3>
                            <div class="flex items-center gap-4 bg-gray-50 rounded-lg px-4 py-2">
                                <button class="text-gray-400 hover:text-gray-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                                <span class="text-sm font-bold text-gray-700">{{ now()->format('F Y') }}</span>
                                <button class="text-gray-400 hover:text-gray-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                            </div>
                        </div>

                        <div class="flex gap-6 mb-6 text-xs font-medium text-gray-500">
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-green-100 border border-green-200"></span> Available</div>
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-red-100 border border-red-200"></span> Booked</div>
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-yellow-100 border border-yellow-200"></span> Maintenance</div>
                        </div>

                        <div class="grid grid-cols-7 gap-3 text-center mb-8">
                            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                                <div class="text-xs font-bold text-gray-400 uppercase mb-2">{{ $day }}</div>
                            @endforeach

                            @php
                                $startDay = now()->startOfMonth()->dayOfWeek;
                                $daysInMonth = now()->daysInMonth;
                                $counts = ['available' => 0, 'booked' => 0, 'maintenance' => 0];
                            @endphp

                            @for($i = 0; $i < $startDay; $i++)
                                <div></div>
                            @endfor

                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dateStr = now()->startOfMonth()->addDays($day - 1)->format('Y-m-d');
                                    $status = $availabilityCalendar[$dateStr]['status'] ?? 'available';
                                    $counts[$status]++;
                                    
                                    $styles = match($status) {
                                        'booked' => 'bg-red-50 text-red-600 border-red-100 hover:bg-red-100',
                                        'maintenance' => 'bg-yellow-50 text-yellow-600 border-yellow-100 hover:bg-yellow-100',
                                        default => 'bg-green-50 text-green-600 border-green-100 hover:bg-green-100'
                                    };
                                @endphp
                                <div class="aspect-square flex items-center justify-center rounded-xl border {{ $styles }} text-sm font-bold transition-colors cursor-default">
                                    {{ $day }}
                                </div>
                            @endfor
                        </div>

                        <div class="flex justify-around border-t border-gray-100 pt-6">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $counts['available'] }}</p>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Available Days</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-red-500">{{ $counts['booked'] }}</p>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Booked Days</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-yellow-500">{{ $counts['maintenance'] }}</p>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Maintenance Days</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Booking History</h3>
                        
                        @if($bookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($bookings as $booking)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition-all bg-gray-50/50">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="font-bold text-gray-900">{{ $booking->customer->name ?? 'Guest Customer' }}</p>
                                        <p class="text-xs text-gray-400 font-mono mt-1">ID: #BK-{{ $booking->id }}</p>
                                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                            <span>Out: {{ $booking->pickupDate ? $booking->pickupDate->format('Y-m-d') : 'N/A' }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span>Ret: {{ $booking->returnDate ? $booking->returnDate->format('Y-m-d') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right flex flex-row sm:flex-col items-center sm:items-end justify-between">
                                        <span class="font-bold text-gray-900 block mb-1">RM {{ number_format($booking->totalPrice, 2) }}</span>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $booking->bookingStat === 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $booking->bookingStat }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400 text-sm">No booking history available.</div>
                        @endif
                    </div>
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Maintenance History</h3>
                        @if($maintenances->count() > 0)
                            <div class="space-y-4">
                                @foreach($maintenances as $maintenance)
                                <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-100 bg-gray-50/50">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $maintenance->description }}</p>
                                        <p class="text-xs text-gray-400 font-mono mt-1">ID: #MNT-{{ $maintenance->id }}</p>
                                        <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $maintenance->mDate ? $maintenance->mDate->format('Y-m-d') : 'N/A' }}
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-900">RM {{ number_format($maintenance->cost, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400 text-sm">No maintenance records found.</div>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-gray-900">Owner Information</h3>
                            <a href="#" class="text-gray-300 hover:text-indigo-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                        </div>
                        
                        @if($fleet->owner)
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Name</p>
                                    <p class="font-bold text-gray-900 text-sm">{{ $fleet->owner->ownerName }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">IC Number</p>
                                    <p class="font-bold text-gray-900 text-sm">{{ $fleet->owner->ownerIC }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Phone</p>
                                    <p class="font-bold text-gray-900 text-sm">{{ $fleet->owner->ownerPhoneNum }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Email</p>
                                    <p class="font-bold text-gray-900 text-sm break-words">{{ $fleet->owner->ownerEmail }}</p>
                                </div>
                            </div>
                        @else
                            <div class="py-6 px-4 bg-gray-50 rounded-xl text-center">
                                <p class="text-sm text-gray-500">No owner assigned</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-gray-900">Compliance</h3>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-bold text-gray-700">Road Tax</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $fleet->roadtaxStat === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $fleet->roadtaxStat }}
                                </span>
                            </div>
                            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mb-2">
                                <div class="h-full {{ $fleet->roadtaxStat === 'active' ? 'bg-green-500' : 'bg-red-500' }}" style="width: 100%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400">
                                <span>Expires:</span>
                                <span>{{ $fleet->taxExpirydate ? $fleet->taxExpirydate->format('d M Y') : '-' }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-bold text-gray-700">Insurance</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $fleet->insuranceStat === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $fleet->insuranceStat }}
                                </span>
                            </div>
                            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mb-2">
                                <div class="h-full {{ $fleet->insuranceStat === 'active' ? 'bg-green-500' : 'bg-red-500' }}" style="width: 100%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400">
                                <span>Expires:</span>
                                <span>{{ $fleet->insuranceExpirydate ? $fleet->insuranceExpirydate->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 mb-6">Documents</h3>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach(['Grant', 'Road Tax', 'Insurance'] as $doc)
                            <div class="flex flex-col gap-2">
                                <div class="aspect-[3/4] bg-gray-100 rounded-xl overflow-hidden relative group">
                                    <div class="absolute inset-0 flex items-center justify-center text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="#" class="text-white hover:text-indigo-200"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></a>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-500 uppercase text-center">{{ $doc }}</span>
                                <button class="text-xs border border-indigo-100 text-indigo-600 rounded-lg py-1 hover:bg-indigo-50 transition-colors">Download</button>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-900">Note</h3>
                            <a href="#" class="text-gray-300 hover:text-indigo-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></a>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $fleet->note ?? 'Vehicle in excellent condition' }}</p>
                    </div>

                </div>
            </div>
            
            @if(isset($otherFleets) && count($otherFleets) > 0)
            <div class="mt-12">
                 <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Other cars</h3>
                    <a href="#" class="text-sm text-red-500 hover:text-red-600 font-medium">View all</a>
                 </div>
                 </div>
            @endif

        </div>
    </div>
</x-staff-layout>
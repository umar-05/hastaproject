@extends('staff.fleet.layout')

@section('tab-content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
    
    {{-- LEFT COLUMN: CALENDAR --}}
    <div class="xl:col-span-2 space-y-8">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-bold text-gray-900">Availability Schedule</h3>
                <div class="flex items-center gap-4 bg-gray-50 rounded-lg px-4 py-2">
                    {{-- Displays current month/year based on logic, not just system clock --}}
                    <span class="text-sm font-bold text-gray-700">{{ now()->format('F Y') }}</span>
                </div>
            </div>

            {{-- Legend --}}
            <div class="flex gap-6 mb-6 text-xs font-medium text-gray-500">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-green-100 border border-green-200"></span> Available</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-red-100 border border-red-200"></span> Booked</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-yellow-100 border border-yellow-200"></span> Maintenance</div>
            </div>

            <div class="grid grid-cols-7 gap-3 text-center">
                {{-- Day Headers --}}
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayName)
                    <div class="text-xs font-bold text-gray-400 uppercase mb-2">{{ $dayName }}</div>
                @endforeach

                @php
                    $startOfMonth = now()->startOfMonth();
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                    $daysInMonth = $startOfMonth->daysInMonth;
                    $dayCounters = ['available' => 0, 'booked' => 0, 'maintenance' => 0];
                @endphp

                {{-- Empty slots for previous month days --}}
                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div></div>
                @endfor

                {{-- Actual Calendar Days --}}
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        // Correctly match the key format from FleetController (Y-m-d)
                        $currentLoopDate = now()->startOfMonth()->addDays($day - 1);
                        $dateKey = $currentLoopDate->format('Y-m-d');
                        
                        $dayStatus = $availabilityCalendar[$dateKey]['status'] ?? 'available';
                        
                        // Increment counters for the summary footer
                        if(isset($dayCounters[$dayStatus])) {
                            $dayCounters[$dayStatus]++;
                        }

                        $dayStyles = match($dayStatus) {
                            'booked' => 'bg-red-50 text-red-600 border-red-100',
                            'maintenance' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                            default => 'bg-green-50 text-green-600 border-green-100'
                        };
                    @endphp
                    <div class="aspect-square flex items-center justify-center rounded-xl border {{ $dayStyles }} text-sm font-bold transition-all cursor-default hover:scale-105 shadow-sm">
                        {{ $day }}
                    </div>
                @endfor
            </div>

            {{-- Monthly Summary Stats --}}
            <div class="flex justify-around border-t border-gray-100 mt-8 pt-6">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $dayCounters['available'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Available</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-red-500">{{ $dayCounters['booked'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Booked</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-500">{{ $dayCounters['maintenance'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Maintenance</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: COMPLIANCE & DOCS --}}
    <div class="xl:col-span-1 space-y-6">
        {{-- Road Tax & Insurance Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-6">Compliance Status</h3>

            {{-- Road Tax Section --}}
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-gray-700">Road Tax</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ ($fleet->roadtaxStat ?? 'active') === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $fleet->roadtaxStat ?? 'Active' }}
                    </span>
                </div>
                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mb-2">
                    <div class="h-full bg-green-500" style="width: 100%"></div>
                </div>
                <div class="flex justify-between text-[11px] text-gray-400">
                    <span>Expiry Date:</span>
                    <span class="font-bold text-gray-600">
                        {{-- Added check to ensure taxExpirydate is a Carbon instance --}}
                        {{ ($fleet->taxExpirydate instanceof \Carbon\Carbon) ? $fleet->taxExpirydate->format('d M Y') : 'N/A' }}
                    </span>
                </div>
            </div>

            {{-- Insurance Section --}}
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-gray-700">Insurance</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ ($fleet->insuranceStat ?? 'active') === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $fleet->insuranceStat ?? 'Active' }}
                    </span>
                </div>
                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mb-2">
                    <div class="h-full bg-green-500" style="width: 100%"></div>
                </div>
                <div class="flex justify-between text-[11px] text-gray-400">
                    <span>Expiry Date:</span>
                    <span class="font-bold text-gray-600">
                        {{-- Added check to ensure insuranceExpirydate is a Carbon instance --}}
                        {{ ($fleet->insuranceExpirydate instanceof \Carbon\Carbon) ? $fleet->insuranceExpirydate->format('d M Y') : 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Documents Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-6">Documents</h3>
            <div class="grid grid-cols-3 gap-3">
                @foreach(['Grant', 'Road Tax', 'Insurance'] as $doc)
                <div class="flex flex-col gap-2">
                    <div class="aspect-[3/4] bg-gray-50 border border-gray-100 rounded-xl overflow-hidden relative group">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="absolute inset-0 bg-indigo-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase text-center">{{ $doc }}</span>
                    <button class="text-[10px] font-bold border border-indigo-50 text-indigo-600 rounded-lg py-1 hover:bg-indigo-50 transition-colors">Download</button>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Condition Note Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900">Condition Note</h3>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed">{{ $fleet->note ?? 'Vehicle in excellent condition. Last detailed recently.' }}</p>
        </div>
    </div>
</div>
@endsection
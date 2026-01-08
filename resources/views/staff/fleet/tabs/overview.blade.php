@extends('staff.fleet.layout')

@section('tab-content')
<div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start animate-fade-in-up">
    
    {{-- LEFT COLUMN: CALENDAR (Spans 8 columns) --}}
    <div class="xl:col-span-8">
        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 p-8 relative overflow-hidden">
            {{-- Decorative bg element --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-50 rounded-full blur-3xl -mr-10 -mt-10 opacity-60 pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row items-center justify-between mb-8 relative z-10">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Availability Schedule</h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">Vehicle booking status for the current month</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center gap-3 bg-gray-50 rounded-2xl px-5 py-3 border border-gray-100 shadow-sm">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-lg font-bold text-gray-800">{{ now()->format('F Y') }}</span>
                </div>
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap gap-4 mb-8">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-50 border border-green-100">
                    <span class="w-3 h-3 rounded-full bg-green-500 shadow-sm shadow-green-200"></span>
                    <span class="text-xs font-bold text-green-700 uppercase tracking-wide">Available</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-50 border border-red-100">
                    <span class="w-3 h-3 rounded-full bg-red-600 shadow-sm shadow-red-200"></span>
                    <span class="text-xs font-bold text-red-700 uppercase tracking-wide">Booked</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-yellow-50 border border-yellow-100">
                    <span class="w-3 h-3 rounded-full bg-yellow-500 shadow-sm shadow-yellow-200"></span>
                    <span class="text-xs font-bold text-yellow-700 uppercase tracking-wide">Maintenance</span>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="grid grid-cols-7 gap-3 sm:gap-4 text-center mb-8">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayName)
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $dayName }}</div>
                @endforeach

                @php
                    $startOfMonth = now()->startOfMonth();
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                    $daysInMonth = $startOfMonth->daysInMonth;
                    $dayCounters = ['available' => 0, 'booked' => 0, 'maintenance' => 0];
                @endphp

                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentLoopDate = now()->startOfMonth()->addDays($day - 1);
                        $dateKey = $currentLoopDate->format('Y-m-d');
                        $dayStatus = $availabilityCalendar[$dateKey]['status'] ?? 'available';
                        
                        if(isset($dayCounters[$dayStatus])) {
                            $dayCounters[$dayStatus]++;
                        }

                        // UPDATED: Available days are now green to match legend
                        $dayStyles = match($dayStatus) {
                            'booked' => 'bg-red-600 text-white shadow-lg shadow-red-200 scale-105 border-transparent',
                            'maintenance' => 'bg-yellow-400 text-yellow-900 shadow-md shadow-yellow-100 border-transparent',
                            default => 'bg-green-50 text-green-700 border-green-100 hover:bg-green-100 hover:shadow-md'
                        };
                    @endphp
                    <div class="aspect-square flex items-center justify-center rounded-2xl border-2 {{ $dayStyles }} text-sm font-bold transition-all duration-300 cursor-default" title="{{ $dateKey }} - {{ ucfirst($dayStatus) }}">
                        {{ $day }}
                    </div>
                @endfor
            </div>

            {{-- Summary Footer --}}
            <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-6">
                <div class="text-center p-4 bg-green-50/50 rounded-2xl">
                    <p class="text-3xl font-black text-green-600">{{ $dayCounters['available'] }}</p>
                    <p class="text-[10px] font-bold text-green-800/60 uppercase tracking-widest mt-1">Days Available</p>
                </div>
                <div class="text-center p-4 bg-red-50/50 rounded-2xl">
                    <p class="text-3xl font-black text-red-600">{{ $dayCounters['booked'] }}</p>
                    <p class="text-[10px] font-bold text-red-800/60 uppercase tracking-widest mt-1">Days Booked</p>
                </div>
                <div class="text-center p-4 bg-yellow-50/50 rounded-2xl">
                    <p class="text-3xl font-black text-yellow-600">{{ $dayCounters['maintenance'] }}</p>
                    <p class="text-[10px] font-bold text-yellow-800/60 uppercase tracking-widest mt-1">Days Service</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: INFO & DOCS (Spans 4 columns) --}}
    <div class="xl:col-span-4 space-y-8">
        
        {{-- Compliance Card --}}
        <div class="bg-white rounded-[2rem] shadow-lg border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gray-900 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Compliance Status</h3>
            </div>

            <div class="space-y-6">
                {{-- Road Tax Logic --}}
                @php
                    $rtExpiry = $fleet->roadtaxExpirydate ? \Carbon\Carbon::parse($fleet->roadtaxExpirydate) : null;
                    $rtActive = $fleet->roadtaxActiveDate ? \Carbon\Carbon::parse($fleet->roadtaxActiveDate) : null;
                    $rtStatus = strtolower($fleet->roadtaxStat ?? 'inactive');
                    $rtProgress = ($rtExpiry && $rtActive) ? min(100, max(0, ($rtActive->diffInDays(now()) / ($rtActive->diffInDays($rtExpiry) ?: 1)) * 100)) : 0;
                    $rtColor = ($rtProgress > 90) ? 'bg-red-500' : (($rtProgress > 75) ? 'bg-yellow-500' : 'bg-green-500');
                @endphp
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Road Tax</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $rtStatus === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $fleet->roadtaxStat ?? 'Missing' }}
                        </span>
                    </div>
                    <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden mb-2">
                        <div class="h-full {{ $rtColor }} transition-all duration-1000 ease-out" style="width: {{ $rtProgress }}%"></div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400">Expires: <span class="font-bold text-gray-700">{{ $rtExpiry ? $rtExpiry->format('d M Y') : 'N/A' }}</span></span>
                    </div>
                </div>

                {{-- Insurance Logic --}}
                @php
                    $insExpiry = $fleet->insuranceExpiryDate ? \Carbon\Carbon::parse($fleet->insuranceExpiryDate) : null;
                    $insActive = $fleet->insuranceActiveDate ? \Carbon\Carbon::parse($fleet->insuranceActiveDate) : null;
                    $insStatus = strtolower($fleet->insuranceStat ?? 'inactive');
                    $insProgress = ($insExpiry && $insActive) ? min(100, max(0, ($insActive->diffInDays(now()) / ($insActive->diffInDays($insExpiry) ?: 1)) * 100)) : 0;
                    $insColor = ($insProgress > 90) ? 'bg-red-500' : (($insProgress > 75) ? 'bg-yellow-500' : 'bg-green-500');
                @endphp
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Insurance</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $insStatus === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $fleet->insuranceStat ?? 'Missing' }}
                        </span>
                    </div>
                    <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden mb-2">
                        <div class="h-full {{ $insColor }} transition-all duration-1000 ease-out" style="width: {{ $insProgress }}%"></div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400">Expires: <span class="font-bold text-gray-700">{{ $insExpiry ? $insExpiry->format('d M Y') : 'N/A' }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents Card --}}
        <div class="bg-white rounded-[2rem] shadow-lg border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-red-600 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Official Documents</h3>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @php
                    $docs = ['grantFile' => 'Grant', 'roadtaxFile' => 'Road Tax', 'insuranceFile' => 'Insurance'];
                @endphp

                @foreach($docs as $dbField => $label)
                    <div class="group relative">
                        <div class="aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl overflow-hidden flex flex-col items-center justify-center transition-all duration-300 group-hover:border-red-200 group-hover:shadow-md">
                            
                            @if($fleet->$dbField)
                                @php
                                    $extension = pathinfo($fleet->$dbField, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp

                                @if($isImage)
                                    <img src="{{ asset('storage/' . $fleet->$dbField) }}" alt="{{ $label }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                                @else
                                    <div class="flex flex-col items-center text-gray-400 group-hover:text-red-500 transition-colors">
                                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-xs font-bold uppercase">{{ $extension }}</span>
                                    </div>
                                @endif

                                {{-- Action Overlay --}}
                                <div class="absolute inset-0 bg-gray-900/80 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center gap-3 backdrop-blur-sm">
                                    <button onclick="openViewModal('{{ asset('storage/' . $fleet->$dbField) }}', '{{ $label }}')" class="px-4 py-2 bg-white text-gray-900 rounded-lg text-xs font-bold shadow-lg hover:bg-red-50 transition-colors transform hover:scale-105">
                                        View
                                    </button>
                                    <button onclick="openUploadModal('{{ $dbField }}', '{{ $label }}')" class="px-4 py-2 bg-gray-800 text-gray-200 rounded-lg text-xs font-bold border border-gray-600 hover:bg-gray-700 transition-colors">
                                        Replace
                                    </button>
                                </div>
                            @else
                                {{-- Empty State --}}
                                <button onclick="openUploadModal('{{ $dbField }}', '{{ $label }}')" class="flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Upload</span>
                                </button>
                            @endif
                        </div>
                        <p class="text-center text-xs font-bold text-gray-500 mt-2 group-hover:text-gray-800 transition-colors">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- ================= MODALS ================= --}}

{{-- 1. UPLOAD MODAL --}}
<div id="uploadModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" onclick="closeUploadModal()"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg border border-gray-100">
                <div class="bg-white px-8 py-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900" id="uploadModalTitle">Upload Document</h3>
                            <p class="text-sm text-gray-500">Update file for this vehicle.</p>
                        </div>
                    </div>

                    <form action="{{ route('staff.fleet.update', $fleet->plateNumber) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-6">
                            <div class="flex items-center justify-center w-full">
                                <label for="fileInput" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-red-50 hover:border-red-300 transition-all group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        <p class="mb-2 text-sm text-gray-500 group-hover:text-red-600"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-400">PDF, PNG, JPG (MAX. 5MB)</p>
                                    </div>
                                    <input type="file" name="grantFile" id="fileInput" class="hidden" required />
                                </label>
                            </div> 
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition-colors" onclick="closeUploadModal()">Cancel</button>
                            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all transform hover:-translate-y-0.5">Upload File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. VIEW MODAL --}}
<div id="viewModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/95 backdrop-blur-md transition-opacity" onclick="closeViewModal()"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-6xl h-[90vh] bg-black rounded-3xl shadow-2xl overflow-hidden flex flex-col border border-gray-800">
                <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-white font-bold text-lg" id="viewModalTitle">Document Viewer</h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-white hover:bg-gray-800 p-2 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 bg-gray-950 flex items-center justify-center relative p-4">
                    <iframe id="docViewer" src="" class="w-full h-full rounded-xl border border-gray-800" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openUploadModal(fieldName, label) {
        document.getElementById('uploadModal').classList.remove('hidden');
        document.getElementById('uploadModalTitle').innerText = 'Upload ' + label;
        const fileInput = document.getElementById('fileInput');
        fileInput.name = fieldName;
        fileInput.value = ''; 
    }
    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }
    function openViewModal(fileUrl, label) {
        document.getElementById('viewModal').classList.remove('hidden');
        document.getElementById('viewModalTitle').innerText = 'Viewing: ' + label;
        document.getElementById('docViewer').src = fileUrl;
    }
    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
        document.getElementById('docViewer').src = ''; 
    }
</script>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
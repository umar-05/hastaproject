@extends('staff.fleet.layout')

@section('tab-content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-10 items-start animate-fade-in">
    
    {{-- LEFT COLUMN: CALENDAR --}}
    <div class="xl:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-10 h-full">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Availability Schedule</h3>
                    <p class="text-base text-gray-500 font-medium mt-1">Overview for {{ now()->format('F Y') }}</p>
                </div>
                
                {{-- Legend --}}
                <div class="flex gap-6 text-sm font-semibold text-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-emerald-100"></span> Available
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500 ring-2 ring-red-100"></span> Booked
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-400 ring-2 ring-orange-100"></span> Maint.
                    </div>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="grid grid-cols-7 gap-4 text-center">
                {{-- Day Headers --}}
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayName)
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ $dayName }}</div>
                @endforeach

                @php
                    $startOfMonth = now()->startOfMonth();
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                    $daysInMonth = $startOfMonth->daysInMonth;
                    $dayCounters = ['available' => 0, 'booked' => 0, 'maintenance' => 0];
                @endphp

                {{-- Empty Slots --}}
                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div></div>
                @endfor

                {{-- Days --}}
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentLoopDate = now()->startOfMonth()->addDays($day - 1);
                        $dateKey = $currentLoopDate->format('Y-m-d');
                        $dayStatus = $availabilityCalendar[$dateKey]['status'] ?? 'available';
                        
                        if(isset($dayCounters[$dayStatus])) {
                            $dayCounters[$dayStatus]++;
                        }

                        // STYLES: Emerald Green for Available
                        $dayClasses = match($dayStatus) {
                            'booked' => 'bg-red-50 text-red-600 border-red-100 hover:bg-red-100',
                            'maintenance' => 'bg-orange-50 text-orange-600 border-orange-100 hover:bg-orange-100',
                            default => 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100'
                        };
                    @endphp
                    
                    <div class="flex items-center justify-center">
                        <div class="w-16 h-16 flex items-center justify-center rounded-2xl border-2 {{ $dayClasses }} text-lg font-bold transition-all duration-200 cursor-default shadow-sm hover:scale-105" 
                             title="{{ $dateKey }}: {{ ucfirst($dayStatus) }}">
                            {{ $day }}
                        </div>
                    </div>
                @endfor
            </div>

            {{-- Footer Stats --}}
            <div class="flex justify-center gap-16 mt-12 pt-8 border-t border-gray-100">
                <div class="text-center">
                    <span class="block text-4xl font-extrabold text-emerald-600">{{ $dayCounters['available'] }}</span>
                    <span class="text-xs uppercase tracking-widest text-gray-400 font-bold mt-1 block">Available</span>
                </div>
                <div class="text-center">
                    <span class="block text-4xl font-extrabold text-red-600">{{ $dayCounters['booked'] }}</span>
                    <span class="text-xs uppercase tracking-widest text-gray-400 font-bold mt-1 block">Booked</span>
                </div>
                <div class="text-center">
                    <span class="block text-4xl font-extrabold text-orange-500">{{ $dayCounters['maintenance'] }}</span>
                    <span class="text-xs uppercase tracking-widest text-gray-400 font-bold mt-1 block">Service</span>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: COMPLIANCE & DOCS --}}
    <div class="xl:col-span-1 space-y-8">
        
        {{-- Status Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8">
            <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-8 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Compliance
            </h3>

            <div class="space-y-8">
                {{-- Road Tax --}}
                @php
                    $rtExpiry = $fleet->roadtaxExpirydate ? \Carbon\Carbon::parse($fleet->roadtaxExpirydate) : null;
                    $rtActive = $fleet->roadtaxActiveDate ? \Carbon\Carbon::parse($fleet->roadtaxActiveDate) : null;
                    $rtProgress = ($rtExpiry && $rtActive) ? min(100, max(0, ($rtActive->diffInDays(now()) / ($rtActive->diffInDays($rtExpiry) ?: 1)) * 100)) : 0;
                    $rtColor = ($rtProgress > 90) ? 'bg-red-500' : 'bg-gray-900';
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-3">
                        <span class="font-semibold text-gray-600">Road Tax</span>
                        <span class="font-bold text-gray-900">{{ $rtExpiry ? $rtExpiry->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $rtColor }} rounded-full transition-all duration-500" style="width: {{ $rtProgress }}%"></div>
                    </div>
                </div>

                {{-- Insurance --}}
                @php
                    $insExpiry = $fleet->insuranceExpiryDate ? \Carbon\Carbon::parse($fleet->insuranceExpiryDate) : null;
                    $insActive = $fleet->insuranceActiveDate ? \Carbon\Carbon::parse($fleet->insuranceActiveDate) : null;
                    $insProgress = ($insExpiry && $insActive) ? min(100, max(0, ($insActive->diffInDays(now()) / ($insActive->diffInDays($insExpiry) ?: 1)) * 100)) : 0;
                    $insColor = ($insProgress > 90) ? 'bg-red-500' : 'bg-gray-900';
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-3">
                        <span class="font-semibold text-gray-600">Insurance</span>
                        <span class="font-bold text-gray-900">{{ $insExpiry ? $insExpiry->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $insColor }} rounded-full transition-all duration-500" style="width: {{ $insProgress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-8">
            <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Documents
            </h3>
            
            <div class="space-y-4">
                @php
                    $docs = ['grantFile' => 'Grant', 'roadtaxFile' => 'Road Tax', 'insuranceFile' => 'Insurance'];
                @endphp

                @foreach($docs as $dbField => $label)
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-gray-200 hover:bg-white transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="p-2.5 bg-white border border-gray-100 rounded-xl text-gray-400 group-hover:text-red-600 transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ $label }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($fleet->$dbField)
                                <button onclick="openViewModal('{{ asset('storage/' . $fleet->$dbField) }}', '{{ $label }}')" class="text-sm font-bold text-gray-900 hover:text-red-600 transition-colors" title="View">
                                    View
                                </button>
                                <span class="text-gray-300">|</span>
                            @endif
                            <button onclick="openUploadModal('{{ $dbField }}', '{{ $label }}')" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors" title="Upload">
                                {{ $fleet->$dbField ? 'Update' : 'Upload' }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- ================= MODALS ================= --}}

{{-- 1. UPLOAD MODAL --}}
<div id="uploadModal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeUploadModal()"></div>
    
    {{-- Centering Container --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all">
                <div class="px-8 py-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900" id="uploadModalTitle">Upload File</h3>
                            <p class="text-sm text-gray-500 mt-1">Select a file to update this document.</p>
                        </div>
                        <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 p-2 rounded-full transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('staff.fleet.update', $fleet->plateNumber) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <label class="block w-full cursor-pointer group mb-8">
                            <div class="flex flex-col items-center justify-center w-full h-48 border-3 border-dashed border-gray-300 rounded-3xl group-hover:border-red-400 group-hover:bg-red-50/30 transition-all bg-gray-50">
                                <div class="p-4 bg-white rounded-full shadow-md mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-600 group-hover:text-red-600">Click to browse file</span>
                                <span class="text-xs text-gray-400 mt-2 font-medium">PDF, JPG, PNG (Max 5MB)</span>
                            </div>
                            <input type="file" name="grantFile" id="fileInput" class="hidden" required />
                        </label>

                        <div class="flex justify-end gap-4">
                            <button type="button" class="px-6 py-3 text-sm font-bold text-gray-600 bg-gray-100 rounded-2xl hover:bg-gray-200 transition" onclick="closeUploadModal()">Cancel</button>
                            <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-gray-900 rounded-2xl hover:bg-black transition shadow-lg shadow-gray-900/20">Upload Document</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. VIEW MODAL --}}
<div id="viewModal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
    {{-- Overlay --}}
    <div class="fixed inset-0 bg-black/90 backdrop-blur-md transition-opacity" onclick="closeViewModal()"></div>
    
    {{-- Centering Container --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            {{-- Modal Panel --}}
            <div class="relative w-full max-w-6xl h-[90vh] bg-gray-900 rounded-3xl shadow-2xl flex flex-col overflow-hidden border border-gray-800">
                <div class="px-8 py-5 border-b border-gray-800 flex justify-between items-center bg-gray-900">
                    <div class="flex items-center gap-4">
                        <div class="p-2 bg-gray-800 rounded-xl">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white" id="viewModalTitle">Document Viewer</h3>
                    </div>
                    <button onclick="closeViewModal()" class="text-gray-500 hover:text-white transition p-2 hover:bg-gray-800 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 bg-black flex items-center justify-center">
                    <iframe id="docViewer" src="" class="w-full h-full border-none" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT FOR MODALS & FIXING POSITION --}}
<script>
    function openUploadModal(fieldName, label) {
        let modal = document.getElementById('uploadModal');
        // FIX: Append to body to break out of relative/transform containers
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.classList.remove('hidden');
        
        document.getElementById('uploadModalTitle').innerText = 'Upload ' + label;
        const fileInput = document.getElementById('fileInput');
        fileInput.name = fieldName;
        fileInput.value = ''; 
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }

    function openViewModal(fileUrl, label) {
        let modal = document.getElementById('viewModal');
        // FIX: Append to body to break out of relative/transform containers
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.classList.remove('hidden');
        
        document.getElementById('viewModalTitle').innerText = label;
        document.getElementById('docViewer').src = fileUrl;
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
        document.getElementById('docViewer').src = ''; 
    }
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
@extends('staff.fleet.layout')

@section('tab-content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
    
    {{-- LEFT COLUMN: CALENDAR --}}
    <div class="xl:col-span-2 space-y-8">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-bold text-gray-900">Availability Schedule</h3>
                <div class="flex items-center gap-4 bg-gray-50 rounded-lg px-4 py-2">
                    <span class="text-sm font-bold text-gray-700">{{ now()->format('F Y') }}</span>
                </div>
            </div>

            <div class="flex gap-6 mb-6 text-xs font-medium text-gray-500">
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-green-100 border border-green-200"></span> Available</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-red-100 border border-red-200"></span> Booked</div>
                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-yellow-100 border border-yellow-200"></span> Maintenance</div>
            </div>

            <div class="grid grid-cols-7 gap-3 text-center">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayName)
                    <div class="text-xs font-bold text-gray-400 uppercase mb-2">{{ $dayName }}</div>
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
                        // Get status from the array passed by Controller
                        $dayStatus = $availabilityCalendar[$dateKey]['status'] ?? 'available';
                        
                        if(isset($dayCounters[$dayStatus])) {
                            $dayCounters[$dayStatus]++;
                        }

                        $dayStyles = match($dayStatus) {
                            'booked' => 'bg-red-50 text-red-600 border-red-100 ring-2 ring-red-100 ring-offset-1',
                            'maintenance' => 'bg-yellow-50 text-yellow-600 border-yellow-100 ring-2 ring-yellow-100 ring-offset-1',
                            default => 'bg-green-50 text-green-600 border-green-100'
                        };
                    @endphp
                    <div class="aspect-square flex items-center justify-center rounded-xl border {{ $dayStyles }} text-sm font-bold transition-all cursor-default shadow-sm" title="{{ $dateKey }} - {{ ucfirst($dayStatus) }}">
                        {{ $day }}
                    </div>
                @endfor
            </div>

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

            {{-- 1. ROAD TAX --}}
            @php
                $rtExpiry = $fleet->roadtaxExpirydate ? \Carbon\Carbon::parse($fleet->roadtaxExpirydate) : null;
                $rtActive = $fleet->roadtaxActiveDate ? \Carbon\Carbon::parse($fleet->roadtaxActiveDate) : null;
                $rtStatus = strtolower($fleet->roadtaxStat ?? 'inactive');
                
                $rtProgress = 0;
                $rtBarColor = 'bg-gray-200';
                
                if ($rtExpiry && $rtActive) {
                    $totalDays = $rtActive->diffInDays($rtExpiry) ?: 1;
                    $daysPassed = $rtActive->diffInDays(now());
                    
                    if (now()->gt($rtExpiry)) {
                        $rtProgress = 100;
                        $rtBarColor = 'bg-red-500';
                    } elseif (now()->lt($rtActive)) {
                        $rtProgress = 0;
                        $rtBarColor = 'bg-blue-500';
                    } else {
                        $rtProgress = ($daysPassed / $totalDays) * 100;
                        $rtBarColor = ($rtProgress > 90) ? 'bg-red-500' : (($rtProgress > 75) ? 'bg-yellow-500' : 'bg-green-500');
                    }
                }
            @endphp

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-gray-700">Road Tax</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                        {{ $rtStatus === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $fleet->roadtaxStat ?? 'Missing' }}
                    </span>
                </div>
                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mb-2">
                    <div class="h-full {{ $rtBarColor }} transition-all duration-500" style="width: {{ $rtProgress }}%"></div>
                </div>
                <div class="flex justify-between text-[11px] text-gray-400">
                    <span>Expiry:</span>
                    <span class="font-bold text-gray-600">{{ $rtExpiry ? $rtExpiry->format('d M Y') : 'N/A' }}</span>
                </div>
            </div>

            {{-- 2. INSURANCE --}}
            @php
                $insExpiry = $fleet->insuranceExpiryDate ? \Carbon\Carbon::parse($fleet->insuranceExpiryDate) : null;
                $insActive = $fleet->insuranceActiveDate ? \Carbon\Carbon::parse($fleet->insuranceActiveDate) : null;
                $insStatus = strtolower($fleet->insuranceStat ?? 'inactive');
                
                $insProgress = 0;
                $insBarColor = 'bg-gray-200';
                
                if ($insExpiry && $insActive) {
                    $totalDays = $insActive->diffInDays($insExpiry) ?: 1;
                    $daysPassed = $insActive->diffInDays(now());
                    
                    if (now()->gt($insExpiry)) {
                        $insProgress = 100;
                        $insBarColor = 'bg-red-500';
                    } elseif (now()->lt($insActive)) {
                        $insProgress = 0;
                        $insBarColor = 'bg-blue-500';
                    } else {
                        $insProgress = ($daysPassed / $totalDays) * 100;
                        $insBarColor = ($insProgress > 90) ? 'bg-red-500' : (($insProgress > 75) ? 'bg-yellow-500' : 'bg-green-500');
                    }
                }
            @endphp

            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-gray-700">Insurance</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                        {{ $insStatus === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $fleet->insuranceStat ?? 'Missing' }}
                    </span>
                </div>
                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden mb-2">
                    <div class="h-full {{ $insBarColor }} transition-all duration-500" style="width: {{ $insProgress }}%"></div>
                </div>
                <div class="flex justify-between text-[11px] text-gray-400">
                    <span>Expiry:</span>
                    <span class="font-bold text-gray-600">{{ $insExpiry ? $insExpiry->format('d M Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- DOCUMENTS SECTION --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-6">Documents</h3>
            <div class="grid grid-cols-3 gap-3">
                
                @php
                    $docs = [
                        'grantFile' => 'Grant',
                        'roadtaxFile' => 'Road Tax',
                        'insuranceFile' => 'Insurance'
                    ];
                @endphp

                @foreach($docs as $dbField => $label)
                    <div class="flex flex-col gap-2">
                        {{-- Preview Thumbnail --}}
                        <div class="aspect-[3/4] bg-gray-100 border border-gray-100 rounded-xl overflow-hidden relative group">
                            
                            @if($fleet->$dbField)
                                @php
                                    $extension = pathinfo($fleet->$dbField, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                @endphp

                                @if($isImage)
                                    {{-- Display Real Image --}}
                                    <img src="{{ asset('storage/' . $fleet->$dbField) }}" alt="{{ $label }}" class="w-full h-full object-cover">
                                @else
                                    {{-- Display Generic Document Icon --}}
                                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 text-gray-400">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-[9px] font-bold uppercase">{{ $extension }}</span>
                                    </div>
                                @endif
                            @else
                                {{-- Empty State --}}
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            
                            {{-- Hover Actions Overlay --}}
                            <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 backdrop-blur-[2px]">
                                @if($fleet->$dbField)
                                    <button onclick="openViewModal('{{ asset('storage/' . $fleet->$dbField) }}', '{{ $label }}')" class="bg-white text-gray-800 p-2 rounded-full shadow hover:bg-gray-100 transform hover:scale-110 transition" title="View Full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                @endif
                                <button onclick="openUploadModal('{{ $dbField }}', '{{ $label }}')" class="bg-blue-600 text-white p-2 rounded-full shadow hover:bg-blue-500 transform hover:scale-110 transition" title="Upload/Replace">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </button>
                            </div>
                        </div>
                        
                        <span class="text-[9px] font-bold text-gray-400 uppercase text-center">{{ $label }}</span>
  
                    </div>
                @endforeach

            </div>
        </div>

        {{-- Condition Note Card has been removed as requested --}}
    </div>
</div>

{{-- ================= MODALS (Unchanged) ================= --}}
{{-- 1. UPLOAD MODAL --}}
<div id="uploadModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeUploadModal()"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="uploadModalTitle">Upload Document</h3>
                            <div class="mt-2"><p class="text-sm text-gray-500">Select a file to upload. This will replace any existing file.</p></div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('staff.fleet.update', $fleet->plateNumber) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="px-6 py-4">
                        <input type="file" name="grantFile" id="fileInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                        <p class="text-xs text-gray-400 mt-2">Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Upload</button>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeUploadModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 2. VIEW MODAL --}}
<div id="viewModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-90 transition-opacity backdrop-blur-sm" onclick="closeViewModal()"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-5xl h-[85vh] bg-black rounded-2xl shadow-2xl overflow-hidden flex flex-col">
                <div class="bg-gray-900 px-4 py-3 flex justify-between items-center border-b border-gray-800">
                    <h3 class="text-white font-bold text-sm" id="viewModalTitle">Document Viewer</h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-white transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="flex-1 bg-gray-800 flex items-center justify-center relative">
                    <iframe id="docViewer" src="" class="w-full h-full" frameborder="0"></iframe>
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
@endsection
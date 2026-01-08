@extends('staff.fleet.layout')

@section('tab-content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Owner Information</h3>
                <p class="text-sm text-gray-500 mt-1">Primary contact and legal ownership details for this vehicle.</p>
            </div>
            @if(!request()->has('edit'))
                <a href="{{ route('staff.fleet.tabs.documents', ['plateNumber' => $fleet->plateNumber, 'edit' => 'true']) }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Details
                </a>
            @endif
        </div>

        <div class="p-8">
            @if(request()->has('edit'))
                {{-- EDIT MODE: Form to update owner fields directly in the Fleet table --}}
                <form action="{{ route('staff.fleet.update', $fleet->plateNumber) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                   {{-- Hidden fields to preserve existing model/year data during update --}}
<input type="hidden" name="modelName" value="{{ $fleet->modelName }}">
<input type="hidden" name="year" value="{{ $fleet->year }}">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-2">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Owner Name</label>
        {{-- CHANGE: Use $fleet->owner->ownerName --}}
        <input type="text" name="ownerName" 
               value="{{ old('ownerName', $fleet->owner->ownerName ?? '') }}" 
               class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 transition-all">
    </div>

    <div class="space-y-2">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NRIC / Company Number</label>
        {{-- CHANGE: Use $fleet->ownerIC or $fleet->owner->ownerIC --}}
        <input type="text" name="ownerIC" 
               value="{{ old('ownerIC', $fleet->ownerIC) }}" 
               class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 transition-all">
    </div>

    <div class="space-y-2">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone Number</label>
        {{-- CHANGE: Use $fleet->owner->ownerPhoneNum (Match your database column) --}}
        <input type="text" name="ownerPhone" 
               value="{{ old('ownerPhone', $fleet->owner->ownerPhoneNum ?? '') }}" 
               class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 transition-all">
    </div>

    <div class="space-y-2">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Address</label>
        {{-- CHANGE: Use $fleet->owner->ownerEmail --}}
        <input type="email" name="ownerEmail" 
               value="{{ old('ownerEmail', $fleet->owner->ownerEmail ?? '') }}" 
               class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 transition-all">
    </div>
</div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('staff.fleet.tabs.documents', $fleet->plateNumber) }}" class="px-6 py-3 text-sm font-bold text-gray-400">Cancel</a>
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                            Submit 
                        </button>
                    </div>
                </form>

            @elseif($fleet->ownerName || $fleet->ownerIC)
                {{-- VIEW MODE: Display the data --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="space-y-1">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Full Name</label>
        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-50">
            {{ $fleet->owner->ownerName ?? 'N/A' }}
        </p>
    </div>
    <div class="space-y-1">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">IC / Registration Number</label>
        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-50 font-mono">
            {{ $fleet->owner->ownerIC ?? $fleet->ownerIC ?? 'N/A' }}
        </p>
    </div>
    <div class="space-y-1">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone Number</label>
        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-50">
            {{ $fleet->owner->ownerPhoneNum ?? 'N/A' }}
        </p>
    </div>
    <div class="space-y-1">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Address</label>
        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-50">
            {{ $fleet->owner->ownerEmail ?? 'N/A' }}
        </p>
    </div>
</div>
            @else
                {{-- EMPTY STATE --}}
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900">No Owner Assigned</h4>
                    <p class="text-sm text-gray-500 max-w-xs mx-auto mt-2">This vehicle currently has no owner information linked to it.</p>
                    <a href="{{ route('staff.fleet.tabs.documents', ['plateNumber' => $fleet->plateNumber, 'edit' => 'true']) }}" class="mt-4 inline-block text-indigo-600 font-bold text-sm">Assign Owner Now →</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
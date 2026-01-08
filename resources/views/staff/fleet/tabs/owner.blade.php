@extends('staff.fleet.layout')

@section('tab-content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header Section --}}
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-900">Owner Information</h3>
            <p class="text-sm text-gray-500 mt-1">Primary contact and legal ownership details for this vehicle.</p>
        </div>

        <div class="p-8">
            {{-- Check if Owner Name exists (using the column name from your Fleet model) --}}
            @if($fleet->ownerName)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Owner Name --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Full Name</label>
                        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            {{ $fleet->ownerName }}
                        </p>
                    </div>

                    {{-- IC / Company Number --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NRIC / Company Number</label>
                        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-100 font-mono">
                            {{ $fleet->ownerIc ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Phone Number --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone Number</label>
                        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            {{ $fleet->ownerPhone ?? 'N/A' }}
                        </p>
                    </div>

                    {{-- Email Address --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Address</label>
                        <p class="text-lg font-bold text-gray-900 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            {{ $fleet->ownerEmail ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900">No Owner Information</h4>
                    <p class="text-sm text-gray-500 max-w-xs mx-auto mt-2">
                        There are no owner details currently recorded for this vehicle.
                    </p>
                    {{-- Optional: Link to edit page if you want to add owner later --}}
                    <a href="{{ route('staff.fleet.edit', $fleet->plateNumber) }}" class="mt-4 inline-block text-indigo-600 font-bold text-sm hover:underline">
                        Edit Vehicle Details &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
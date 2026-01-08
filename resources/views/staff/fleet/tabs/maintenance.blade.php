@extends('staff.fleet.layout')

@section('tab-content')
<div class="space-y-6">

    {{-- HEADER & ADD BUTTON --}}
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Maintenance History</h3>
        
        @if(!request()->has('create'))
            <a href="{{ route('staff.fleet.tabs.maintenance', ['plateNumber' => $fleet->plateNumber, 'create' => 'true']) }}" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Record
            </a>
        @endif
    </div>

    {{-- CREATE FORM (Visible only when ?create=true is in URL) --}}
    @if(request()->has('create'))
        <div class="bg-gray-50 border border-indigo-100 rounded-2xl p-6 mb-6">
            <h4 class="text-sm font-bold text-indigo-900 uppercase tracking-widest mb-4">New Maintenance Entry</h4>
            
            <form action="{{ route('staff.fleet.maintenance.store', $fleet->plateNumber) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    {{-- Description --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Service Description</label>
                        <input type="text" name="description" placeholder="e.g., Oil Change & Filter Replacement" required
                               class="w-full p-3 bg-white rounded-xl border border-gray-200 focus:border-indigo-500 outline-none">
                    </div>
                    
                    {{-- Date --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Date</label>
                        <input type="date" name="mDate" required
                               class="w-full p-3 bg-white rounded-xl border border-gray-200 focus:border-indigo-500 outline-none">
                    </div>

                    {{-- Time --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Time</label>
                        <input type="time" name="mTime" 
                               class="w-full p-3 bg-white rounded-xl border border-gray-200 focus:border-indigo-500 outline-none">
                    </div>

                    {{-- Cost --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Cost (RM)</label>
                        <input type="number" step="0.01" name="cost" placeholder="0.00" required
                               class="w-full p-3 bg-white rounded-xl border border-gray-200 focus:border-indigo-500 outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('staff.fleet.tabs.maintenance', $fleet->plateNumber) }}" class="px-4 py-2 text-gray-500 font-bold text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700">Save Log</button>
                </div>
            </form>
        </div>
    @endif

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        @if($maintenances->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                        <th class="p-6 font-bold">Date</th>
                        <th class="p-6 font-bold">Description</th>
                        <th class="p-6 font-bold text-right">Cost (RM)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($maintenances as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6 text-sm font-medium text-gray-900 whitespace-nowrap">
                                {{ $log->mDate ? $log->mDate->format('d M Y') : '-' }}
                                <span class="block text-xs text-gray-400 font-normal">
                                    {{ $log->mTime ? \Carbon\Carbon::parse($log->mTime)->format('h:i A') : '' }}
                                </span>
                            </td>
                            <td class="p-6 text-sm text-gray-600">
                                {{ $log->description }}
                                <span class="block text-xs text-gray-300 font-mono mt-1">{{ $log->maintenanceID }}</span>
                            </td>
                            <td class="p-6 text-sm font-bold text-gray-900 text-right">
                                RM {{ number_format($log->cost, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            {{-- EMPTY STATE --}}
            <div class="p-12 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-gray-900 font-bold text-sm">No Logs Found</h3>
                <p class="text-gray-500 text-xs mt-1">This vehicle has no recorded maintenance history.</p>
            </div>
        @endif
    </div>
</div>
@endsection
@extends('staff.fleet.layout')

@section('tab-content')
<div class="animate-fade-in-up space-y-8">

    {{-- STATS ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Total Cost --}}
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-lg shadow-gray-200/50 flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-red-50 rounded-full blur-3xl -mr-10 -mt-10 transition-transform group-hover:scale-150"></div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Spent</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">RM {{ number_format($maintenances->sum('cost'), 2) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Last Service --}}
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-lg shadow-gray-200/50 flex items-center justify-between relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-gray-100 rounded-full blur-3xl -mr-10 -mt-10 transition-transform group-hover:scale-150"></div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Last Service</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">
                    {{ $maintenances->first() ? $maintenances->first()->mDate->format('d M Y') : 'N/A' }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-600 relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- ACTION HEADER --}}
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Maintenance Logs</h3>
            <p class="text-sm text-gray-500 font-medium">Service history and repair records.</p>
        </div>
        
        @if(!request()->has('create'))
            <a href="{{ route('staff.fleet.tabs.maintenance', ['plateNumber' => $fleet->plateNumber, 'create' => 'true']) }}" 
               class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-gray-900 font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 hover:bg-red-600 hover:shadow-lg hover:shadow-red-200 hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Record
            </a>
        @endif
    </div>

    {{-- CREATE FORM --}}
    @if(request()->has('create'))
        <div class="bg-white border-2 border-red-50 rounded-[2rem] p-8 shadow-xl relative overflow-hidden animate-fade-in-up">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-50 pointer-events-none"></div>
            
            <div class="flex items-center gap-3 mb-6 relative z-10">
                <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">New Maintenance Entry</h4>
            </div>
            
            <form action="{{ route('staff.fleet.maintenance.store', $fleet->plateNumber) }}" method="POST" class="relative z-10">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Service Description</label>
                        <input type="text" name="description" placeholder="e.g., Oil Change & Brake Pad Replacement" required
                               class="w-full p-4 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 focus:bg-white transition-all font-bold text-gray-900 placeholder-gray-300 outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Date</label>
                        <input type="date" name="mDate" required
                               class="w-full p-4 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 focus:bg-white transition-all font-bold text-gray-900 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Time</label>
                        <input type="time" name="mTime" 
                               class="w-full p-4 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 focus:bg-white transition-all font-bold text-gray-900 outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Cost (RM)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">RM</span>
                            <input type="number" step="0.01" name="cost" placeholder="0.00" required
                                   class="w-full p-4 pl-12 bg-gray-50 rounded-xl border-2 border-transparent focus:border-red-500 focus:bg-white transition-all font-bold text-gray-900 placeholder-gray-300 outline-none text-lg">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('staff.fleet.tabs.maintenance', $fleet->plateNumber) }}" class="px-6 py-3 text-gray-500 font-bold text-sm hover:text-gray-800 transition-colors">Cancel</a>
                    <button type="submit" class="px-8 py-3 bg-red-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-red-200 hover:bg-red-700 hover:-translate-y-1 transition-all">Save Record</button>
                </div>
            </form>
        </div>
    @endif

    {{-- DATA TABLE --}}
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden">
        @if($maintenances->count() > 0)
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Date & Time</th>
                        <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Description</th>
                        <th class="px-8 py-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($maintenances as $log)
                        <tr class="group hover:bg-red-50/20 transition-all duration-300">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-gray-100 text-gray-500 rounded-xl group-hover:bg-white group-hover:text-red-500 group-hover:shadow-md transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $log->mDate ? $log->mDate->format('d M Y') : '-' }}</div>
                                        <div class="text-xs font-bold text-gray-400 mt-0.5">{{ $log->mTime ? \Carbon\Carbon::parse($log->mTime)->format('h:i A') : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-gray-700 leading-snug">{{ $log->description }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 mt-2 rounded-md bg-gray-100 text-gray-500 text-[10px] font-mono border border-gray-200 group-hover:border-red-200 group-hover:text-red-500 transition-colors">
                                    #{{ $log->maintenanceID }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors">
                                    RM {{ number_format($log->cost, 2) }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-24 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 border-2 border-dashed border-green-200">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Excellent Condition!</h3>
                <p class="text-gray-500 mt-2 max-w-sm">No maintenance records found. This vehicle seems to be running smoothly.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
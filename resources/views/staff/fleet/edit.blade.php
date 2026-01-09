@extends('staff.fleet.layout')

@section('tab-content')
<div class="max-w-5xl mx-auto animate-fade-in-up">
    
    <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50 pointer-events-none"></div>

        <div class="bg-gradient-to-r from-gray-50 to-white px-10 py-8 border-b border-gray-100 relative z-10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Vehicle Details</h3>
                    <p class="text-sm text-gray-500 font-medium">Updating information for <span class="font-mono text-indigo-600">{{ $fleet->plateNumber }}</span></p>
                </div>
            </div>
        </div>

        <form action="{{ route('staff.fleet.update', $fleet->plateNumber) }}" method="POST" class="p-10 relative z-10" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="ownerIC" value="{{ $fleet->ownerIC }}">
            <input type="hidden" name="ownerName" value="{{ $fleet->owner->ownerName ?? '' }}">
            <input type="hidden" name="ownerPhone" value="{{ $fleet->owner->ownerPhoneNum ?? '' }}">
            <input type="hidden" name="ownerEmail" value="{{ $fleet->owner->ownerEmail ?? '' }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                
                {{-- LEFT COLUMN: Basic Info --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </span>
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Vehicle Identity</h4>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Model Name</label>
                        <input type="text" name="modelName" value="{{ old('modelName', $fleet->modelName) }}" 
                               class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-bold text-gray-900 placeholder-gray-300 shadow-sm"
                               placeholder="e.g. Proton X50 Flagship">
                        @error('modelName') <p class="text-red-500 text-xs mt-2 ml-1 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Year</label>
                            <input type="number" name="year" value="{{ old('year', $fleet->year) }}" 
                                   class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-bold text-gray-900 text-center shadow-sm"
                                   placeholder="YYYY">
                            @error('year') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        {{-- ADDED: Price Field --}}
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-500 mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Price (RM)</label>
                            <input type="number" name="price" value="{{ old('price', $fleet->price) }}" 
                                   class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-bold text-gray-900 text-center shadow-sm"
                                   placeholder="150">
                            @error('price') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">Color</label>
                        <input type="text" name="color" value="{{ old('color', $fleet->color) }}" 
                               class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-bold text-gray-900 shadow-sm"
                               placeholder="e.g. Jet Grey">
                        @error('color') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- RIGHT COLUMN: Status & Actions --}}
                <div class="space-y-8">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="p-1.5 bg-purple-50 text-purple-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Operational Status</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            {{-- Available --}}
                            <label class="cursor-pointer relative">
                                <input type="radio" name="status" value="available" class="peer sr-only" {{ $fleet->status == 'available' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 border-gray-100 bg-white text-center hover:border-green-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all group">
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-green-700">Available</span>
                                </div>
                            </label>

                            {{-- Booked --}}
                            <label class="cursor-pointer relative">
                                <input type="radio" name="status" value="booked" class="peer sr-only" {{ $fleet->status == 'booked' || $fleet->status == 'rented' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 border-gray-100 bg-white text-center hover:border-blue-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all group">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-blue-700">Booked</span>
                                </div>
                            </label>

                            {{-- Maintenance --}}
                            <label class="cursor-pointer relative">
                                <input type="radio" name="status" value="maintenance" class="peer sr-only" {{ $fleet->status == 'maintenance' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 border-gray-100 bg-white text-center hover:border-yellow-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-all group">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-yellow-700">Maintenance</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-4 pt-4 mt-auto">
                        <a href="{{ route('staff.fleet.tabs.overview', $fleet->plateNumber) }}" class="px-6 py-4 text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold text-sm shadow-xl shadow-gray-200 hover:bg-indigo-600 hover:shadow-indigo-200 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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
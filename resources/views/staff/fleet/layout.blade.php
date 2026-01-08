@extends('staff.fleet.layout')

@section('tab-content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gray-50/50 px-8 py-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-900">Edit Vehicle Details</h3>
            <p class="text-sm text-gray-500 mt-1">Update basic information for {{ $fleet->plateNumber }}</p>
        </div>

        {{-- CHANGE 1: ADD enctype="multipart/form-data" HERE --}}
        <form action="{{ route('staff.fleet.update', $fleet->plateNumber) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Hidden Owner Fields --}}
            <input type="hidden" name="ownerIC" value="{{ $fleet->ownerIC }}">
            <input type="hidden" name="ownerName" value="{{ $fleet->owner->ownerName ?? '' }}">
            <input type="hidden" name="ownerPhone" value="{{ $fleet->owner->ownerPhoneNum ?? '' }}">
            <input type="hidden" name="ownerEmail" value="{{ $fleet->owner->ownerEmail ?? '' }}">

            {{-- CHANGE 2: ADD IMAGE UPLOAD SECTION HERE --}}
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Vehicle Image</label>
                <div class="flex items-center gap-4">
                    {{-- Current Image Preview --}}
                    <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 shrink-0">
                        <img src="{{ asset('images/cars/' . ($fleet->image ?? 'default_car.png')) }}" 
                             class="w-full h-full object-cover">
                    </div>
                    {{-- File Input --}}
                    <input type="file" name="image" accept="image/png, image/jpeg, image/jpg"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Model Name --}}
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Model Name</label>
                <input type="text" name="modelName" value="{{ old('modelName', $fleet->modelName) }}" 
                       class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-bold text-gray-900">
                @error('modelName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Year --}}
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Year</label>
                <input type="number" name="year" value="{{ old('year', $fleet->year) }}" 
                       class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-bold text-gray-900">
                @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Color --}}
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Vehicle Color</label>
                <input type="text" name="color" value="{{ old('color', $fleet->color) }}" 
                       class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-bold text-gray-900">
                @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Current Status</label>
                <select name="status" class="w-full p-4 bg-gray-50 rounded-2xl border border-gray-200 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all font-bold text-gray-900">
                    <option value="active" {{ $fleet->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $fleet->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="under_maintenance" {{ $fleet->status == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('staff.fleet.show', $fleet->plateNumber) }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
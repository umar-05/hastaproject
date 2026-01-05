<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="max-w-3xl w-full mx-auto sm:px-6 lg:px-8">
            
            {{-- Main Modal-style Card --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                
                {{-- Header Section --}}
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-red-600 rounded-2xl text-white shadow-lg shadow-red-200">
                            <i class="fas fa-user-plus text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Add New Customer</h2>
                            <p class="text-sm text-gray-500">Enter customer details below</p>
                        </div>
                    </div>
                    {{-- Close Button --}}
                    <a href="{{ route('staff.customermanagement') }}" class="text-gray-400 hover:text-gray-600 transition p-2">
                        <i class="fas fa-times text-lg"></i>
                    </a>
                </div>

                {{-- Form Section --}}
                <form action="{{ route('staff.customermanagement-crud.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        {{-- Matric Number --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">Matric Number <span class="text-red-500">*</span></label>
                            <input type="text" name="matricNum" value="{{ old('matricNum') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition placeholder-gray-400" 
                                   placeholder="e.g., A20CE1001">
                            @error('matricNum') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Full Name --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition placeholder-gray-400" 
                                   placeholder="e.g., Ahmad Hassan">
                        </div>

                        {{-- IC Number --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">IC Number <span class="text-red-500">*</span></label>
                            <input type="text" name="ic_number" value="{{ old('ic_number') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition placeholder-gray-400" 
                                   placeholder="e.g., 901234-56-7890">
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition placeholder-gray-400" 
                                   placeholder="e.g., student@email.com">
                        </div>

                        {{-- Phone Number --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition placeholder-gray-400" 
                                   placeholder="e.g., +60 12-345 6789">
                        </div>

                        {{-- College Address --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-gray-700">College Address <span class="text-red-500">*</span></label>
                            <textarea name="college_address" rows="3" required
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/30 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition placeholder-gray-400" 
                                      placeholder="e.g., Kolej Kediaman Kinabalu, Universiti Malaya">{{ old('college_address') }}</textarea>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-10 mt-4">
                        <a href="{{ route('staff.customermanagement') }}" 
                           class="px-8 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-all active:scale-95">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-[#B03434] rounded-xl text-sm font-bold text-white hover:bg-red-800 shadow-lg shadow-red-100 transition-all active:scale-95">
                            Add Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.staff>
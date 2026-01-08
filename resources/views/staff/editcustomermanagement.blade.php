<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-2xl font-bold text-gray-900">Edit Customer Information</h2>
                    <p class="text-sm text-gray-500">Updating record for: <span class="font-mono font-bold text-red-600">{{ $customer->matricNum }}</span></p>
                </div>

                {{-- Ensure the update route name matches your web.php --}}
                <form action="{{ route('staff.customermanagement-crud.update', $customer->matricNum) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">IC / Passport Number</label>
                            <input type="text" name="icNum_passport" value="{{ old('icNum_passport', $customer->icNum_passport) }}" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">Phone Number</label>
                            <input type="text" name="phoneNum" value="{{ old('phoneNum', $customer->phoneNum) }}" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase">College Address</label>
                            <textarea name="collegeAddress" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition">{{ old('collegeAddress', $customer->collegeAddress) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-4">
                        {{-- FIX: Check this route name --}}
                        <a href="{{ url('/staff/customermanagement-crud') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700">Cancel</a>
                        
                        <button type="submit" class="px-8 py-2 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-md transition active:scale-95">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.staff>
<x-staff-layout>
    {{-- AlpineJS for interactivity --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }

        /* Custom File Input Styling */
        input[type="file"]::file-selector-button {
            padding: 0.75rem 1.5rem;
            margin-right: 1rem;
            border-radius: 0.75rem;
            border-width: 0;
            background: #f3f4f6;
            color: #1f2937;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        input[type="file"]::file-selector-button:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }
    </style>

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8" x-data="vehicleForm()">
        
        <div class="max-w-7xl mx-auto">
            
            {{-- Header Section --}}
            <div class="mb-10 text-center animate-fade-up">
                <h1 class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-700 to-red-500 tracking-tight mb-2">
                    Add New Vehicle
                </h1>
                <p class="text-2xl text-gray-500 font-medium">Register a fleet unit with style & precision.</p>
            </div>

            @if ($errors->any())
                <div class="mb-8 bg-red-50 border-l-4 border-red-600 p-6 rounded-r-xl shadow-lg animate-fade-up">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-red-800">Submission Error</h3>
                            <ul class="mt-2 list-disc list-inside text-red-700 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('staff.fleet.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

                {{-- 1. VEHICLE DETAILS --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 p-10 border border-gray-100 animate-fade-up delay-100 hover:shadow-2xl transition-shadow duration-300">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white shadow-lg shadow-red-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Vehicle Specifications</h2>
                            <p class="text-gray-500">Core details identifying the car.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-hover:text-red-600 transition-colors">Plate Number</label>
                            <input type="text" name="plateNumber" placeholder="JAV 8888" value="{{ old('plateNumber') }}"
                                class="w-full text-lg font-bold border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all placeholder-gray-300 uppercase">
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-hover:text-red-600 transition-colors">Model Name</label>
                            <input type="text" name="modelName" placeholder="Perodua Ativa" value="{{ old('modelName') }}"
                                class="w-full text-lg font-bold border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all placeholder-gray-300">
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-hover:text-red-600 transition-colors">Year</label>
                            <input type="number" name="year" placeholder="2024" value="{{ old('year') }}"
                                class="w-full text-lg font-bold border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all placeholder-gray-300">
                        </div>
                        <div class="group">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-hover:text-red-600 transition-colors">Color</label>
                            <input type="text" name="color" placeholder="Granite Grey" value="{{ old('color') }}"
                                class="w-full text-lg font-bold border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all placeholder-gray-300">
                        </div>
                    </div>
                </div>

                {{-- 2. PHOTO GALLERY --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 p-10 border border-gray-100 animate-fade-up delay-200">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Visual Gallery</h2>
                            <p class="text-gray-500">Upload high-quality images (Front, Side, Interior).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach(['photo1', 'photo2', 'photo3'] as $photo)
                        <div x-data="{ preview: null }">
                            <label class="relative block w-full aspect-[4/3] rounded-2xl border-2 border-dashed border-gray-300 hover:border-blue-500 hover:bg-blue-50/50 transition-all cursor-pointer group overflow-hidden bg-gray-50">
                                
                                {{-- Placeholder State --}}
                                <div x-show="!preview" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:text-blue-600 transition-colors">
                                    <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    </div>
                                    <span class="font-bold uppercase tracking-widest text-sm">{{ ucfirst($photo) }}</span>
                                </div>

                                {{-- Preview State --}}
                                <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-cover">
                                
                                {{-- Hover Overlay (Only when preview exists) --}}
                                <div x-show="preview" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white font-bold bg-black/50 px-4 py-2 rounded-full backdrop-blur-sm">Change</span>
                                </div>

                                <input type="file" name="{{ $photo }}" class="hidden" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])">
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- 3. DOCUMENTATION GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-up delay-300">
                    
                    {{-- Roadtax Card --}}
                    <div class="bg-gradient-to-br from-yellow-50 to-white rounded-[2rem] p-8 border border-yellow-100 shadow-xl shadow-yellow-100/50 hover:shadow-2xl hover:scale-[1.01] transition-all duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-yellow-400 rounded-xl text-white shadow-lg shadow-yellow-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Roadtax</h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status</label>
                                <div class="relative">
                                    <select name="roadtaxStat" class="w-full text-base font-semibold border-gray-200 rounded-xl py-3 px-4 focus:ring-yellow-400 focus:border-yellow-400 appearance-none bg-white">
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Active Date</label>
                                    <input type="date" name="roadtaxActiveDate" class="w-full text-base border-gray-200 rounded-xl py-3 px-4 focus:ring-yellow-400 focus:border-yellow-400 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Expiry Date</label>
                                    <input type="date" name="roadtaxExpiryDate" class="w-full text-base border-gray-200 rounded-xl py-3 px-4 focus:ring-yellow-400 focus:border-yellow-400 bg-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Document (PDF)</label>
                                <input type="file" name="roadtaxFile" accept=".pdf" class="block w-full text-sm text-gray-500 file:bg-yellow-100 file:text-yellow-800 hover:file:bg-yellow-200 border border-gray-200 rounded-xl bg-white">
                            </div>
                        </div>
                    </div>

                    {{-- Insurance Card --}}
                    <div class="bg-gradient-to-br from-green-50 to-white rounded-[2rem] p-8 border border-green-100 shadow-xl shadow-green-100/50 hover:shadow-2xl hover:scale-[1.01] transition-all duration-300">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 bg-green-500 rounded-xl text-white shadow-lg shadow-green-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Insurance</h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status</label>
                                <div class="relative">
                                    <select name="insuranceStat" class="w-full text-base font-semibold border-gray-200 rounded-xl py-3 px-4 focus:ring-green-500 focus:border-green-500 appearance-none bg-white">
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Active Date</label>
                                    <input type="date" name="insuranceActiveDate" class="w-full text-base border-gray-200 rounded-xl py-3 px-4 focus:ring-green-500 focus:border-green-500 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Expiry Date</label>
                                    <input type="date" name="insuranceExpiryDate" class="w-full text-base border-gray-200 rounded-xl py-3 px-4 focus:ring-green-500 focus:border-green-500 bg-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Document (PDF)</label>
                                <input type="file" name="insuranceFile" accept=".pdf" class="block w-full text-sm text-gray-500 file:bg-green-100 file:text-green-800 hover:file:bg-green-200 border border-gray-200 rounded-xl bg-white">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. VEHICLE GRANT --}}
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-[2rem] shadow-2xl p-8 flex items-center justify-between animate-fade-up delay-300">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-white backdrop-blur-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Vehicle Grant</h3>
                            <p class="text-gray-400">Securely upload the original vehicle grant.</p>
                        </div>
                    </div>
                    <div>
                        <input type="file" name="grantFile" accept=".pdf" class="block w-full text-sm text-gray-300 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-red-600 file:text-white hover:file:bg-red-500 cursor-pointer">
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="flex justify-end gap-6 pt-6 animate-fade-up delay-300">
                    <a href="{{ route('staff.fleet.index') }}" class="px-8 py-4 text-lg font-bold text-gray-600 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 transition shadow-sm">Cancel</a>
                    <button type="submit" class="px-10 py-4 text-lg font-bold text-white bg-gradient-to-r from-red-600 to-red-800 rounded-xl hover:from-red-700 hover:to-red-900 shadow-xl shadow-red-500/30 transform hover:-translate-y-1 transition-all">
                        Register Vehicle
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function vehicleForm() {
            return {
                // Future expansion logic
            }
        }
    </script>
</x-staff-layout>
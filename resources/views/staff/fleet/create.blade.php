<x-staff-layout>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Smooth Entry Animations */
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-slide-up { animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Hide Number Input Arrows (Spinner) */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <script>
    function openUploadModal(fieldName, label) {
        let modal = document.getElementById('uploadModal');
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.classList.remove('hidden');
        document.getElementById('uploadModalTitle').innerText = 'Upload ' + label;
        const fileInput = document.getElementById('fileInput');
        fileInput.name = fieldName;
        fileInput.value = ''; 
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }
    </script>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8 relative" 
     x-data="{ 
        activeTab: 'basic',
        
        // --- OWNER AUTO-FILL LOGIC ---
        ownerIC: '{{ old('ownerIC') }}',
        ownerName: '{{ old('ownerName') }}',
        ownerEmail: '{{ old('ownerEmail') }}',
        ownerPhone: '{{ old('ownerPhoneNum') }}',
        
        isNewOwner: true, 
        isLoading: false,
        
        init() {
            if(this.ownerIC && this.ownerIC.length > 5) {
                this.fetchOwner();
            }
        },

        async fetchOwner() {
            let inputIc = this.ownerIC.trim();

            // Wait for reasonable length
            if (inputIc.length < 6) return;

            this.isLoading = true;

            try {
                // Use Query Parameter to handle special chars safely
                
                
                const response = await fetch(url);
                const result = await response.json();

                if (result.found) {
                    // FOUND: Fill data and lock fields
                    this.ownerName = result.data.ownerName;
                    this.ownerEmail = result.data.ownerEmail;
                    this.ownerPhone = result.data.ownerPhoneNum;
                    this.isNewOwner = false;
                } else {
                    // NOT FOUND: Unlock fields
                    // FIX: Only clear fields if we were previously looking at an existing owner
                    if (this.isNewOwner === false) {
                        this.ownerName = '';
                        this.ownerEmail = '';
                        this.ownerPhone = '';
                    }
                    this.isNewOwner = true;
                }
            } catch (error) {
                console.error('Error fetching owner:', error);
            } finally {
                this.isLoading = false;
            }
        }
     }">
        
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 mt-10 mr-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 mb-10 ml-10 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto space-y-6">
            
            {{-- Header Section --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 animate-slide-up">
                <div>
                    <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
                        Register New Vehicle
                    </h1>
                    <p class="text-sm text-gray-500 mt-2 font-medium">Add a new premium unit to your fleet.</p>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('staff.fleet.index') }}" class="px-6 py-2.5 rounded-xl text-gray-600 font-semibold text-sm bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                        Cancel
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50/80 backdrop-blur-md border border-red-200 p-4 rounded-xl shadow-sm animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <h3 class="text-sm font-bold text-red-900">Please correct the following errors:</h3>
                            <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1">
                                @foreach ($errors->all() as $error)
                                    <span class="text-xs font-medium text-red-600">• {{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Form Card --}}
            <form action="{{ route('staff.fleet.store') }}" method="POST" enctype="multipart/form-data" 
                  class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/50 overflow-hidden flex flex-col md:flex-row animate-slide-up min-h-[600px]"
                  style="animation-delay: 0.2s;">
                @csrf

                {{-- SIDEBAR NAVIGATION --}}
                <div class="w-full md:w-72 bg-gray-50/50 border-r border-gray-100 flex flex-col p-6 shrink-0">
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-2">Navigation</p>
                        
                        @foreach([
                            'basic' => ['label' => 'Basic Details', 'desc' => 'Model, specs & price', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'bg-blue-500'],
                            'gallery' => ['label' => 'Visual Gallery', 'desc' => 'Photos & media', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'bg-purple-500'],
                            'docs' => ['label' => 'Documentation', 'desc' => 'Legal & permits', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-yellow-500'],
                            'owner' => ['label' => 'Owner Info', 'desc' => 'Contact details', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'bg-green-500']
                        ] as $key => $tab)
                        
                        <button type="button" @click="activeTab = '{{ $key }}'"
                            class="group w-full text-left p-3 rounded-xl transition-all duration-300 relative overflow-hidden"
                            :class="activeTab === '{{ $key }}' ? 'bg-white shadow-sm ring-1 ring-black/5' : 'hover:bg-white/50'">
                            
                            <div class="absolute left-0 top-3 bottom-3 w-1 rounded-r-full transition-all duration-300"
                                 :class="activeTab === '{{ $key }}' ? '{{ $tab['color'] }}' : 'bg-transparent'"></div>

                            <div class="flex items-center gap-4 pl-2">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors duration-300"
                                     :class="activeTab === '{{ $key }}' ? '{{ $tab['color'] }} text-white shadow-md' : 'bg-gray-200 text-gray-500 group-hover:bg-white'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" /></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900" :class="activeTab === '{{ $key }}' ? 'text-gray-900' : 'text-gray-600'">{{ $tab['label'] }}</p>
                                    <p class="text-[11px] font-medium text-gray-400" :class="activeTab === '{{ $key }}' ? 'text-gray-500' : 'text-gray-400'">{{ $tab['desc'] }}</p>
                                </div>
                            </div>
                        </button>
                        @endforeach
                    </div>

                    <div class="mt-auto pt-8 border-t border-gray-100">
                        <button type="submit" class="w-full bg-gray-900 text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-gray-900/10 hover:bg-black hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 group text-sm">
                            <span>Save Vehicle</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- CONTENT PANEL --}}
                <div class="flex-1 bg-white">
                    <div class="p-8 md:p-12">
                        
                        {{-- TAB 1: BASIC INFO --}}
                        <div x-show="activeTab === 'basic'" x-cloak
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Basic Information</h2>
                                    <p class="text-gray-500 text-sm mt-1">Primary identification details for the system.</p>
                                </div>
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-5">
                                    {{-- Plate Number --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Plate Number</label>
                                        <input type="text" name="plateNumber" placeholder="JAV 8888" value="{{ old('plateNumber') }}"
                                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-gray-50/50 focus:bg-white text-gray-900 font-medium placeholder-gray-400 uppercase">
                                    </div>

                                    {{-- Model Name --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Model Name</label>
                                        <input type="text" name="modelName" placeholder="e.g. Perodua Ativa" value="{{ old('modelName') }}"
                                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-gray-50/50 focus:bg-white text-gray-900 font-medium placeholder-gray-400">
                                    </div>

                                    {{-- Daily Price --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Daily Price (RM)</label>
                                        <input type="number" name="price" placeholder="150" value="{{ old('price') }}"
                                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-gray-50/50 focus:bg-white text-gray-900 font-medium placeholder-gray-400">
                                    </div>
                                </div>

                                <div class="space-y-5">
                                    {{-- Manufacture Year --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Manufacture Year</label>
                                        <input type="number" name="year" placeholder="2024" value="{{ old('year') }}"
                                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-gray-50/50 focus:bg-white text-gray-900 font-medium placeholder-gray-400">
                                    </div>

                                    {{-- Color --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Color</label>
                                        <input type="text" name="color" placeholder="e.g. Metallic Red" value="{{ old('color') }}"
                                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-gray-50/50 focus:bg-white text-gray-900 font-medium placeholder-gray-400">
                                    </div>

                                    {{-- Status Select --}}
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Current Status</label>
                                        <div class="relative">
                                            <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-gray-50/50 focus:bg-white text-gray-900 font-medium appearance-none cursor-pointer">
                                                <option value="available">Available</option>
                                                <option value="maintenance">Maintenance</option>
                                                <option value="rented">On Road</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 2: GALLERY --}}
                        <div x-show="activeTab === 'gallery'" x-cloak
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Vehicle Gallery</h2>
                                    <p class="text-gray-500 text-sm mt-1">Showcase the vehicle with high-quality images.</p>
                                </div>
                                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach(['photo1' => 'Front View', 'photo2' => 'Side View', 'photo3' => 'Interior'] as $field => $label)
                                <div x-data="{ preview: null }" class="group relative">
                                    <label class="block aspect-[4/3] rounded-2xl border-2 border-dashed border-gray-200 hover:border-purple-500 hover:bg-purple-50/30 transition-all duration-300 cursor-pointer overflow-hidden bg-gray-50 relative">
                                        
                                        {{-- Placeholder --}}
                                        <div x-show="!preview" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:text-purple-600 transition-colors">
                                            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            </div>
                                            <span class="text-xs font-bold uppercase tracking-widest">{{ $label }}</span>
                                            <span class="text-[10px] font-medium mt-1 opacity-60">Click to upload</span>
                                        </div>

                                        {{-- Preview Image --}}
                                        <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                        
                                        {{-- Overlay on Hover --}}
                                        <div x-show="preview" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                            <span class="text-white text-sm font-bold bg-white/20 px-4 py-2 rounded-full border border-white/50 backdrop-blur-md">Change</span>
                                        </div>

                                        <input type="file" name="{{ $field }}" class="hidden" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])">
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- TAB 3: DOCUMENTS --}}
                        <div x-show="activeTab === 'docs'" x-cloak
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Legal Documentation</h2>
                                    <p class="text-gray-500 text-sm mt-1">Manage roadtax, insurance, and grants.</p>
                                </div>
                                <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                                {{-- Roadtax --}}
                                <div class="bg-yellow-50/50 p-6 rounded-2xl border border-yellow-100 hover:shadow-lg hover:border-yellow-200 transition-all duration-300">
                                    <h3 class="font-bold text-yellow-900 mb-4 flex items-center gap-3 text-base">
                                        <div class="p-1.5 bg-yellow-100 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        Roadtax Details
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-yellow-700 uppercase mb-1 block">Active Date</label>
                                                <input type="date" name="roadtaxActiveDate" class="w-full bg-white border-yellow-200 rounded-lg px-3 py-2 text-sm focus:ring-yellow-400 focus:border-yellow-400">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-yellow-700 uppercase mb-1 block">Expiry Date</label>
                                                <input type="date" name="roadtaxExpiryDate" class="w-full bg-white border-yellow-200 rounded-lg px-3 py-2 text-sm focus:ring-yellow-400 focus:border-yellow-400">
                                            </div>
                                        </div>
                                        
                                        {{-- Row for Status & File --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-yellow-700 uppercase mb-1 block">Status</label>
                                                <div class="relative">
                                                    <select name="roadtaxStat" class="w-full bg-white border-yellow-200 rounded-lg px-3 py-2 text-sm focus:ring-yellow-400 focus:border-yellow-400 appearance-none cursor-pointer">
                                                        <option value="Active">Available</option>
                                                        <option value="Inactive">Unavailable</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-yellow-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-yellow-700 uppercase mb-1 block">Upload PDF</label>
                                                <input type="file" name="roadtaxFile" class="block w-full text-sm text-yellow-700 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-yellow-100 file:text-yellow-700 hover:file:bg-yellow-200 transition-all cursor-pointer bg-white rounded-lg border border-yellow-200">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Insurance --}}
                                <div class="bg-green-50/50 p-6 rounded-2xl border border-green-100 hover:shadow-lg hover:border-green-200 transition-all duration-300">
                                    <h3 class="font-bold text-green-900 mb-4 flex items-center gap-3 text-base">
                                        <div class="p-1.5 bg-green-100 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        </div>
                                        Insurance Policy
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-green-700 uppercase mb-1 block">Start Date</label>
                                                <input type="date" name="insuranceActiveDate" class="w-full bg-white border-green-200 rounded-lg px-3 py-2 text-sm focus:ring-green-400 focus:border-green-400">
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-green-700 uppercase mb-1 block">End Date</label>
                                                <input type="date" name="insuranceExpiryDate" class="w-full bg-white border-green-200 rounded-lg px-3 py-2 text-sm focus:ring-green-400 focus:border-green-400">
                                            </div>
                                        </div>
                                        
                                        {{-- Row for Status & File --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-xs font-bold text-green-700 uppercase mb-1 block">Status</label>
                                                <div class="relative">
                                                    <select name="insuranceStat" class="w-full bg-white border-green-200 rounded-lg px-3 py-2 text-sm focus:ring-green-400 focus:border-green-400 appearance-none cursor-pointer">
                                                        <option value="Active">Available</option>
                                                        <option value="Inactive">Unavailable</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-green-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-bold text-green-700 uppercase mb-1 block">Upload PDF</label>
                                                <input type="file" name="insuranceFile" class="block w-full text-sm text-green-700 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 transition-all cursor-pointer bg-white rounded-lg border border-green-200">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Grant --}}
                                <div class="xl:col-span-2 bg-gray-50 p-6 rounded-2xl border-2 border-dashed border-gray-300 hover:border-gray-400 hover:bg-gray-100 transition-all duration-300 flex items-center justify-between group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-600 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-sm">Original Vehicle Grant</h3>
                                            <p class="text-xs text-gray-500">Must be a clear PDF scan of the main page.</p>
                                        </div>
                                    </div>
                                    <input type="file" name="grantFile" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-black transition-all cursor-pointer">
                                </div>
                            </div>
                        </div>

                        {{-- TAB 4: OWNER INFO --}}
                        <div x-show="activeTab === 'owner'" x-cloak 
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0 translate-y-2" 
                             x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Owner Profile</h2>
                                    <p class="text-gray-500 text-sm mt-1">Vehicle ownership details for records.</p>
                                </div>
                                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 rounded-3xl border border-green-100 mb-8 relative overflow-hidden">
                                
                                {{-- Loading Spinner --}}
                                <div x-show="isLoading" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-10" x-transition.opacity>
                                    <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-green-800 uppercase tracking-wide mb-2">Owner IC / Passport ID</label>
                                        <div class="relative">
                                            <input type="text" name="ownerIC" 
                                                   x-model="ownerIC" 
                                                   @input.debounce.500ms="fetchOwner()" 
                                                   placeholder="e.g. 990101-14-1234" 
                                                   class="w-full font-medium border border-green-200 rounded-xl px-4 py-3 focus:ring-green-500 focus:border-green-500 bg-white/80 backdrop-blur-sm hover:bg-white transition-all">
                                            
                                            {{-- Indicators --}}
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                                                <span x-show="!isNewOwner && !isLoading" class="flex items-center text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 
                                                    Found
                                                </span>
                                                <span x-show="isNewOwner && ownerIC.length > 5 && !isLoading" class="flex items-center text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded-full shadow-sm">
                                                    New Owner
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-green-800 uppercase tracking-wide mb-2">Full Legal Name</label>
                                        <input type="text" name="ownerName" 
                                               x-model="ownerName" 
                                               placeholder="Owner Name" 
                                               :readonly="!isNewOwner" 
                                               :class="!isNewOwner ? 'bg-gray-100 cursor-not-allowed text-gray-500' : 'bg-white/80 hover:bg-white'" 
                                               class="w-full font-medium border border-green-200 rounded-xl px-4 py-3 focus:ring-green-500 focus:border-green-500 backdrop-blur-sm transition-all">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-green-800 uppercase tracking-wide mb-2">Email Address</label>
                                            <input type="email" name="ownerEmail" 
                                                   x-model="ownerEmail" 
                                                   placeholder="owner@email.com" 
                                                   :readonly="!isNewOwner" 
                                                   :class="!isNewOwner ? 'bg-gray-100 cursor-not-allowed text-gray-500' : 'bg-white/80 hover:bg-white'" 
                                                   class="w-full font-medium border border-green-200 rounded-xl px-4 py-3 focus:ring-green-500 focus:border-green-500 backdrop-blur-sm transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-green-800 uppercase tracking-wide mb-2">Phone Number</label>
                                            <input type="text" name="ownerPhoneNum" 
                                                   x-model="ownerPhone" 
                                                   placeholder="+6012-3456789" 
                                                   :readonly="!isNewOwner" 
                                                   :class="!isNewOwner ? 'bg-gray-100 cursor-not-allowed text-gray-500' : 'bg-white/80 hover:bg-white'" 
                                                   class="w-full font-medium border border-green-200 rounded-xl px-4 py-3 focus:ring-green-500 focus:border-green-500 backdrop-blur-sm transition-all">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 bg-white border border-gray-100 rounded-2xl flex items-start gap-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg flex-shrink-0 mt-1">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm mb-1">System Intelligence</h4>
                                    <p class="text-sm text-gray-500 leading-relaxed">
                                        <span x-show="isNewOwner">This IC does not exist in our records. Please fill in the details above to register a <strong>new owner</strong>.</span>
                                        <span x-show="!isNewOwner">Owner found! The details have been <strong>auto-filled</strong> from the database. These fields are read-only to ensure consistency.</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</x-staff-layout>
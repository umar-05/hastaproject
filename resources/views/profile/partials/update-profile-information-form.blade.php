<section 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 100)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500 transform"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 max-w-5xl mx-auto"
>
    <header class="mb-10 border-b border-gray-100 pb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('Profile Settings') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Manage your personal information, address, and emergency contacts.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        {{-- Personal Details --}}
        <div class="mb-10">
            <div class="flex items-center mb-6">
                <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#bb1419] mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Personal Details</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="matricNum" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Matric Number</label>
                    <input id="matricNum" name="matricNum" type="text" 
                        class="block w-full bg-gray-50 border border-gray-200 text-gray-500 font-mono text-sm rounded-lg py-3 px-4 focus:outline-none cursor-not-allowed" 
                        value="{{ old('matricNum', $user->matricNum) }}" readonly />
                </div>

                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Full Name</label>
                    <x-text-input id="name" name="name" type="text" 
                        class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                        :value="old('name', $user->name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Email Address</label>
                    <x-text-input id="email" name="email" type="email" 
                        class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                        :value="old('email', $user->email)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div>
                    <label for="phoneNum" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Phone Number</label>
                    <x-text-input id="phoneNum" name="phoneNum" type="text" 
                        class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                        :value="old('phoneNum', $user->phoneNum)" required />
                </div>

                <div>
                    <label for="faculty" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Faculty</label>
                    <x-text-input id="faculty" name="faculty" type="text" 
                        class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                        :value="old('faculty', $user->faculty)" required />
                </div>

                <div>
                    <label for="icNum_passport" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">IC Number / Passport</label>
                    <x-text-input id="icNum_passport" name="icNum_passport" type="text" 
                        class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                        :value="old('icNum_passport', $user->icNum_passport)" required />
                </div>
            </div>
        </div>

        <hr class="border-gray-100 mb-10">

        {{-- Address Information --}}
        <div class="mb-10">
            <div class="flex items-center mb-6">
                <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#bb1419] mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Address Information</h3>
            </div>

            <div class="space-y-6">
                <div class="w-full">
                    <label for="address" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Permanent Address</label>
                    <textarea id="address" name="address" rows="3"
                        class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200 resize-none" 
                        >{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="w-full">
                    <label for="collegeAddress" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">College Address</label>
                    <textarea id="collegeAddress" name="collegeAddress" rows="2"
                        class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200 resize-none" 
                        >{{ old('collegeAddress', $user->collegeAddress) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- State Dropdown --}}
                    <div>
                        <label for="state" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">State</label>
                        <div class="relative">
                            <select id="stateSelect" name="state" 
                                class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 appearance-none cursor-pointer pr-10"
                                onchange="updateCities()">
                                <option value="" disabled {{ old('state', $user->state) ? '' : 'selected' }}>Select State</option>
                                @foreach(['JOHOR', 'KEDAH', 'KELANTAN', 'MELAKA', 'NEGERI SEMBILAN', 'PAHANG', 'PENANG (PULAU PINANG)', 'PERAK', 'PERLIS', 'SABAH', 'SARAWAK', 'SELANGOR', 'TERENGGANU', 'W.P. KUALA LUMPUR', 'W.P. LABUAN', 'W.P. PUTRAJAYA'] as $stateOption)
                                    <option value="{{ $stateOption }}" {{ old('state', $user->state) === $stateOption ? 'selected' : '' }}>{{ $stateOption }}</option>
                                @endforeach
                            </select>
                            {{-- Custom Arrow SVG --}}
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Dependent City Dropdown --}}
                    <div>
                        <label for="city" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">City</label>
                        <div class="relative">
                            <select id="citySelect" name="city" 
                                class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 appearance-none cursor-pointer pr-10">
                                <option value="">Select City</option>
                            </select>
                            {{-- Custom Arrow SVG --}}
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Postcode --}}
                    <div>
                        <label for="postcode" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Postcode</label>
                        <x-text-input id="postcode" name="postcode" type="text" class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('postcode', $user->postcode)" placeholder="e.g. 81310" />
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-100 mb-10">

        {{-- Emergency & Financial Details --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                 <div class="flex items-center mb-6">
                    <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#bb1419] mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Emergency Contact</h3>
                </div>
                <div class="space-y-4">
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Contact Name</label>
                        <x-text-input id="eme_name" name="eme_name" type="text" class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('eme_name', $user->eme_name)" />
                     </div>
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Phone Number</label>
                        <x-text-input id="emephoneNum" name="emephoneNum" type="text" class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('emephoneNum', $user->emephoneNum)" />
                     </div>
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Relationship</label>
                        <input list="relationships" id="emerelation" name="emerelation" class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20" value="{{ old('emerelation', $user->emerelation) }}">
                        <datalist id="relationships">
                            @foreach(['FATHER', 'MOTHER', 'SIBLING', 'BROTHER', 'SISTER', 'GRANDPARENT', 'UNCLE', 'AUNT', 'GUARDIAN', 'SPOUSE', 'COUSIN'] as $rel)
                                <option value="{{ $rel }}">
                            @endforeach
                        </datalist>
                     </div>
                </div>
            </div>

            <div>
                 <div class="flex items-center mb-6">
                    <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#bb1419] mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Financial Details</h3>
                </div>
                <div class="space-y-4">
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Bank Name</label>
                        <div class="relative">
                            <select id="bankName" name="bankName" class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 appearance-none cursor-pointer pr-10">
                                <option value="" disabled {{ old('bankName', $user->bankName) ? '' : 'selected' }}>Select Bank</option>
                                @foreach(['MAYBANK', 'CIMB BANK', 'PUBLIC BANK', 'RHB BANK', 'HONG LEONG BANK', 'AMBANK', 'UOB MALAYSIA', 'BANK RAKYAT', 'OCBC BANK', 'HSBC BANK', 'BANK ISLAM', 'AFFIN BANK', 'ALLIANCE BANK', 'AGROBANK'] as $bank)
                                    <option value="{{ $bank }}" {{ old('bankName', $user->bankName) === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                @endforeach
                            </select>
                            {{-- Custom Arrow SVG --}}
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                     </div>
                     <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Account Number</label>
                        <x-text-input id="accountNum" name="accountNum" type="text" class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('accountNum', $user->accountNum)" />
                     </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6 pt-10 mt-6 border-t border-gray-100">
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Saved Successfully') }}
                </p>
            @endif
            <button type="submit" class="bg-[#bb1419] hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transform transition hover:-translate-y-0.5 duration-200">
                {{ __('SAVE CHANGES') }}
            </button>
        </div>
    </form>

    <style>
        /* Force remove default browser arrows in all browsers */
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }
        /* IE specific */
        select::-ms-expand {
            display: none !important;
        }
    </style>

    <script>
        const stateData = {
            "JOHOR": ["Johor Bahru", "Iskandar Puteri", "Pasir Gudang", "Kulai", "Batu Pahat", "Kluang", "Muar", "Segamat", "Pontian", "Kota Tinggi", "Tangkak", "Yong Peng", "Pekan Nenas", "Parit Raja", "Ulu Tiram"],
            "KEDAH": ["Alor Setar", "Sungai Petani", "Kulim", "Kuah (Langkawi)", "Jitra", "Pendang", "Yan", "Baling", "Sik", "Kuala Nerang", "Pokok Sena", "Bedong", "Gurun"],
            "KELANTAN": ["Kota Bharu", "Pasir Mas", "Tumpat", "Bachok", "Machang", "Tanah Merah", "Kuala Krai", "Gua Musang", "Pasir Puteh", "Jeli", "Dabong"],
            "MELAKA": ["Melaka City (Bandaraya Melaka)", "Alor Gajah", "Jasin", "Masjid Tanah", "Merlimau"],
            "NEGERI SEMBILAN": ["Seremban", "Port Dickson", "Nilai", "Bahau", "Tampin", "Kuala Pilah", "Rembau", "Gemas"],
            "PAHANG": ["Kuantan", "Temerloh", "Bentong", "Pekan", "Raub", "Jerantut", "Kuala Lipis", "Mentakab", "Maran", "Muadzam Shah", "Tanah Rata", "Brinchang"],
            "PENANG (PULAU PINANG)": ["George Town", "Balik Pulau", "Butterworth", "Bukit Mertajam", "Perai", "Kepala Batas", "Nibong Tebal", "Jawi"],
            "PERAK": ["Ipoh", "Taiping", "Teluk Intan", "Batu Gajah", "Kampar", "Kuala Kangsar", "Seri Manjung", "Sitiawan", "Lumut", "Parit Buntar", "Tapah", "Gerik", "Bagan Serai", "Tanjung Malim"],
            "PERLIS": ["Kangar", "Arau", "Kuala Perlis", "Padang Besar"],
            "SABAH": ["Kota Kinabalu", "Sandakan", "Tawau", "Lahad Datu", "Keningau", "Semporna", "Kudat", "Ranau", "Beaufort", "Papar", "Kota Belud", "Tuaran", "Putatan", "Penampang"],
            "SARAWAK": ["Kuching", "Samarahan", "Serian", "Bau", "Lundu", "Miri", "Bintulu", "Sibu", "Mukah", "Kapit", "Sri Aman", "Betong", "Sarikei", "Limbang", "Lawas", "Marudi"],
            "SELANGOR": ["Shah Alam", "Petaling Jaya", "Subang Jaya", "Klang", "Kajang", "Ampang Jaya", "Selayang", "Gombak", "Puchong", "Cyberjaya", "Putrayaja", "Sepang", "Dengkil", "Rawang", "Kuala Selangor", "Sabak Bernam", "Banting", "Jenjarom", "Hulu Selangor"],
            "TERENGGANU": ["Kuala Terengganu", "Dungun", "Kemaman", "Chukai", "Marang", "Besut", "Setiu", "Kuala Berang"],
            "W.P. KUALA LUMPUR": ["Kuala Lumpur", "Cheras", "Setiawangsa", "Kepong", "Bangsar"],
            "W.P. LABUAN": ["Labuan"],
            "W.P. PUTRAJAYA": ["Putrayaja"]
        };

        const currentCity = "{{ old('city', $user->city) }}";

        function updateCities() {
            const stateSelect = document.getElementById('stateSelect');
            const citySelect = document.getElementById('citySelect');
            const selectedState = stateSelect.value;

            citySelect.innerHTML = '<option value="">Select City</option>';

            if (selectedState && stateData[selectedState]) {
                stateData[selectedState].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.text = city;
                    if (city === currentCity) option.selected = true;
                    citySelect.appendChild(option);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateCities();
        });
    </script>
</section>
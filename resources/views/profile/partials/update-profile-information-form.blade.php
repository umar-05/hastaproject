<section 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 100)"
    x-show="show"
    x-transition:enter="transition ease-out duration-700 transform"
    x-transition:enter-start="opacity-0 translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="bg-[#f3f4f6] p-8 rounded-[24px] shadow-[12px_12px_24px_#d1d5db,-12px_-12px_24px_#ffffff]"
>
    <header class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
            {{ __('Profile Settings') }}
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            {{ __("Manage your account details and preferences.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div>
            <div class="flex items-center mb-6">
                <div class="p-3 bg-[#f3f4f6] rounded-full shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] mr-4 text-[#bb1419]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700">Personal Details</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <x-input-label for="matricNum" :value="__('Matric Number')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <input id="matricNum" name="matricNum" type="text" 
                        class="block w-full bg-[#f3f4f6] border-none text-gray-400 font-mono text-sm rounded-xl py-4 px-5 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:outline-none cursor-not-allowed" 
                        value="{{ old('matricNum', $user->matricNum) }}" readonly />
                </div>

                <div>
                    <x-input-label for="name" :value="__('Full Name')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <x-text-input id="name" name="name" type="text" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                        :value="old('name', $user->name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <x-text-input id="email" name="email" type="email" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                        :value="old('email', $user->email)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div>
                    <x-input-label for="phoneNum" :value="__('Phone Number')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <x-text-input id="phoneNum" name="phoneNum" type="text" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                        :value="old('phoneNum', $user->phoneNum)" required />
                </div>

                <div>
                    <x-input-label for="faculty" :value="__('Faculty')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <x-text-input id="faculty" name="faculty" type="text" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                        :value="old('faculty', $user->faculty)" required />
                </div>

                <div>
                    <x-input-label for="icNum_passport" :value="__('IC Number / Passport')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <x-text-input id="icNum_passport" name="icNum_passport" type="text" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                        :value="old('icNum_passport', $user->icNum_passport)" required />
                </div>
            </div>
        </div>

        <div>
            <div class="flex items-center mb-6 mt-8">
                <div class="p-3 bg-[#f3f4f6] rounded-full shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] mr-4 text-[#bb1419]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700">Address Information</h3>
            </div>

            <div class="space-y-6">
                <div class="w-full">
                    <x-input-label for="address" :value="__('Permanent Address')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <textarea id="address" name="address" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300 h-28 resize-none" 
                        >{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="w-full">
                    <x-input-label for="collegeAddress" :value="__('College Address')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                    <textarea id="collegeAddress" name="collegeAddress" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300 h-20 resize-none" 
                        >{{ old('collegeAddress', $user->collegeAddress) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="city" :value="__('City')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                        <x-text-input id="city" name="city" type="text" class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('city', $user->city)" />
                    </div>
                    <div>
                        <x-input-label for="postcode" :value="__('Postcode')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                        <x-text-input id="postcode" name="postcode" type="text" class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('postcode', $user->postcode)" />
                    </div>
                    
                    <div>
                        <x-input-label for="state" :value="__('State')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                        <select id="state" name="state" 
                            class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 appearance-none">
                            <option value="" disabled {{ old('state', $user->state) ? '' : 'selected' }}>Select State</option>
                            @foreach(['JOHOR', 'KEDAH', 'KELANTAN', 'MELAKA', 'NEGERI SEMBILAN', 'PAHANG', 'PERAK', 'PERLIS', 'PULAU PINANG', 'SABAH', 'SARAWAK', 'SELANGOR', 'TERENGGANU', 'W.P. KUALA LUMPUR', 'W.P. LABUAN', 'W.P. PUTRAJAYA'] as $state)
                                <option value="{{ $state }}" {{ old('state', $user->state) === $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <div>
                 <div class="flex items-center mb-6">
                    <div class="p-3 bg-[#f3f4f6] rounded-full shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] mr-4 text-[#bb1419]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Emergency Contact</h3>
                </div>
                <div class="space-y-4">
                     <x-text-input id="eme_name" name="eme_name" placeholder="Contact Name" type="text" class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('eme_name', $user->eme_name)" />
                     
                     <x-text-input id="emephoneNum" name="emephoneNum" placeholder="Phone Number" type="text" class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('emephoneNum', $user->emephoneNum)" />
                     
                     <div class="relative">
                        <input list="relationships" id="emerelation" name="emerelation" placeholder="Relationship (Select or Type)" 
                            class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20" 
                            value="{{ old('emerelation', $user->emerelation) }}" />
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
                    <div class="p-3 bg-[#f3f4f6] rounded-full shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] mr-4 text-[#bb1419]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Financial Details</h3>
                </div>
                <div class="space-y-4">
                     <select id="bankName" name="bankName" 
                        class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 appearance-none">
                        <option value="" disabled {{ old('bankName', $user->bankName) ? '' : 'selected' }}>Select Bank</option>
                        @foreach(['MAYBANK', 'CIMB BANK', 'PUBLIC BANK', 'RHB BANK', 'HONG LEONG BANK', 'AMBANK', 'UOB MALAYSIA', 'BANK RAKYAT', 'OCBC BANK', 'HSBC BANK', 'BANK ISLAM', 'AFFIN BANK', 'ALLIANCE BANK', 'AGROBANK'] as $bank)
                            <option value="{{ $bank }}" {{ old('bankName', $user->bankName) === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                        @endforeach
                    </select>

                     <x-text-input id="accountNum" name="accountNum" placeholder="Account Number" type="text" class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20" :value="old('accountNum', $user->accountNum)" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-6 mt-8">
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-bold tracking-wide">{{ __('Saved Successfully') }}</p>
            @endif

            <button type="submit" class="bg-[#bb1419] text-white font-bold py-4 px-12 rounded-xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] hover:shadow-[inset_4px_4px_8px_#8a0f12,inset_-4px_-4px_8px_#ec191f] active:scale-95 transition-all duration-300 transform">
                {{ __('SAVE CHANGES') }}
            </button>
        </div>
    </form>
</section>
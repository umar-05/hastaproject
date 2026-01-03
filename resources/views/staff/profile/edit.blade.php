<x-staff-layout>
    
    {{-- Main Container --}}
    <div class="max-w-5xl mx-auto pb-12 pt-8">

        {{-- Simple Page Header (Since Banner is removed) --}}
        <div class="mb-8 px-4 md:px-0">
            <h2 class="font-bold text-4xl text-gray-800 tracking-tight">Profile Settings</h2>
            <p class="text-gray-500 text-sm mt-1">Manage your personal information and security settings.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 md:px-0">

            {{-- LEFT COLUMN: Main Form --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- EDIT PROFILE FORM --}}
                <form id="profile-form" method="post" action="{{ route('staff.profile.update') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @csrf
                    @method('patch')

                    {{-- 1. Personal Details --}}
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center text-hasta-red">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Personal Details</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Full Name</label>
                                <input name="name" type="text" value="{{ old('name', $user->name) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors" placeholder="Your full name">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Phone Number</label>
                                <input name="phoneNum" type="text" inputmode="numeric" value="{{ old('phoneNum', $user->phoneNum) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors" placeholder="0123456789">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">IC / Passport</label>
                                <input name="icNum_passport" type="text" value="{{ old('icNum_passport', $user->icNum_passport) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors" placeholder="Identity Number">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Email</label>
                                <input name="email" type="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Position</label>
                                <input name="position" type="text" value="{{ old('position', $user->position) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors">
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 mx-8"></div>

                    {{-- 2. Address Info --}}
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-8 w-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Address</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Street Address</label>
                                <textarea name="address" rows="2" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors resize-none">{{ old('address', $user->address) }}</textarea>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">City</label>
                                <input name="city" type="text" value="{{ old('city', $user->city) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Postcode</label>
                                    <input name="postcode" type="text" inputmode="numeric" value="{{ old('postcode', $user->postcode) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">State</label>
                                    <select name="state" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors cursor-pointer text-gray-700">
                                        <option value="" disabled selected>Select State</option>
                                        @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan (KL)', 'Wilayah Persekutuan (Putrajaya)'] as $state)
                                            <option value="{{ $state }}" {{ old('state', $user->state) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 mx-8"></div>

                    {{-- 3. Emergency & Banking (Grid) --}}
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Emergency --}}
                        <div>
                             <div class="flex items-center gap-3 mb-6">
                                <div class="h-8 w-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Emergency</h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Contact Name</label>
                                    <input name="eme_name" type="text" value="{{ old('eme_name', $user->eme_name) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Relation</label>
                                        {{-- Data List --}}
                                        <input list="relations" name="emerelation" type="text" value="{{ old('emerelation', $user->emerelation) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors" placeholder="Select or type">
                                        <datalist id="relations">
                                            <option value="Parent">
                                            <option value="Spouse">
                                            <option value="Sibling">
                                            <option value="Relative">
                                            <option value="Friend">
                                            <option value="Colleague">
                                            <option value="Other">
                                        </datalist>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Phone</label>
                                        <input name="emephoneNum" type="text" inputmode="numeric" value="{{ old('emephoneNum', $user->emephoneNum) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Banking --}}
                        <div>
                             <div class="flex items-center gap-3 mb-6">
                                <div class="h-8 w-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Banking</h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Bank Name</label>
                                    <select name="bankName" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors cursor-pointer text-gray-700">
                                        <option value="" disabled selected>Select Bank</option>
                                        @foreach(['Maybank', 'CIMB Bank', 'Public Bank', 'RHB Bank', 'Hong Leong Bank', 'AmBank', 'UOB Bank', 'Bank Rakyat', 'OCBC Bank', 'HSBC Bank', 'Bank Islam'] as $bank)
                                            <option value="{{ $bank }}" {{ old('bankName', $user->bankName) == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Account No.</label>
                                    <input name="accountNum" type="text" inputmode="numeric" value="{{ old('accountNum', $user->accountNum) }}" class="mt-1 w-full bg-gray-50 border-transparent focus:border-red-500 focus:bg-white focus:ring-0 rounded-xl px-4 py-3 text-sm font-medium transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Footer --}}
                    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                         <div class="text-xs text-gray-400 italic">
                             Last updated: {{ now()->format('d M Y') }}
                         </div>
                         <div class="flex items-center gap-4">
                            @if (session('status') === 'profile-updated')
                                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Saved!
                                </span>
                            @endif
                            <button type="submit" class="bg-[#bb1419] hover:bg-red-800 text-white font-bold py-3 px-8 rounded-xl shadow-md transition transform active:scale-95 text-sm">
                                Save Profile
                            </button>
                         </div>
                    </div>
                </form>
            </div>

            {{-- RIGHT COLUMN: Security & Read-only --}}
            <div class="lg:col-span-1 space-y-8">
                
                {{-- PASSWORD CARD --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-800">Security</h3>
                        <p class="text-xs text-gray-500 mt-1">Update your password</p>
                    </div>
                    
                    <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-4">
                        @csrf
                        @method('put')

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Current Password</label>
                            <input name="current_password" type="password" class="mt-1 w-full bg-gray-50 border-transparent focus:border-gray-400 focus:bg-white focus:ring-0 rounded-xl px-4 py-2.5 text-sm transition-colors">
                            @error('current_password', 'updatePassword') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">New Password</label>
                            <input name="password" type="password" class="mt-1 w-full bg-gray-50 border-transparent focus:border-gray-400 focus:bg-white focus:ring-0 rounded-xl px-4 py-2.5 text-sm transition-colors">
                            @error('password', 'updatePassword') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">Confirm Password</label>
                            <input name="password_confirmation" type="password" class="mt-1 w-full bg-gray-50 border-transparent focus:border-gray-400 focus:bg-white focus:ring-0 rounded-xl px-4 py-2.5 text-sm transition-colors">
                        </div>

                        <div class="pt-2">
                            {{-- CHANGED: Button Color to match Save Profile --}}
                            <button type="submit" class="w-full bg-[#bb1419] hover:bg-red-800 text-white font-bold py-3 rounded-xl shadow-md transition transform active:scale-95 text-sm">
                                Update Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs text-green-600 font-bold mt-2 text-center">
                                    Password Changed!
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-staff-layout>
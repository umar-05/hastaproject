<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-hasta-red leading-tight">
            {{ __('Manage Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="p-8 bg-white shadow-xl rounded-[30px] border border-gray-100">
                    <div class="flex items-center mb-6 border-b pb-4">
                        <div class="p-2 bg-red-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-hasta-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Personal Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 focus:border-hasta-red focus:ring-hasta-red" :value="old('name', $user->name)" required />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        </div>
                        <div>
                            <x-input-label for="phoneNum" :value="__('Phone Number')" />
                            <x-text-input id="phoneNum" name="phoneNum" type="text" class="mt-1 block w-full" :value="old('phoneNum', $user->phoneNum)" placeholder="01X-XXXXXXX" />
                        </div>
                        <div>
                            <x-input-label for="icNum" :value="__('IC Number')" />
                            <x-text-input id="icNum" name="icNum" type="text" class="mt-1 block w-full" :value="old('icNum', $user->icNum)" />
                        </div>
                        <div>
                            <x-input-label for="matricNum" :value="__('Matric Number')" />
                            <x-text-input id="matricNum" name="matricNum" type="text" class="mt-1 block w-full bg-gray-50" :value="old('matricNum', $user->matricNum)" readonly />
                        </div>
                        <div>
                            <x-input-label for="faculty" :value="__('Faculty')" />
                            <x-text-input id="faculty" name="faculty" type="text" class="mt-1 block w-full" :value="old('faculty', $user->faculty)" />
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-white shadow-xl rounded-[30px] border border-gray-100">
                    <div class="flex items-center mb-6 border-b pb-4">
                        <div class="p-2 bg-red-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-hasta-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Address Information</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="address" :value="__('Home Address')" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-text-input name="city" placeholder="City" :value="old('city', $user->city)" />
                            <x-text-input name="postcode" placeholder="Postcode" :value="old('postcode', $user->postcode)" />
                            <x-text-input name="state" placeholder="State" :value="old('state', $user->state)" />
                        </div>
                        <div>
                            <x-input-label for="collegeAddress" :value="__('College Address (UTM)')" />
                            <x-text-input id="collegeAddress" name="collegeAddress" type="text" class="mt-1 block w-full" :value="old('collegeAddress', $user->collegeAddress)" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-8 bg-white shadow-xl rounded-[30px] border border-gray-100">
                        <h3 class="text-lg font-bold text-hasta-red mb-4">Emergency Contact</h3>
                        <div class="space-y-4">
                            <x-text-input name="eme_name" placeholder="Contact Name" :value="old('eme_name', $user->eme_name)" class="w-full" />
                            <x-text-input name="emephoneNum" placeholder="Phone Number" :value="old('emephoneNum', $user->emephoneNum)" class="w-full" />
                            <x-text-input name="emerelation" placeholder="Relationship" :value="old('emerelation', $user->emerelation)" class="w-full" />
                        </div>
                    </div>

                    <div class="p-8 bg-white shadow-xl rounded-[30px] border border-gray-100">
                        <h3 class="text-lg font-bold text-hasta-red mb-4">Bank Account</h3>
                        <div class="space-y-4">
                            <select name="bankName" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Select Bank</option>
                                <option value="Maybank" {{ $user->bankName == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                <option value="CIMB" {{ $user->bankName == 'CIMB' ? 'selected' : '' }}>CIMB</option>
                                <option value="Bank Islam" {{ $user->bankName == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                            </select>
                            <x-text-input name="accountNum" placeholder="Account Number" :value="old('accountNum', $user->accountNum)" class="w-full" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-hasta-red hover:bg-red-700 text-white font-bold py-3 px-12 rounded-full shadow-lg transition duration-200">
                        {{ __('Save Profile Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
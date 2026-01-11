<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-hasta-red leading-tight">
            {{ __('Manage Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{ activeTab: '{{ session('status') === 'documents-uploaded' ? 'documents' : 'profile' }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex space-x-1 bg-gray-200/50 p-1 rounded-xl mb-8 w-fit mx-auto sm:mx-0">
                
                <button 
                    @click="activeTab = 'profile'"
                    :class="{ 'bg-white text-hasta-red shadow-sm': activeTab === 'profile', 'text-gray-500 hover:text-gray-700': activeTab !== 'profile' }"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile Settings
                </button>

                <button 
                    @click="activeTab = 'documents'"
                    :class="{ 'bg-white text-hasta-red shadow-sm': activeTab === 'documents', 'text-gray-500 hover:text-gray-700': activeTab !== 'documents' }"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    My Documents
                </button>
            </div>

            <div x-show="activeTab === 'profile'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-6"
            >
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-100">
                    <div class="w-full">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-100">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

            </div>

            <div x-show="activeTab === 'documents'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="space-y-6"
     style="display: none;"
>
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <header class="mb-10 border-b border-gray-100 pb-6">
            <div class="flex items-center mb-2">
                <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-hasta-red mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('My Documents') }}</h2>
            </div>
            <p class="mt-1 text-sm text-gray-500 pl-[3.25rem]">{{ __("Upload your required documents for vehicle rental verification. These documents are securely stored.") }}</p>
        </header>

        <form action="{{ route('profile.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div class="bg-gray-50 p-6 rounded-xl border {{ $user->doc_ic_passport ? 'border-green-200 bg-green-50/30' : 'border-dashed border-gray-300' }} hover:border-hasta-red transition group flex flex-col justify-between">
                    <div>
                        <div class="text-center mb-4">
                            <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:shadow-md transition">
                                <svg class="w-6 h-6 {{ $user->doc_ic_passport ? 'text-green-500' : 'text-gray-400' }} group-hover:text-hasta-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .667.333 1 1 1v1m0 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                            </div>
                            <h4 class="font-bold text-gray-800">IC / Passport</h4>
                            <p class="text-xs text-gray-500 mt-1">Front and back (PDF, JPG)</p>
                        </div>

                        @if($user->doc_ic_passport)
                            <div class="mb-4 bg-white p-3 rounded-lg border border-green-100 shadow-sm">
                                <p class="text-[10px] text-green-600 font-bold flex items-center justify-center mb-2 uppercase tracking-wide">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Uploaded
                                </p>
                                <div class="flex items-center justify-between gap-2 border-t border-gray-100 pt-2">
                                    <span class="text-[10px] text-gray-400 truncate max-w-[80px]" title="{{ basename($user->doc_ic_passport) }}">
                                        {{ basename($user->doc_ic_passport) }}
                                    </span>
                                    <a href="{{ asset('storage/' . $user->doc_ic_passport) }}" target="_blank" class="text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded transition flex items-center font-semibold">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <input type="file" name="ic_passport" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-hasta-red hover:file:bg-red-100 cursor-pointer"/>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border {{ $user->doc_license ? 'border-green-200 bg-green-50/30' : 'border-dashed border-gray-300' }} hover:border-hasta-red transition group flex flex-col justify-between">
                    <div>
                        <div class="text-center mb-4">
                            <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:shadow-md transition">
                                <svg class="w-6 h-6 {{ $user->doc_license ? 'text-green-500' : 'text-gray-400' }} group-hover:text-hasta-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h4 class="font-bold text-gray-800">Driver's License</h4>
                            <p class="text-xs text-gray-500 mt-1">Valid license copy</p>
                        </div>

                        @if($user->doc_license)
                            <div class="mb-4 bg-white p-3 rounded-lg border border-green-100 shadow-sm">
                                <p class="text-[10px] text-green-600 font-bold flex items-center justify-center mb-2 uppercase tracking-wide">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Uploaded
                                </p>
                                <div class="flex items-center justify-between gap-2 border-t border-gray-100 pt-2">
                                    <span class="text-[10px] text-gray-400 truncate max-w-[80px]" title="{{ basename($user->doc_license) }}">
                                        {{ basename($user->doc_license) }}
                                    </span>
                                    <a href="{{ asset('storage/' . $user->doc_license) }}" target="_blank" class="text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded transition flex items-center font-semibold">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <input type="file" name="license" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-hasta-red hover:file:bg-red-100 cursor-pointer"/>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border {{ $user->doc_matric ? 'border-green-200 bg-green-50/30' : 'border-dashed border-gray-300' }} hover:border-hasta-red transition group flex flex-col justify-between">
                    <div>
                        <div class="text-center mb-4">
                            <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:shadow-md transition">
                                <svg class="w-6 h-6 {{ $user->doc_matric ? 'text-green-500' : 'text-gray-400' }} group-hover:text-hasta-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <h4 class="font-bold text-gray-800">Matric Card</h4>
                            <p class="text-xs text-gray-500 mt-1">Student verification</p>
                        </div>

                        @if($user->doc_matric)
                            <div class="mb-4 bg-white p-3 rounded-lg border border-green-100 shadow-sm">
                                <p class="text-[10px] text-green-600 font-bold flex items-center justify-center mb-2 uppercase tracking-wide">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Uploaded
                                </p>
                                <div class="flex items-center justify-between gap-2 border-t border-gray-100 pt-2">
                                    <span class="text-[10px] text-gray-400 truncate max-w-[80px]" title="{{ basename($user->doc_matric) }}">
                                        {{ basename($user->doc_matric) }}
                                    </span>
                                    <a href="{{ asset('storage/' . $user->doc_matric) }}" target="_blank" class="text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded transition flex items-center font-semibold">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <input type="file" name="matric_card" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-hasta-red hover:file:bg-red-100 cursor-pointer"/>
                </div>

            </div>

            <div class="flex items-center justify-end gap-6 pt-10 mt-6 border-t border-gray-100">
                @if (session('status') === 'documents-uploaded')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ __('Documents Saved') }}
                    </p>
                @endif
                <button type="submit" class="bg-hasta-red hover:bg-red-800 text-white font-bold py-3 px-8 rounded-xl shadow-md hover:shadow-lg transform transition hover:-translate-y-0.5 duration-200">
                    {{ __('UPLOAD DOCUMENTS') }}
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
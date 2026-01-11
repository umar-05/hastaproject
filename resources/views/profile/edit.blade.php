<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-hasta-red leading-tight">
            {{ __('Manage Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        {{-- Main Container: Maximized width for better visibility --}}
        <div class="w-full max-w-[98%] mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                
                {{-- LEFT COLUMN: Profile Information (Takes up 2/3 of space) --}}
                <div class="xl:col-span-2 shadow-lg sm:rounded-xl bg-white border border-gray-100 p-6 sm:p-10">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- RIGHT COLUMN: Documents & Password (Takes up 1/3 of space) --}}
                <div class="space-y-8">
                    
                    {{-- SECTION 2: My Documents --}}
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                        <header class="mb-8 border-b border-gray-100 pb-6">
                            <div class="flex items-center mb-3">
                                <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-hasta-red mr-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ __('My Documents') }}</h2>
                            </div>
                            <p class="text-sm text-gray-500 pl-[3.25rem]">{{ __("Upload documents for rental verification.") }}</p>
                        </header>

                        <form action="{{ route('profile.documents.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 gap-6">
                                
                                {{-- IC / Passport Upload --}}
                                <div class="bg-gray-50 p-5 rounded-xl border {{ $user->doc_ic_passport ? 'border-green-200 bg-green-50/30' : 'border-dashed border-gray-300' }} hover:border-hasta-red transition group flex flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center shadow-sm group-hover:shadow-md transition shrink-0">
                                            <svg class="w-6 h-6 {{ $user->doc_ic_passport ? 'text-green-500' : 'text-gray-400' }} group-hover:text-hasta-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .667.333 1 1 1v1m0 0a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-base text-gray-800">IC / Passport</h4>
                                            @if($user->doc_ic_passport)
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-xs text-green-600 font-bold uppercase flex items-center"><svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Uploaded</span>
                                                    <a href="{{ asset('storage/' . $user->doc_ic_passport) }}" target="_blank" class="text-xs underline text-gray-500 hover:text-gray-800 font-semibold">View File</a>
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-500">Front and back (PDF, JPG)</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="w-32">
                                        <input type="file" name="ic_passport" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-hasta-red hover:file:bg-red-100 cursor-pointer"/>
                                    </div>
                                </div>

                                {{-- Driver's License Upload --}}
                                <div class="bg-gray-50 p-5 rounded-xl border {{ $user->doc_license ? 'border-green-200 bg-green-50/30' : 'border-dashed border-gray-300' }} hover:border-hasta-red transition group flex flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center shadow-sm group-hover:shadow-md transition shrink-0">
                                            <svg class="w-6 h-6 {{ $user->doc_license ? 'text-green-500' : 'text-gray-400' }} group-hover:text-hasta-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-base text-gray-800">License</h4>
                                            @if($user->doc_license)
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-xs text-green-600 font-bold uppercase flex items-center"><svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Uploaded</span>
                                                    <a href="{{ asset('storage/' . $user->doc_license) }}" target="_blank" class="text-xs underline text-gray-500 hover:text-gray-800 font-semibold">View File</a>
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-500">Valid license copy</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="w-32">
                                        <input type="file" name="license" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-hasta-red hover:file:bg-red-100 cursor-pointer"/>
                                    </div>
                                </div>

                                {{-- Matric Card Upload --}}
                                <div class="bg-gray-50 p-5 rounded-xl border {{ $user->doc_matric ? 'border-green-200 bg-green-50/30' : 'border-dashed border-gray-300' }} hover:border-hasta-red transition group flex flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center shadow-sm group-hover:shadow-md transition shrink-0">
                                            <svg class="w-6 h-6 {{ $user->doc_matric ? 'text-green-500' : 'text-gray-400' }} group-hover:text-hasta-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-base text-gray-800">Matric</h4>
                                            @if($user->doc_matric)
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-xs text-green-600 font-bold uppercase flex items-center"><svg class="w-4 h-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Uploaded</span>
                                                    <a href="{{ asset('storage/' . $user->doc_matric) }}" target="_blank" class="text-xs underline text-gray-500 hover:text-gray-800 font-semibold">View File</a>
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-500">Student verification</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="w-32">
                                        <input type="file" name="matric_card" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-hasta-red hover:file:bg-red-100 cursor-pointer"/>
                                    </div>
                                </div>

                            </div>

                            <div class="flex items-center justify-end pt-8 mt-8 border-t border-gray-100">
                                @if (session('status') === 'documents-uploaded')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center mr-4">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Documents Saved
                                    </p>
                                @endif
                                <button type="submit" class="bg-hasta-red hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg text-base shadow-md hover:shadow-lg transform transition hover:-translate-y-0.5 duration-200">
                                    {{ __('UPLOAD DOCUMENTS') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- SECTION 3: Update Password --}}
                    <div class="p-6 sm:p-8 bg-white shadow-lg rounded-2xl border border-gray-100">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Message --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    {{ __("Welcome back, ") }} <span class="font-bold text-hasta-red">{{ Auth::guard('staff')->user()->name }}</span>!
                    <br>
                    <span class="text-sm text-gray-500">Staff ID: {{ Auth::guard('staff')->user()->staffID }}</span>
                </div>
            </div>

            {{-- Quick Actions Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Card 1: Manage Profile --}}
                <a href="{{ route('staff.profile.edit') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-200 border-l-4 border-transparent hover:border-hasta-red h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-hasta-red group-hover:bg-red-200 transition">
                                    {{-- User Icon --}}
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">My Profile</h3>
                                    <p class="text-sm text-gray-500 mt-1">Update email, name & password</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Card 2: Pickup & Return --}}
                <a href="{{ route('staff.pickup-return') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-200 border-l-4 border-transparent hover:border-hasta-red h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition">
                                    {{-- Car Icon --}}
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Pickup & Return</h3>
                                    <p class="text-sm text-gray-500 mt-1">Manage vehicle handovers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Card 3: Add New Staff --}}
                <a href="{{ route('staff.add-staff') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition duration-200 border-l-4 border-transparent hover:border-hasta-red h-full">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 group-hover:bg-green-200 transition">
                                    {{-- Plus Icon --}}
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">Add Staff</h3>
                                    <p class="text-sm text-gray-500 mt-1">Register new team members</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
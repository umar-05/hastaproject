<x-staff-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        {{-- Sticky Header --}}
        <div class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    {{-- Left Side: Title & Back --}}
                    <div>
                        <a href="{{ route('staff.fleet.index') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-indigo-600 mb-2 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Fleet List
                        </a>
                        <div class="flex items-center gap-4">
                            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $fleet->modelName }}</h1>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-sm font-mono border border-gray-200 font-bold tracking-wider">
                                    {{ $fleet->plateNumber }}
                                </span>
                                @php
                                    $statusColors = match(strtolower($fleet->status)) {
                                        'available' => 'bg-green-100 text-green-800 border-green-200',
                                        'maintenance' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'booked' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'rented' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border {{ $statusColors }}">
                                    <span class="w-2 h-2 mr-2 rounded-full bg-current opacity-75"></span>
                                    {{ ucfirst($fleet->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Actions --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('staff.fleet.edit', $fleet->plateNumber) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Edit Vehicle
                        </a>
                    </div>
                </div>

                {{-- Hero Section --}}
                <div class="py-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    {{-- Image --}}
                    <div class="lg:col-span-4 h-56 bg-gray-50 rounded-2xl overflow-hidden flex items-center justify-center border border-gray-200 relative group">
                        <div class="absolute inset-0 bg-gradient-to-tr from-gray-100 to-transparent opacity-50"></div>
                        {{-- Handle photo logic inline or use generic default --}}
                        <img src="{{ $fleet->photo1 && str_contains($fleet->photo1, '/') ? asset('storage/' . $fleet->photo1) : asset('images/' . ($fleet->photo1 ?? 'default.png')) }}" 
                        class="w-full h-full object-contain p-4 transform group-hover:scale-110 transition-transform duration-700 relative z-0" 
                        alt="{{ $fleet->modelName }}">
                    </div>

                    {{-- Stats Grid --}}
                    <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                        {{-- Stat 1 --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Year</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $fleet->year }}</p>
                        </div>

                        {{-- Stat 2 --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Color</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $fleet->color ?? 'N/A' }}</p>
                        </div>

                        {{-- Stat 3 --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Road Tax</p>
                            <p class="text-sm font-bold {{ (strtolower($fleet->roadtaxStat) == 'active') ? 'text-green-600' : 'text-red-600' }} mt-2">
                                {{ ucfirst($fleet->roadtaxStat ?? 'Unknown') }}
                            </p>
                        </div>

                        {{-- Stat 4 --}}
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-shadow">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Insurance</p>
                            <p class="text-sm font-bold {{ (strtolower($fleet->insuranceStat) == 'active') ? 'text-green-600' : 'text-red-600' }} mt-2">
                                {{ ucfirst($fleet->insuranceStat ?? 'Unknown') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tabs Navigation --}}
                <div class="flex space-x-1 border-b border-gray-200 overflow-x-auto no-scrollbar">
                    @php
                        $tabs = [
                            'staff.fleet.tabs.overview' => ['name' => 'Overview', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                            'staff.fleet.tabs.bookings' => ['name' => 'Booking History', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'staff.fleet.tabs.maintenance' => ['name' => 'Maintenance', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                            'staff.fleet.tabs.owner' => ['name' => 'Owner Details', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ];
                    @endphp

                    @foreach($tabs as $route => $tab)
                        <a href="{{ route($route, $fleet->plateNumber) }}" 
                           class="group relative min-w-max px-6 py-4 text-sm font-bold transition-all
                           {{ Route::currentRouteName() == $route ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-800' }}">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 {{ Route::currentRouteName() == $route ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
                                {{ $tab['name'] }}
                            </div>
                            
                            {{-- Active underline --}}
                            @if(Route::currentRouteName() == $route)
                                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 rounded-t-full"></div>
                            @else
                                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-200 rounded-t-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in-up">
            @yield('tab-content')
        </div>
    </div>

    {{-- Animations Style --}}
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-staff-layout>
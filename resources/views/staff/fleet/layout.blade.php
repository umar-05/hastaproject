<x-staff-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <a href="{{ route('staff.fleet.index') }}" class="inline-flex items-center text-xs font-medium text-gray-500 hover:text-indigo-600 mb-1">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                            Back to Fleet
                        </a>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $fleet->modelName }}</h1>
                            <span class="px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-600 text-sm font-mono border border-gray-200">
                                {{ $fleet->plateNumber }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide
                                {{ $fleet->status === 'available' ? 'bg-green-100 text-green-700' : 
                                   ($fleet->status === 'maintenance' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $fleet->status === 'available' ? 'bg-green-500' : ($fleet->status === 'maintenance' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                {{ $fleet->status }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('staff.fleet.edit', $fleet->plateNumber) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                </div>

                <div class="py-6 grid grid-cols-1 lg:grid-cols-4 gap-8 items-center">
                    <div class="lg:col-span-1 h-40 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center border border-gray-200">
                         <img src="{{ asset('images/cars/' . ($fleet->image ?? 'default_car.png')) }}" class="h-full w-full object-contain p-2 hover:scale-105 transition-transform">
                    </div>
                    <div class="lg:col-span-3 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <span class="text-xs text-gray-400 uppercase font-semibold">Year</span>
                            <p class="text-lg font-bold text-gray-900">{{ $fleet->year }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <span class="text-xs text-gray-400 uppercase font-semibold">Color</span>
                            <p class="text-lg font-bold text-gray-900">{{ $fleet->color ?? 'Silver' }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-8 border-b border-gray-200 mt-2">
                    <a href="{{ route('staff.fleet.tabs.overview', $fleet->plateNumber) }}" 
                       class="pb-4 text-sm font-medium border-b-2 transition-colors {{ Route::currentRouteName() == 'staff.fleet.tabs.overview' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Overview & Calendar
                    </a>
                    <a href="{{ route('staff.fleet.tabs.bookings', $fleet->plateNumber) }}" 
                       class="pb-4 text-sm font-medium border-b-2 transition-colors {{ Route::currentRouteName() == 'staff.fleet.tabs.bookings' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Booking History
                    </a>
                    <a href="{{ route('staff.fleet.tabs.maintenance', $fleet->plateNumber) }}" 
                       class="pb-4 text-sm font-medium border-b-2 transition-colors {{ Route::currentRouteName() == 'staff.fleet.tabs.maintenance' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Maintenance Logs
                    </a>
                    <a href="{{ route('staff.fleet.tabs.owner', $fleet->plateNumber) }}" 
                       class="pb-4 text-sm font-medium border-b-2 transition-colors {{ Route::currentRouteName() == 'staff.fleet.tabs.owner' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Owner
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('tab-content')
        </div>
    </div>
</x-staff-layout>
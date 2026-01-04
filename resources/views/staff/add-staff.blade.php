<x-staff-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Staff Record</h2>
            <p class="text-gray-500">Manage staff information and performance</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                <div class="h-14 w-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalStaffCount ?? '24' }}</p>
                    <p class="text-gray-500 text-sm">Total Staff</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                <div class="h-14 w-14 rounded-xl bg-green-50 text-green-600 flex items-center justify-center mr-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1" /></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $driverCount ?? '12' }}</p>
                    <p class="text-gray-500 text-sm">Drivers</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                <div class="h-14 w-14 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mr-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $adminCount ?? '8' }}</p>
                    <p class="text-gray-500 text-sm">Admin Staff</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
                <div class="h-14 w-14 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mr-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ $managerCount ?? '4' }}</p>
                    <p class="text-gray-500 text-sm">Managers</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-t-2xl border border-gray-200 border-b-0 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3 flex-1 min-w-[300px]">
                <div class="relative flex-1 max-w-xs">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input type="text" placeholder="Search staff..." class="pl-10 w-full border-gray-200 rounded-lg focus:ring-[#bb1419] focus:border-[#bb1419] py-2">
                </div>
                <select class="border-gray-200 rounded-lg text-gray-600 py-2 focus:ring-[#bb1419] focus:border-[#bb1419]">
                    <option>All Positions</option>
                    <option>Drivers</option>
                    <option>Admin Staff</option>
                    <option>Manager</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <button class="flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                    Filter
                </button>
                <button class="flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                    Export
                </button>
                <a href="{{ route('staff.add-stafffunctioning') }}" class="flex items-center px-4 py-2 bg-[#bb1419] text-white rounded-lg hover:bg-red-800 font-bold shadow-sm transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Add Staff
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-b-2xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Staff ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($staffs ?? [] as $staff)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $staff->staffID }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $staff->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $staff->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                           </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">No staff found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-staff-layout>
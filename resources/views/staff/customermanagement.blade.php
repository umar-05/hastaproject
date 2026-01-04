<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Customers</h1>
                <p class="text-gray-500">Manage customer information and records</p>
            </div>

            {{-- Action Bar --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex flex-1 items-center gap-3 max-w-2xl">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl bg-white focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Search customers...">
                    </div>
                </div>

                <div>
                    {{-- FIXED ROUTE NAME: Matches 'customermanagement-crud' in your web.php --}}
                    <a href="{{ route('staff.customermanagement-crud.create') }}" class="inline-flex items-center px-6 py-2 bg-red-600 rounded-xl text-sm font-bold text-white hover:bg-red-700 shadow-md transition">
                        <i class="fas fa-user-plus mr-2"></i> Add Customer
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Matric Number</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">IC Number</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Email</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Phone</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-5 text-sm font-semibold text-gray-600">{{ $customer->matricNum }}</td>
                                <td class="px-6 py-5 text-sm font-bold text-gray-900">{{ $customer->name }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $customer->ic_number ?? 'N/A' }}</td>
                                <td class="px-6 py-5 text-sm text-gray-500">{{ $customer->email }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $customer->phone ?? '+60 --' }}</td>
                                <td class="px-6 py-5 text-right text-sm">
                                    <div class="flex justify-end gap-3">
                                        {{-- View --}}
                                        <a href="{{ route('staff.customermanagement-crud.show', $customer->matricNum) }}" class="text-gray-400 hover:text-blue-600">
                                            <i class="far fa-eye text-lg"></i>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('staff.customermanagement-crud.edit', $customer->matricNum) }}" class="text-gray-400 hover:text-green-600">
                                            <i class="far fa-edit text-lg"></i>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('staff.customermanagement-crud.destroy', $customer->matricNum) }}" method="POST" onsubmit="return confirm('Delete this customer?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600">
                                                <i class="far fa-trash-alt text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.staff>
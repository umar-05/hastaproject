<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-8">
                <h1 class="text-3xl font-medium text-gray-900 tracking-tight">Customer management</h1>
                <p class="text-base text-gray-500 mt-1">Manage customer information, records, and account statuses</p>
            </div>

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
                    <span class="text-base font-normal">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Action Bar --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                {{-- Search Box --}}
                <div class="relative w-full max-w-xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-base"></i>
                    </div>
                    <input type="text" id="customerSearch" 
                        class="block w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 text-base font-normal shadow-sm transition" 
                        placeholder="Search by name, matric, or address...">
                </div>

                {{-- Status Filter Tabs --}}
                <div class="flex bg-gray-100 p-1 rounded-xl shadow-inner">
                    <button onclick="filterStatus('all')" id="filter-all" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-medium transition-all bg-white text-gray-900 shadow-sm">
                        All
                    </button>
                    <button onclick="filterStatus('active')" id="filter-active" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-medium transition-all text-gray-500 hover:text-gray-700">
                        Active
                    </button>
                    <button onclick="filterStatus('blacklisted')" id="filter-blacklisted" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-medium transition-all text-gray-500 hover:text-gray-700">
                        Blacklisted
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100" id="customerTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Matric number</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Email & phone</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">College address</th>
                            <th class="px-6 py-4 text-center text-sm font-medium text-gray-500 tracking-tight">Account status</th>
                            <th class="px-6 py-4 text-center text-sm font-medium text-gray-500 tracking-tight">Details</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 tracking-tight">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($customers as $customer)
                            @php 
                                $status = str_contains(strtolower($customer->accStatus), 'blacklisted') ? 'blacklisted' : 'active';
                            @endphp
                            <tr class="customer-row hover:bg-gray-50/50 transition-colors" data-status="{{ $status }}">
                                <td class="px-6 py-5 text-base text-blue-600 font-normal">
                                    {{ preg_replace('/cs/i', 'CS', ucfirst(strtolower($customer->matricNum))) }}
                                </td>
                                <td class="px-6 py-5 text-base text-gray-800 font-normal capitalize">{{ strtolower($customer->name) }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-base text-gray-700 font-normal">{{ $customer->email }}</span>
                                        <span class="text-sm text-gray-400 font-normal">{{ $customer->phoneNum ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-400 font-normal">
                                    {{ $customer->collegeAddress ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($status === 'blacklisted')
                                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100">Blacklisted</span>
                                    @else
                                        <span class="status-badge px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-100">Active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <button onclick="openDetailsModal('{{ $customer->matricNum }}')" class="text-gray-400 hover:text-red-600 transition-all">
                                        <i class="far fa-eye text-xl"></i>
                                    </button>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-4">
                                        <a href="{{ route('staff.customermanagement-crud.edit', $customer->matricNum) }}" class="text-gray-400 hover:text-green-600 transition">
                                            <i class="far fa-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('staff.customermanagement-crud.destroy', $customer->matricNum) }}" method="POST" onsubmit="return confirm('Delete this customer?');" class="inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                <i class="far fa-trash-alt text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center text-gray-400 italic font-normal">No customer records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Details Modal & Doc Preview Modal remain exactly the same as previous --}}
    {{-- (Insert the Modals from the previous response here) --}}

    <script>
        let currentStatusFilter = 'all';

        function filterStatus(status) {
            currentStatusFilter = status;
            
            // Update Button Styles
            document.querySelectorAll('.status-filter-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                btn.classList.add('text-gray-500');
            });
            const activeBtn = document.getElementById(`filter-${status}`);
            activeBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
            activeBtn.classList.remove('text-gray-500');

            applyFilters();
        }

        function applyFilters() {
            const searchTerm = document.getElementById('customerSearch').value.toUpperCase();
            const rows = document.querySelectorAll('.customer-row');

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowText = row.textContent.toUpperCase();
                
                const matchesStatus = (currentStatusFilter === 'all' || rowStatus === currentStatusFilter);
                const matchesSearch = rowText.includes(searchTerm);

                if (matchesStatus && matchesSearch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Search Input Listener
        document.getElementById('customerSearch').addEventListener('keyup', applyFilters);

        // ... Keep existing openDetailsModal, closeDetailsModal, openDocPreview, closeDocPreview, and formatMatric functions ...
    </script>
</x-layouts.staff>
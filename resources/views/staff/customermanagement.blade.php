<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-8">
                {{-- Only heading is bold --}}
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Customer Management</h1>
                <p class="text-base text-gray-500 mt-1 font-normal">Manage customer information, records, and account statuses</p>
            </div>

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
                    <span class="text-base font-medium">{{ session('success') }}</span>
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
                    <button onclick="filterStatus('all')" id="filter-all" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-bold transition-all bg-white text-gray-900 shadow-sm">
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
                            {{-- Table Headers are bold --}}
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 tracking-tight">Matric number</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 tracking-tight">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 tracking-tight">Email & phone</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 tracking-tight">College address</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 tracking-tight">Account status</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 tracking-tight">Details</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 tracking-tight">Actions</th>
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
                                        <span class="status-badge px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">Blacklisted</span>
                                    @else
                                        <span class="status-badge px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600 border border-green-100">Active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <button onclick="openDetailsModal('{{ $customer->matricNum }}')" class="text-gray-400 hover:text-red-600 transition-all">
                                        <i class="far fa-eye text-2xl"></i>
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

    <div id="detailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDetailsModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Customer Details</h3>
                    <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="modalContent" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Matric Number</p>
                            <p id="det-matric" class="text-base text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">IC/Passport</p>
                            <p id="det-ic" class="text-base text-gray-900 font-medium"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase">Full Name</p>
                            <p id="det-name" class="text-base text-gray-900 font-medium capitalize"></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Email</p>
                            <p id="det-email" class="text-base text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Phone Number</p>
                            <p id="det-phone" class="text-base text-gray-900 font-medium"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase">College Address</p>
                            <p id="det-address" class="text-base text-gray-400 font-normal italic"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeDetailsModal()" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailsModal(matricNum) {
    // Show loading state or clear previous data
    const modal = document.getElementById('detailsModal');
    
    // Fetch data from the show API route
    fetch(`/staff/customermanagement-crud/${matricNum}`)
        .then(response => response.json())
        .then(data => {
            // Inject data into modal elements
            document.getElementById('det-matric').innerText = data.matricNum;
            document.getElementById('det-ic').innerText = data.icNum_passport || 'N/A';
            document.getElementById('det-name').innerText = data.name.toLowerCase();
            document.getElementById('det-email').innerText = data.email;
            document.getElementById('det-phone').innerText = data.phoneNum || 'N/A';
            document.getElementById('det-address').innerText = data.collegeAddress || 'N/A';
            
            // Show the modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        })
        .catch(error => {
            console.error('Error fetching customer details:', error);
            alert('Failed to load customer details.');
        });
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
</x-layouts.staff>
<x-layouts.staff>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-6">
                <h1 class="text-3xl font-normal text-gray-900 tracking-tight">Customer Management</h1>
                <p class="text-base text-gray-500 mt-1 font-normal">Manage customer information, records, and account statuses</p>
            </div>

            {{-- Action Bar --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                {{-- Search Box --}}
                <div class="relative w-full max-w-lg">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-base"></i>
                    </div>
                    <input type="text" id="customerSearch" 
                        class="block w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 text-base font-normal shadow-sm transition" 
                        placeholder="Search by name, matric, or address...">
                </div>

                {{-- Status Filter Tabs --}}
                <div class="flex bg-gray-200 p-1 rounded-xl shadow-inner border border-gray-200">
                    <button onclick="filterStatus('all')" id="filter-all" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-normal transition-all bg-white text-gray-900 shadow-sm">All</button>
                    <button onclick="filterStatus('active')" id="filter-active" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-normal transition-all text-gray-600 hover:text-gray-900">Active</button>
                    <button onclick="filterStatus('inactive')" id="filter-inactive" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-normal transition-all text-gray-600 hover:text-gray-900">Inactive</button>
                    <button onclick="filterStatus('blacklisted')" id="filter-blacklisted" class="status-filter-btn px-6 py-2 rounded-lg text-sm font-normal transition-all text-gray-600 hover:text-gray-900">Blacklisted</button>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200" id="customerTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Matric number</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email & phone</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">College address</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Account status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($customers as $customer)
                            @php $statusKey = strtolower($customer->accStatus); @endphp
                            <tr class="customer-row hover:bg-gray-50/50 transition-colors" data-status="{{ $statusKey }}">
                                <td class="px-6 py-4 text-sm text-blue-600 font-normal underline decoration-1 underline-offset-4">
                                    {{ $customer->matricNum }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-normal capitalize">
                                    {{ strtolower($customer->name) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-800 font-normal">{{ $customer->email }}</span>
                                        <span class="text-xs text-gray-500 font-normal mt-0.5">{{ $customer->phoneNum ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-normal">
                                    {{ $customer->collegeAddress ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-normal border
                                        {{ $statusKey === 'active' ? 'bg-green-50 text-green-700 border-green-100' : 
                                           ($statusKey === 'inactive' ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-red-50 text-red-700 border-red-100') }}">
                                        {{ ucfirst($statusKey) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="openModal({{ json_encode($customer) }})" class="text-gray-400 hover:text-red-600 transition-all">
                                        <i class="far fa-eye text-xl"></i>
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('staff.customermanagement-crud.edit', $customer->matricNum) }}" class="text-gray-400 hover:text-green-600 transition">
                                        <i class="far fa-edit text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-20 text-center text-gray-400 italic text-sm font-normal">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Details Modal --}}
    <div id="detailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeModal()"></div>
            
            <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:max-w-4xl w-full">
                {{-- Header --}}
                <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Customer Details</h3>
                        <p class="text-sm text-gray-500 font-normal mt-0.5" id="modalSubHeader"></p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="px-8 py-8 max-h-[75vh] overflow-y-auto space-y-10 bg-white">
                    {{-- Address Info --}}
                    <section>
                        <h4 class="text-sm font-medium text-gray-900 border-b border-gray-100 pb-2 mb-5">Address Information</h4>
                        <div class="grid grid-cols-2 gap-x-10 gap-y-6">
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-400 font-normal mb-1">Full Address</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalAddress"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">City</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalCity"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">Postcode</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalPostcode"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">State</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalState"></span>
                            </div>
                        </div>
                    </section>

                    {{-- Emergency Contact --}}
                    <section>
                        <h4 class="text-sm font-medium text-gray-900 border-b border-gray-100 pb-2 mb-5">Emergency Contact</h4>
                        <div class="grid grid-cols-2 gap-x-10 gap-y-6">
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">Contact Name</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalEmName"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">Contact Phone</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalEmPhone"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">Relationship</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalEmRelation"></span>
                            </div>
                        </div>
                    </section>

                    {{-- ADDED: Bank Information Section --}}
                    <section>
                        <h4 class="text-sm font-medium text-gray-900 border-b border-gray-100 pb-2 mb-5">Bank Information</h4>
                        <div class="grid grid-cols-2 gap-x-10 gap-y-6">
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">Bank Name</label>
                                <span class="text-sm text-gray-800 font-normal" id="modalBank"></span>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-normal mb-1">Account Number</label>
                                <span class="text-sm text-gray-800 font-normal tracking-wider" id="modalAccNo"></span>
                            </div>
                        </div>
                    </section>

                    {{-- Documents --}}
                    <section>
                        <h4 class="text-sm font-medium text-gray-900 border-b border-gray-100 pb-2 mb-5">Customer Documents</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <a id="docPassport" target="_blank" class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all flex items-center gap-4">
                                <div class="bg-blue-50 p-2 rounded-lg"><i class="far fa-id-card text-blue-500 text-lg"></i></div>
                                <div>
                                    <p class="text-xs text-gray-800 font-normal">IC / Passport</p>
                                    <p class="text-[10px] text-gray-400 font-normal truncate max-w-[120px]" id="docPassportName"></p>
                                </div>
                            </a>
                            <a id="docMatric" target="_blank" class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all flex items-center gap-4">
                                <div class="bg-green-50 p-2 rounded-lg"><i class="far fa-address-card text-green-500 text-lg"></i></div>
                                <div>
                                    <p class="text-xs text-gray-800 font-normal">Matric Card</p>
                                    <p class="text-[10px] text-gray-400 font-normal truncate max-w-[120px]" id="docMatricName"></p>
                                </div>
                            </a>
                            <a id="docLicense" target="_blank" class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-all flex items-center gap-4">
                                <div class="bg-orange-50 p-2 rounded-lg"><i class="fas fa-id-badge text-orange-500 text-lg"></i></div>
                                <div>
                                    <p class="text-xs text-gray-800 font-normal">Driver's License</p>
                                    <p class="text-[10px] text-gray-400 font-normal truncate max-w-[120px]" id="docLicenseName"></p>
                                </div>
                            </a>
                        </div>
                    </section>
                </div>

                <div class="px-8 py-4 bg-gray-50 flex justify-end">
                    <button onclick="closeModal()" class="px-8 py-2 bg-white border border-gray-300 rounded-lg text-sm font-normal text-gray-700 hover:bg-gray-100 transition-all shadow-sm">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(customer) {
            // Basic Info
            document.getElementById('modalSubHeader').innerText = `${customer.matricNum} - ${customer.name}`;
            document.getElementById('modalAddress').innerText = customer.address || 'N/A';
            document.getElementById('modalCity').innerText = customer.city || 'N/A';
            document.getElementById('modalPostcode').innerText = customer.postcode || 'N/A';
            document.getElementById('modalState').innerText = customer.state || 'N/A';
            
            // Emergency Contact
            document.getElementById('modalEmName').innerText = customer.eme_name || 'N/A';
            document.getElementById('modalEmPhone').innerText = customer.emephoneNum || 'N/A';
            document.getElementById('modalEmRelation').innerText = customer.emerelation || 'N/A';
            
            // ADDED: Bank Data Logic
            document.getElementById('modalBank').innerText = customer.bankName || 'N/A';
            document.getElementById('modalAccNo').innerText = customer.accountNum || 'N/A';

            // Document Mapping
            const docs = [
                { id: 'docPassport', nameId: 'docPassportName', file: customer.doc_ic_passport },
                { id: 'docMatric', nameId: 'docMatricName', file: customer.doc_matric },
                { id: 'docLicense', nameId: 'docLicenseName', file: customer.doc_license }
            ];

            docs.forEach(doc => {
                const link = document.getElementById(doc.id);
                const nameLabel = document.getElementById(doc.nameId);
                if (doc.file) {
                    link.href = '/storage/' + doc.file;
                    nameLabel.innerText = doc.file.split('/').pop();
                    link.style.opacity = '1';
                    link.style.pointerEvents = 'auto';
                } else {
                    link.href = '#';
                    nameLabel.innerText = 'Not uploaded';
                    link.style.opacity = '0.5';
                    link.style.pointerEvents = 'none';
                }
            });

            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        function filterStatus(status) {
            const rows = document.querySelectorAll('.customer-row');
            const buttons = document.querySelectorAll('.status-filter-btn');

            buttons.forEach(btn => {
                btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
                btn.classList.add('text-gray-600');
            });

            const activeBtn = document.getElementById(`filter-${status}`);
            activeBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
            activeBtn.classList.remove('text-gray-600');

            rows.forEach(row => {
                row.style.display = (status === 'all' || row.getAttribute('data-status') === status) ? 'table-row' : 'none';
            });
        }

        document.getElementById('customerSearch').addEventListener('keyup', function() {
            let term = this.value.toLowerCase();
            document.querySelectorAll('.customer-row').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(term) ? 'table-row' : 'none';
            });
        });
    </script>
</x-layouts.staff>
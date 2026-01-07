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
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div class="relative w-full max-w-2xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-base"></i>
                    </div>
                    <input type="text" id="customerSearch" 
                        class="block w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 text-base font-normal shadow-sm transition" 
                        placeholder="Search by name, matric, or status...">
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Matric number</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Ic / Passport</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Email & phone</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 tracking-tight">Academic & college</th>
                            <th class="px-6 py-4 text-center text-sm font-medium text-gray-500 tracking-tight">Account status</th>
                            <th class="px-6 py-4 text-center text-sm font-medium text-gray-500 tracking-tight">Details</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-500 tracking-tight">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-5 text-base text-blue-600 font-normal capitalize">{{ strtolower($customer->matricNum) }}</td>
                                <td class="px-6 py-5 text-base text-gray-800 font-normal capitalize">{{ strtolower($customer->name) }}</td>
                                <td class="px-6 py-5 text-base text-gray-600 font-normal">{{ $customer->icNum_passport ?? 'N/A' }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-base text-gray-700 font-normal">{{ $customer->email }}</span>
                                        <span class="text-sm text-gray-400 font-normal">{{ $customer->phoneNum ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-600 font-normal capitalize">{{ strtolower($customer->faculty ?? 'N/A') }}</span>
                                        <span class="text-sm text-gray-400 font-normal truncate max-w-[180px]">{{ $customer->collegeAddress ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if(str_contains(strtolower($customer->accStatus), 'blacklisted'))
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100">Blacklisted</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-100">Active</span>
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
                                <td colspan="8" class="px-6 py-20 text-center text-gray-400 italic font-normal">No customer records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CUSTOMER DETAILS MODAL --}}
    <div id="detailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-3xl max-w-4xl w-full mx-4 overflow-hidden shadow-xl transform transition-all">
            <div class="px-8 py-6 border-b flex justify-between items-center bg-gray-50">
                <div>
                    <h3 class="text-xl font-medium text-gray-900 capitalize" id="modalName">Customer details</h3>
                    <p class="text-sm text-red-500 font-normal uppercase" id="modalMatric"></p>
                </div>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            
            <div class="p-8 max-h-[75vh] overflow-y-auto custom-scrollbar space-y-10">
                
                {{-- Academic Info --}}
                <section>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-6 flex items-center">
                        <i class="fas fa-university mr-2"></i> Academic & residence information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 font-normal">
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase mb-1">Faculty</p>
                            <p id="det-faculty" class="text-base text-gray-800 capitalize"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase mb-1">College address</p>
                            <p id="det-college" class="text-base text-gray-800"></p>
                        </div>
                    </div>
                </section>

                {{-- Permanent Address Section --}}
                <section>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-6 flex items-center">
                        <i class="fas fa-home mr-2"></i> Permanent address information
                    </h4>
                    <div class="space-y-6 font-normal">
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase mb-1">Full address</p>
                            <p id="det-address" class="text-base text-gray-800"></p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase mb-1">City</p>
                                <p id="det-city" class="text-base text-gray-800 capitalize"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase mb-1">Postcode</p>
                                <p id="det-postcode" class="text-base text-gray-800"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase mb-1">State</p>
                                <p id="det-state" class="text-base text-gray-800 capitalize"></p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Bank & Emergency --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <section>
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-6">Bank information</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Bank name</p>
                                <p id="det-bank" class="text-base text-gray-800 uppercase font-normal"></p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Account number</p>
                                <p id="det-acc" class="text-base text-gray-800 font-normal"></p>
                            </div>
                        </div>
                    </section>
                    <section>
                        <h4 class="text-xs font-semibold text-red-400 uppercase tracking-widest mb-6">Emergency contact</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] text-red-400 uppercase">Name & Relation</p>
                                <p id="det-emergency" class="text-base text-gray-800 font-normal capitalize"></p>
                            </div>
                            <div>
                                <p class="text-[10px] text-red-400 uppercase">Phone</p>
                                <p id="det-eme-phone" class="text-base text-gray-800 font-normal"></p>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Documents Section --}}
                <section class="border-t pt-8">
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-6">Uploaded documents</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 font-normal">
                        <button onclick="openDocPreview('ic')" class="p-4 border rounded-2xl flex items-center justify-between hover:bg-gray-50 transition group text-left">
                            <span class="text-sm text-gray-700">Ic / Passport</span>
                            <i class="fas fa-external-link-alt text-gray-300 group-hover:text-blue-500"></i>
                        </button>
                        <button onclick="openDocPreview('matric')" class="p-4 border rounded-2xl flex items-center justify-between hover:bg-gray-50 transition group text-left">
                            <span class="text-sm text-gray-700">Matric card</span>
                            <i class="fas fa-external-link-alt text-gray-300 group-hover:text-blue-500"></i>
                        </button>
                        <button onclick="openDocPreview('license')" class="p-4 border rounded-2xl flex items-center justify-between hover:bg-gray-50 transition group text-left">
                            <span class="text-sm text-gray-700">Driver license</span>
                            <i class="fas fa-external-link-alt text-gray-300 group-hover:text-blue-500"></i>
                        </button>
                    </div>
                </section>
            </div>

            <div class="px-8 py-5 bg-gray-50 border-t flex justify-end">
                <button onclick="closeDetailsModal()" class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-normal hover:bg-gray-100 transition">Close</button>
            </div>
        </div>
    </div>

    {{-- DOCUMENT PREVIEW MODAL --}}
    <div id="docPreviewModal" class="fixed inset-0 z-[60] hidden bg-black/80 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[90vh] flex flex-col shadow-2xl">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-sm font-normal text-gray-900 capitalize">Document preview: <span id="previewTitle"></span></h3>
                <button onclick="closeDocPreview()" class="text-gray-400 hover:text-gray-900 text-2xl">&times;</button>
            </div>
            <div class="flex-1 bg-gray-100 overflow-hidden">
                <iframe id="docFrame" class="w-full h-full" src=""></iframe>
            </div>
        </div>
    </div>

    <script>
        let currentCustomer = null;

        function openDetailsModal(matricNum) {
            fetch(`/staff/customermanagement-crud/${matricNum}`)
                .then(response => response.json())
                .then(data => {
                    currentCustomer = data;
                    
                    // Header
                    document.getElementById('modalName').innerText = data.name.toLowerCase();
                    document.getElementById('modalMatric').innerText = data.matricNum;
                    
                    // Academic
                    document.getElementById('det-faculty').innerText = data.faculty ? data.faculty.toLowerCase() : 'N/A';
                    document.getElementById('det-college').innerText = data.collegeAddress || 'N/A';
                    
                    // NEW: Address fields
                    document.getElementById('det-address').innerText = data.address || 'N/A';
                    document.getElementById('det-city').innerText = data.city ? data.city.toLowerCase() : 'N/A';
                    document.getElementById('det-postcode').innerText = data.postcode || 'N/A';
                    document.getElementById('det-state').innerText = data.state ? data.state.toLowerCase() : 'N/A';

                    // Bank & Emergency
                    document.getElementById('det-bank').innerText = data.bankName || 'N/A';
                    document.getElementById('det-acc').innerText = data.accountNum || 'N/A';
                    document.getElementById('det-emergency').innerText = `${data.emeName?.toLowerCase() || 'N/A'} (${data.emeRelation || 'N/A'})`;
                    document.getElementById('det-eme-phone').innerText = data.emePhoneNum || 'N/A';

                    document.getElementById('detailsModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openDocPreview(type) {
            const columnMap = { 'ic': 'docIcPassport', 'matric': 'docMatric', 'license': 'docLicense' };
            const filePath = currentCustomer[columnMap[type]];
            if (filePath) {
                document.getElementById('docFrame').src = `${window.location.origin}/storage/${filePath}`;
                document.getElementById('previewTitle').innerText = type;
                document.getElementById('docPreviewModal').classList.remove('hidden');
            } else {
                alert("No file found.");
            }
        }

        function closeDocPreview() {
            document.getElementById('docPreviewModal').classList.add('hidden');
            document.getElementById('docFrame').src = "";
        }

        // Search Script
        document.getElementById('customerSearch').addEventListener('keyup', function() {
            let filter = this.value.toUpperCase();
            let rows = document.querySelector("tbody").rows;
            for (let i = 0; i < rows.length; i++) {
                rows[i].style.display = rows[i].textContent.toUpperCase().includes(filter) ? "" : "none";
            }
        });
    </script>
</x-layouts.staff>
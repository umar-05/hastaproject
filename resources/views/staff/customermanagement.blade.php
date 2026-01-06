<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Customers</h1>
                <p class="text-gray-500">Manage customer information and records</p>
            </div>

            {{-- Flash Message Alert --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Action Bar --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex flex-1 items-center gap-3 max-w-2xl">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="customerSearch" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl bg-white focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Search by name, matric, faculty or college...">
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Matric Number</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">IC Number</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Phone</th>
                            {{-- NEW COLUMNS --}}
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Faculty</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">College Address</th>
                            
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-5 text-sm font-semibold text-gray-600">{{ $customer->matricNum }}</td>
                                <td class="px-6 py-5 text-sm font-bold text-gray-900">{{ $customer->name }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $customer->icNum_passport ?? 'N/A' }}</td>
                                <td class="px-6 py-5 text-sm text-gray-500">{{ $customer->email }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $customer->phoneNum ?? 'N/A' }}</td>
                                
                                {{-- NEW DATA CELLS --}}
                                <td class="px-6 py-5 text-sm text-gray-600 italic uppercase">{{ $customer->faculty ?? 'N/A' }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600 truncate max-w-[150px]">{{ $customer->college_address ?? 'N/A' }}</td>
                                
                                <td class="px-6 py-5 text-center">
                                    <button onclick="openDetailsModal('{{ $customer->matricNum }}')" class="text-red-600 hover:text-red-800 transition-all group">
                                        <i class="far fa-eye text-lg group-hover:scale-110"></i>
                                        <span class="block text-[10px] font-bold uppercase">View</span>
                                    </button>
                                </td>

                                <td class="px-6 py-5 text-right text-sm">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('staff.customermanagement-crud.edit', $customer->matricNum) }}" class="text-green-600 hover:text-green-800 transition">
                                            <i class="far fa-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('staff.customermanagement-crud.destroy', $customer->matricNum) }}" method="POST" onsubmit="return confirm('Delete this customer?');" class="inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                                <i class="far fa-trash-alt text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-10 text-center text-gray-400 italic">
                                    No customer records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CUSTOMER DETAILS MODAL --}}
    <div id="detailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-3xl max-w-4xl w-full mx-4 overflow-hidden shadow-2xl transform transition-all">
            <div class="px-8 py-6 border-b flex justify-between items-center bg-gray-50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="modalName">Customer Details</h3>
                    <p class="text-sm text-red-600 font-mono" id="modalMatric"></p>
                </div>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            
            <div class="p-8 max-h-[75vh] overflow-y-auto custom-scrollbar">
                
                {{-- NEW SECTION: Academic & Residence Information --}}
                <div class="mb-10">
                    <h4 class="text-gray-400 font-bold uppercase text-xs tracking-[0.2em] mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg mr-2"><i class="fas fa-university"></i></span>
                        Academic & Residence Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-400 font-bold uppercase">Faculty</p>
                            <p id="det-faculty" class="text-sm font-semibold text-gray-800 italic uppercase"></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-400 font-bold uppercase">College Address</p>
                            <p id="det-college" class="text-sm font-semibold text-gray-800"></p>
                        </div>
                    </div>
                </div>

                {{-- 1. Personal & Address Information (Permanent) --}}
                <div class="mb-10">
                    <h4 class="text-gray-400 font-bold uppercase text-xs tracking-[0.2em] mb-6 flex items-center">
                        <span class="bg-red-100 text-red-600 p-1.5 rounded-lg mr-2"><i class="fas fa-map-marker-alt"></i></span>
                        Permanent Address Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-400 font-bold uppercase">Full Address</p>
                            <p id="det-address" class="text-sm font-semibold text-gray-800"></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-400 font-bold uppercase">City</p>
                            <p id="det-city" class="text-sm font-semibold text-gray-800"></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-400 font-bold uppercase">Postcode</p>
                            <p id="det-postcode" class="text-sm font-semibold text-gray-800"></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-400 font-bold uppercase">State</p>
                            <p id="det-state" class="text-sm font-semibold text-gray-800"></p>
                        </div>
                    </div>
                </div>

                {{-- 2. Emergency Contact --}}
                <div class="mb-10 p-6 bg-red-50 rounded-2xl border border-red-100">
                    <h4 class="text-red-600 font-bold uppercase text-xs tracking-[0.2em] mb-6 flex items-center">
                        <span class="bg-white p-1.5 rounded-lg mr-2 shadow-sm"><i class="fas fa-phone-alt"></i></span>
                        Emergency Contact
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <p class="text-[11px] text-red-400 font-bold uppercase">Contact Name</p>
                            <p id="det-eme-name" class="text-sm font-bold text-gray-900"></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] text-red-400 font-bold uppercase">Phone Number</p>
                            <p id="det-eme-phone" class="text-sm font-bold text-gray-900"></p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] text-red-400 font-bold uppercase">Relationship</p>
                            <p id="det-eme-rel" class="text-sm font-bold text-gray-900"></p>
                        </div>
                    </div>
                </div>

                {{-- 3. Bank Information --}}
                <div class="mb-10">
                    <h4 class="text-gray-400 font-bold uppercase text-xs tracking-[0.2em] mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg mr-2"><i class="fas fa-university"></i></span>
                        Bank Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50/50">
                            <p class="text-[11px] text-gray-400 font-bold uppercase mb-1">Bank Name</p>
                            <p id="det-bank" class="text-sm font-bold text-gray-900 italic uppercase"></p>
                        </div>
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50/50">
                            <p class="text-[11px] text-gray-400 font-bold uppercase mb-1">Account Number</p>
                            <p id="det-acc" class="text-sm font-mono font-bold text-gray-900 tracking-wider"></p>
                        </div>
                    </div>
                </div>

                {{-- 4. Customer Documents --}}
                <div>
                    <h4 class="text-gray-400 font-bold uppercase text-xs tracking-[0.2em] mb-6 flex items-center">
                        <span class="bg-orange-100 text-orange-600 p-1.5 rounded-lg mr-2"><i class="fas fa-file-pdf"></i></span>
                        Customer Documents
                    </h4>
                    <div class="flex flex-wrap gap-4">
                        <button onclick="openDocPreview('ic')" class="flex-1 min-w-[200px] p-4 border rounded-2xl flex items-center justify-between hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-id-card text-2xl text-blue-500"></i>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-gray-900">IC / Passport</p>
                                    <p class="text-[10px] text-gray-400" id="file-ic-name">Document_IC.pdf</p>
                                </div>
                            </div>
                            <i class="fas fa-external-link-alt text-gray-300 group-hover:text-blue-500"></i>
                        </button>

                        <button onclick="openDocPreview('matric')" class="flex-1 min-w-[200px] p-4 border rounded-2xl flex items-center justify-between hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-graduation-cap text-2xl text-green-500"></i>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-gray-900">Matric Card</p>
                                    <p class="text-[10px] text-gray-400" id="file-matric-name">Matric_Card.pdf</p>
                                </div>
                            </div>
                            <i class="fas fa-external-link-alt text-gray-300 group-hover:text-green-500"></i>
                        </button>

                        <button onclick="openDocPreview('license')" class="flex-1 min-w-[200px] p-4 border rounded-2xl flex items-center justify-between hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-car text-2xl text-orange-500"></i>
                                <div class="text-left">
                                    <p class="text-xs font-bold text-gray-900">Driver's License</p>
                                    <p class="text-[10px] text-gray-400" id="file-license-name">License_Final.pdf</p>
                                </div>
                            </div>
                            <i class="fas fa-external-link-alt text-gray-300 group-hover:text-orange-500"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-gray-50 border-t flex justify-end">
                <button onclick="closeDetailsModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">Close</button>
            </div>
        </div>
    </div>

    {{-- DOCUMENT PREVIEW MODAL --}}
    <div id="docPreviewModal" class="fixed inset-0 z-[60] hidden bg-black/80 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-5xl h-[90vh] flex flex-col shadow-2xl">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-file-alt text-red-600"></i> Document Preview: <span id="previewTitle"></span>
                </h3>
                <button onclick="closeDocPreview()" class="text-gray-400 hover:text-gray-900 text-2xl">&times;</button>
            </div>
            <div class="flex-1 bg-gray-200 relative overflow-hidden">
                <iframe id="docFrame" class="w-full h-full" src=""></iframe>
            </div>
        </div>
    </div>

    <script>
    let currentCustomer = null;

    // Search Logic (Updated to filter by index 5 and 6 for Faculty/College)
    document.getElementById('customerSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("tbody").rows;

        for (let i = 0; i < rows.length; i++) {
            let matric = rows[i].cells[0].textContent.toUpperCase();
            let name = rows[i].cells[1].textContent.toUpperCase();
            let faculty = rows[i].cells[5].textContent.toUpperCase();
            let college = rows[i].cells[6].textContent.toUpperCase();

            if (matric.indexOf(filter) > -1 || name.indexOf(filter) > -1 || faculty.indexOf(filter) > -1 || college.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });

    function openDetailsModal(matricNum) {
        fetch(`/staff/customermanagement-crud/${matricNum}`)
            .then(response => response.json())
            .then(data => {
                currentCustomer = data;
                
                document.getElementById('modalName').innerText = data.name;
                document.getElementById('modalMatric').innerText = data.matricNum;

                // Academic & College
                document.getElementById('det-faculty').innerText = data.faculty || 'N/A';
                document.getElementById('det-college').innerText = data.college_address || 'N/A';

                // Address
                document.getElementById('det-address').innerText = data.address || 'Not Provided';
                document.getElementById('det-city').innerText = data.city || '-';
                document.getElementById('det-postcode').innerText = data.postcode || '-';
                document.getElementById('det-state').innerText = data.state || '-';
                
                // Emergency
                document.getElementById('det-eme-name').innerText = data.eme_name || 'N/A';
                document.getElementById('det-eme-phone').innerText = data.emephoneNum || 'N/A';
                document.getElementById('det-eme-rel').innerText = data.emerelation || 'N/A';
                
                // Bank
                document.getElementById('det-bank').innerText = data.bankName || 'N/A';
                document.getElementById('det-acc').innerText = data.accountNum || 'N/A';

                // Files
                document.getElementById('file-ic-name').innerText = data.doc_ic_passport ? 'Document_IC.pdf' : 'No file';
                document.getElementById('file-matric-name').innerText = data.doc_matric ? 'Matric_Card.pdf' : 'No file';
                document.getElementById('file-license-name').innerText = data.doc_license ? 'License_Final.pdf' : 'No file';

                document.getElementById('detailsModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden'; 
            });
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openDocPreview(type) {
        const columnMap = { 'ic': 'doc_ic_passport', 'matric': 'doc_matric', 'license': 'doc_license' };
        const filePath = currentCustomer[columnMap[type]];
        
        if (filePath) {
            const fileUrl = window.location.origin + '/storage/' + filePath;
            document.getElementById('docFrame').src = fileUrl;
            document.getElementById('previewTitle').innerText = type.toUpperCase();
            document.getElementById('docPreviewModal').classList.remove('hidden');
        } else {
            alert("No document file record found for this customer.");
        }
    }

    function closeDocPreview() {
        document.getElementById('docPreviewModal').classList.add('hidden');
        document.getElementById('docFrame').src = "";
    }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</x-layouts.staff>
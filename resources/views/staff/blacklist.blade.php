<x-layouts.staff>
    <div class="py-12 bg-gray-50 min-h-screen">
        {{-- Changed max-w-7xl to max-w-[95%] for a wider view --}}
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Blacklist Records</h1>
                <p class="text-gray-500">Manage customers with restricted access to services</p>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm animate-pulse">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Stats Card --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8 flex items-center gap-6 max-w-xs">
                <div class="bg-red-50 p-4 rounded-2xl text-red-600">
                    <i class="fas fa-user-slash text-2xl"></i>
                </div>
                <div>
                    <span class="text-3xl font-bold text-gray-900">{{ $count }}</span>
                    <p class="text-gray-500 text-sm font-medium">Total Blacklisted</p>
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" id="blacklistSearch" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Search records...">
                </div>
                
                <button onclick="toggleModal('addBlacklistModal')" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all shadow-md transform hover:scale-105">
                    <i class="fas fa-user-plus"></i>
                    Add to Blacklist
                </button>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Matric Number</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Customer Name</th>
                            {{-- NEW COLUMNS --}}
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Faculty</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">College</th>
                            {{-- END NEW COLUMNS --}}
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">IC Number</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50" id="blacklistTableBody">
                        @forelse($blacklisted as $customer)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-5 text-sm font-semibold text-blue-600">{{ $customer->matricNum }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600 text-xs font-bold">
                                            {{ substr($customer->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ $customer->name }}</span>
                                    </div>
                                </td>
                                {{-- NEW DATA CELLS --}}
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $customer->faculty ?? 'N/A' }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $customer->collegeAddress ?? 'N/A' }}</td>
                                {{-- END NEW DATA CELLS --}}
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $customer->icNum_passport ?? 'N/A' }}</td>
                                <td class="px-6 py-5 text-sm text-gray-500">{{ $customer->email }}</td>
                                <td class="px-6 py-5 text-sm text-gray-700 italic">
                                    {{ str_replace('blacklisted: ', '', $customer->accStatus) }}
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-3">
                                        {{-- Edit Button (Green) --}}
                                        <button onclick="openEditModal('{{ $customer->matricNum }}', '{{ $customer->name }}', '{{ str_replace('blacklisted: ', '', $customer->accStatus) }}')" 
                                                title="Edit Reason"
                                                class="text-green-500 hover:text-green-700 transition-colors p-2 hover:bg-green-50 rounded-lg">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>

                                        {{-- Delete/Restore Form (Red) --}}
                                        <form action="{{ route('staff.blacklist.destroy', $customer->matricNum) }}" method="POST" onsubmit="return confirm('Restore this customer? They will be removed from the blacklist.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Restore Customer"
                                                    class="text-red-500 hover:text-red-700 transition-colors p-2 hover:bg-red-50 rounded-lg">
                                                <i class="fas fa-trash-alt text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-gray-400 italic">No blacklisted records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL: ADD TO BLACKLIST --}}
    <div id="addBlacklistModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-3xl max-w-2xl w-full mx-4 overflow-hidden shadow-2xl">
            {{-- Modal Header --}}
            <div class="px-8 py-6 border-b flex justify-between items-center bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="bg-red-600 text-white p-2 rounded-lg"><i class="fas fa-user-slash"></i></div>
                    <h3 class="text-xl font-bold text-gray-900">Add to Blacklist</h3>
                </div>
                <button onclick="toggleModal('addBlacklistModal')" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('staff.blacklist.store') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-1 relative">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">Matric Number *</label>
                        <div class="relative">
                            <input type="text" name="matricNum" id="add_matric" required 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500 pr-10" 
                                placeholder="Enter Matric...">
                            {{-- Spinner Icon --}}
                            <div id="search_spinner" class="hidden absolute right-3 top-3.5 text-gray-400">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        <p id="search_status" class="text-[10px] mt-1 font-semibold min-h-[15px]"></p>
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">Name</label>
                        <input type="text" id="add_name" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                    </div>

                    {{-- NEW FIELDS: Faculty & College --}}
                    <div class="col-span-1">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">Faculty</label>
                        <input type="text" id="add_faculty" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed" placeholder="Auto-filled">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">College</label>
                        <input type="text" id="add_college" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed" placeholder="Auto-filled">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">Reason *</label>
                        <textarea name="reason" required rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500" placeholder="Describe the reason for blacklisting..."></textarea>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-red-50 rounded-xl border border-red-100 flex gap-3">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-1"></i>
                    <p class="text-xs text-red-700 leading-relaxed"><span class="font-bold">Warning:</span> Blacklisted customers cannot book any vehicles until they are removed from this list.</p>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('addBlacklistModal')" class="px-6 py-2.5 font-bold text-gray-500">Cancel</button>
                    <button type="submit" class="px-8 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg">Confirm Blacklist</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDIT BLACKLIST --}}
    <div id="editBlacklistModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-3xl max-w-2xl w-full mx-4 overflow-hidden shadow-2xl">
            <div class="px-8 py-6 border-b flex justify-between items-center bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="bg-green-600 text-white p-2 rounded-lg"><i class="fas fa-edit"></i></div>
                    <h3 class="text-xl font-bold text-gray-900">Update Blacklist Reason</h3>
                </div>
                <button onclick="toggleModal('editBlacklistModal')" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            
            <form action="{{ route('staff.blacklist.store') }}" method="POST" class="p-8">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">Matric Number</label>
                        <input type="text" name="matricNum" id="edit_matric" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">Name</label>
                        <input type="text" id="edit_name" readonly class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-400">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold mb-2 uppercase text-gray-500 tracking-wide">Reason *</label>
                        <textarea name="reason" id="edit_reason" required rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('editBlacklistModal')" class="px-6 py-2.5 font-bold text-gray-500">Cancel</button>
                    <button type="submit" class="px-8 py-2.5 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition shadow-lg">Update Reason</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
        document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
    }

    // Auto-fill Fetch Logic
    document.getElementById('add_matric').addEventListener('input', function() {
        let matric = this.value;
        let status = document.getElementById('search_status');
        let spinner = document.getElementById('search_spinner');
        
        // Input Fields
        let nameInput = document.getElementById('add_name');
        let facultyInput = document.getElementById('add_faculty');
        let collegeInput = document.getElementById('add_college');
        
        if(matric.length >= 4) {
            status.innerText = "Searching...";
            status.className = "text-[10px] mt-1 text-blue-500";
            spinner.classList.remove('hidden');

            fetch(`/staff/reports/customer-search/${matric}`)
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    spinner.classList.add('hidden');
                    if(data) {
                        // Populate fields
                        nameInput.value = data.name;
                        facultyInput.value = data.faculty || 'N/A';
                        collegeInput.value = data.collegeAddress || 'N/A';
                        
                        status.innerText = "Customer found!";
                        status.className = "text-[10px] mt-1 text-green-500";
                    } else {
                        // Clear fields if not found
                        nameInput.value = '';
                        facultyInput.value = '';
                        collegeInput.value = '';
                        
                        status.innerText = "Customer not found.";
                        status.className = "text-[10px] mt-1 text-red-500";
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    spinner.classList.add('hidden');
                    status.innerText = "Error searching. Check console.";
                    status.className = "text-[10px] mt-1 text-red-500";
                });
        } else {
            // Clear fields if input is too short
            nameInput.value = '';
            facultyInput.value = '';
            collegeInput.value = '';
            status.innerText = "";
            spinner.classList.add('hidden');
        }
    });

    // Search/Filter Table Logic
    document.getElementById('blacklistSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('#blacklistTableBody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    // Modal Filler for Edit
    function openEditModal(matric, name, reason) {
        document.getElementById('edit_matric').value = matric;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_reason').value = reason;
        toggleModal('editBlacklistModal');
    }
</script>
</x-layouts.staff>
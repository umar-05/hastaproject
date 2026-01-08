<x-layouts.staff>
    <div class="p-8 font-sans bg-[#F8FAFC] min-h-screen">
        
        {{-- Success Message --}}
        @if (session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="max-w-6xl mx-auto mb-6 bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-2xl relative shadow-sm" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="max-w-6xl mx-auto mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Reward Management</h1>
            <p class="text-slate-500 text-sm">Manage loyalty rewards and referral codes</p>
        </div>

        {{-- Stats Cards --}}
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            {{-- Total Rewards --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Rewards</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-gift"></i>
                </div>
            </div>
            {{-- Active Rewards --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Rewards</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['active'] ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-green-50 text-green-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            {{-- Total Claims --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Claims</p>
                    <h3 class="text-3xl font-bold text-slate-800">0</h3> 
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            {{-- Available Slots --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Available Slots</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['slots'] ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-unlock"></i>
                </div>
            </div>
        </div>

        {{-- Loyalty Rewards Container --}}
        <div class="max-w-6xl mx-auto bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 relative">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-2xl font-bold text-slate-800">Loyalty Rewards</h2>
                <button onclick="openCreateModal()" class="bg-[#B91C1C] hover:bg-red-800 text-white px-8 py-3 rounded-2xl font-bold transition shadow-lg shadow-red-100 active:scale-95">
                    + Create Reward
                </button>
            </div>

            {{-- 1. ACTIVE REWARDS TABLE --}}
            <div class="mb-14">
                <h4 class="text-xs font-bold text-slate-400 mb-6 uppercase tracking-widest">Active Rewards ({{ $activeRewards->count() }})</h4>
                <table class="w-full text-left">
                    <thead class="text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <tr>
                            <th class="pb-4">Code</th>
                            <th class="pb-4">Type</th>
                            <th class="pb-4">Value</th>
                            <th class="pb-4">Stamps Required</th>
                            <th class="pb-4">Expiry Date</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Claims Progress</th>
                            <th class="pb-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($activeRewards as $reward)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="py-6"><span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-lg font-bold text-xs">{{ $reward->voucherCode }}</span></td>
                            <td class="py-6 text-slate-600">{{ $reward->rewardType }}</td>
                            <td class="py-6 font-bold text-slate-800">{{ $reward->rewardAmount }}{{ $reward->rewardType == 'Discount' ? '%' : '' }}</td>
                            <td class="py-6 text-slate-500">{{ $reward->rewardPoints }} stamps</td>
                            
                            <td class="py-6 text-slate-500 text-xs font-semibold">
                                {{ $reward->expiryDate ? \Carbon\Carbon::parse($reward->expiryDate)->format('d M Y') : 'No Expiry' }}
                            </td>
                            
                            <td class="py-6">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Available</span>
                            </td>

                            <td class="py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-24 bg-slate-100 rounded-full h-1.5">
                                        @php 
                                            $percent = 0; // Placeholder
                                        @endphp
                                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400">0/{{ $reward->totalClaimable }}</span>
                                </div>
                            </td>
                            <td class="py-6 text-right">
                                <button onclick="openEditModal({{ json_encode($reward) }})" class="text-blue-500 hover:text-blue-700 transition transform hover:scale-110 mr-3" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                {{-- FIX: Added onclick event --}}
                                <button onclick="confirmDelete('{{ $reward->rewardID }}')" class="text-red-400 hover:text-red-600 transition transform hover:scale-110" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="py-10 text-center text-slate-400">No active rewards found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 2. INACTIVE REWARDS TABLE --}}
            <div>
                <h4 class="text-xs font-bold text-slate-400 mb-6 uppercase tracking-widest">Inactive Rewards ({{ $inactiveRewards->count() }})</h4>
                <table class="w-full text-left">
                    <thead class="text-[10px] text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <tr>
                            <th class="pb-4">Code</th>
                            <th class="pb-4">Type</th>
                            <th class="pb-4">Value</th>
                            <th class="pb-4">Stamps Required</th>
                            <th class="pb-4">Expiry Date</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($inactiveRewards as $reward)
                        <tr class="border-b border-slate-50 opacity-60">
                            <td class="py-6"><span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg font-bold text-xs">{{ $reward->voucherCode }}</span></td>
                            <td class="py-6 text-slate-500">{{ $reward->rewardType }}</td>
                            <td class="py-6 font-bold text-slate-500">{{ $reward->rewardAmount }}%</td>
                            <td class="py-6 text-slate-400">{{ $reward->rewardPoints }} stamps</td>

                            <td class="py-6 text-slate-400 text-xs">
                                {{ $reward->expiryDate ? \Carbon\Carbon::parse($reward->expiryDate)->format('d M Y') : 'No Expiry' }}
                            </td>
                            
                            <td class="py-6">
                                <span class="bg-slate-200 text-slate-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Unavailable</span>
                            </td>

                            <td class="py-6 text-right">
                                <button onclick="openEditModal({{ json_encode($reward) }})" class="text-blue-500 hover:text-blue-700 transition transform hover:scale-110 mr-3" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                {{-- FIX: Added onclick event --}}
                                <button onclick="confirmDelete('{{ $reward->rewardID }}')" class="text-red-400 hover:text-red-600 transition transform hover:scale-110" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- REWARD MODAL (Create & Edit) --}}
    <div id="rewardModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center p-4">
        <div class="bg-white rounded-[3rem] p-10 max-w-xl w-full shadow-2xl relative animate-in fade-in zoom-in duration-300">
            <h2 id="modalTitle" class="text-3xl font-black text-slate-800 mb-8">Create New Reward</h2>
            
            <form id="rewardForm" action="{{ route('staff.reward.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm font-bold">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Code Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="E.G. OFF10" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700 uppercase" required>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Type</label>
                        <select id="type" name="type" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                            <option value="Discount">Discount</option>
                            <option value="Extra Hours">Extra Hours</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Value</label>
                        <input type="number" id="value" name="value" value="{{ old('value') }}" placeholder="10" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Stamps Required</label>
                        <input type="number" id="points_required" name="points_required" value="{{ old('points_required') }}" placeholder="5" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Max Customers</label>
                        <input type="number" id="totalClaimable" name="totalClaimable" value="{{ old('totalClaimable') }}" placeholder="10" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Expiry Date</label>
                        <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status</label>
                        <select id="rewardStatus" name="rewardStatus" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="button" onclick="toggleRewardModal(false)" class="flex-1 py-4 border-2 border-slate-100 text-slate-400 font-black rounded-2xl hover:bg-slate-50 transition active:scale-95">Cancel</button>
                    <button type="submit" class="flex-1 py-4 bg-[#E3342F] text-white font-black rounded-2xl hover:bg-red-700 transition shadow-xl shadow-red-200 active:scale-95">Save Reward</button>
                </div>
            </form>
        </div>
    </div>

    {{-- HIDDEN DELETE FORM --}}
    <form id="deleteRewardForm" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- CUSTOM DELETE CONFIRMATION MODAL --}}
<div id="deleteModal" class="fixed inset-0 z-[110] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-[2px] transition-opacity opacity-0" id="deleteBackdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 scale-95" id="deletePanel">
                
                <div class="bg-white px-8 pt-10 pb-8">
                    <div class="flex flex-col items-center">
                        <div class="mx-auto flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full bg-red-50 mb-6 animate-pulse">
                            <i class="fas fa-trash-alt text-3xl text-[#E3342F]"></i>
                        </div>
                        
                        <div class="text-center">
                            <h3 class="text-2xl font-black text-slate-800 mb-2" id="modal-title">Delete Reward?</h3>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">
                                Are you sure you want to remove this reward? <br>
                                This action <span class="text-[#E3342F] font-bold">cannot be undone</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-8 py-6 flex gap-4">
                    <button type="button" onclick="closeDeleteModal()" 
                        class="flex-1 py-4 bg-white border border-slate-200 text-slate-500 font-bold rounded-2xl hover:bg-slate-50 hover:text-slate-700 hover:border-slate-300 transition duration-200 shadow-sm active:scale-95">
                        Cancel
                    </button>
                    <button type="button" onclick="executeDelete()" 
                        class="flex-1 py-4 bg-[#E3342F] text-white font-bold rounded-2xl hover:bg-red-700 transition duration-200 shadow-lg shadow-red-200 active:scale-95 flex items-center justify-center gap-2 group">
                        <span>Yes, Delete</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        function toggleRewardModal(show) {
            const modal = document.getElementById('rewardModal');
            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function openCreateModal() {
            document.getElementById('rewardForm').reset();
            document.getElementById('modalTitle').innerText = 'Create New Reward';
            document.getElementById('methodField').value = 'POST'; 
            document.getElementById('rewardForm').action = "{{ route('staff.reward.store') }}"; 
            toggleRewardModal(true);
        }

        function openEditModal(reward) {
    // 1. Populate basic fields
    document.getElementById('name').value = reward.voucherCode;
    document.getElementById('type').value = reward.rewardType;
    document.getElementById('value').value = reward.rewardAmount;
    document.getElementById('points_required').value = reward.rewardPoints;
    document.getElementById('totalClaimable').value = reward.totalClaimable;
    document.getElementById('rewardStatus').value = reward.rewardStatus;
    
    // 2. Date Handling
    if (reward.expiryDate) {
        let dateValue = reward.expiryDate;
        if (dateValue.includes('T')) {
            dateValue = dateValue.split('T')[0];
        } 
        else if (dateValue.includes(' ')) {
            dateValue = dateValue.split(' ')[0];
        }
        document.getElementById('expiry_date').value = dateValue;
    } else {
        document.getElementById('expiry_date').value = '';
    }

    // 3. Set Modal Title & Method
    document.getElementById('modalTitle').innerText = 'Edit Reward';
    document.getElementById('methodField').value = 'PUT'; 

    // 4. FIX: Use a unique placeholder (:id) to prevent replacing the port number
    let updateUrl = "{{ route('staff.reward.update', ':id') }}"; 
    updateUrl = updateUrl.replace(':id', reward.rewardID); 
    
    document.getElementById('rewardForm').action = updateUrl;

    toggleRewardModal(true);
}

        let targetDeleteId = null;

        function confirmDelete(rewardID) {
            targetDeleteId = rewardID; // Store the ID
            
            const modal = document.getElementById('deleteModal');
            const backdrop = document.getElementById('deleteBackdrop');
            const panel = document.getElementById('deletePanel');

            // Show Modal Container
            modal.classList.remove('hidden');
            
            // Animate In (Small delay allows CSS transition to trigger)
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const backdrop = document.getElementById('deleteBackdrop');
            const panel = document.getElementById('deletePanel');

            // Animate Out
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'scale-100');
            panel.classList.add('opacity-0', 'scale-95');

            // Hide Modal Container after animation finishes (300ms)
            setTimeout(() => {
                modal.classList.add('hidden');
                targetDeleteId = null; // Clear ID
            }, 300);
        }

        function executeDelete() {
            if (!targetDeleteId) return;

            const form = document.getElementById('deleteRewardForm');
            
            // Generate URL
            let url = "{{ route('staff.reward.destroy', ':id') }}";
            url = url.replace(':id', targetDeleteId);
            
            form.action = url;
            form.submit();
        }
    </script>
</x-layouts.staff>
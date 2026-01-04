<x-layouts.staff>
    <div class="p-8 font-sans bg-[#F8FAFC] min-h-screen">
        {{-- Header Section --}}
        <div class="max-w-6xl mx-auto mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Reward Management</h1>
            <p class="text-slate-500 text-sm">Manage loyalty rewards and referral codes</p>
        </div>

        {{-- Dynamic Stats Cards --}}
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            {{-- Total Rewards --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Rewards</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total'] }}</h3>
                </div>
                <div class="w-10 h-10 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-gift"></i>
                </div>
            </div>
            {{-- Active Rewards --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Rewards</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['active'] }}</h3>
                </div>
                <div class="w-10 h-10 bg-green-50 text-green-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            {{-- Total Claims (Placeholder logic for now) --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Claims</p>
                    <h3 class="text-3xl font-bold text-slate-800">15</h3>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            {{-- Available Slots (Sum of totalClaimable) --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Available Slots</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['slots'] }}</h3>
                </div>
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-unlock"></i>
                </div>
            </div>
        </div>

        {{-- Loyalty Rewards Container --}}
        <div class="max-w-6xl mx-auto bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 relative">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-xl font-bold text-slate-800">Loyalty Rewards</h2>
                <button onclick="toggleRewardModal(true)" class="bg-[#B91C1C] hover:bg-red-800 text-white px-8 py-3 rounded-2xl font-bold transition shadow-lg shadow-red-100 active:scale-95">
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
                            <th class="pb-4">Claims Progress</th>
                            <th class="pb-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($activeRewards as $reward)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="py-6"><span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-lg font-bold text-xs">{{ $reward->voucherCode }}</span></td>
                            <td class="py-6 text-slate-600">{{ $reward->rewardType }}</td>
                            <td class="py-6 font-bold text-slate-800">{{ $reward->rewardAmount }}{{ $reward->rewardType == 'Discount' ? '%' : ' hr' }}</td>
                            <td class="py-6 text-slate-500">{{ $reward->rewardPoints }} stamps</td>
                            <td class="py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-24 bg-slate-100 rounded-full h-1.5">
                                        {{-- Progress Bar Logic: Placeholder 0 until you have a claims table --}}
                                        <div class="bg-green-500 h-1.5 rounded-full" style="width: 0%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400">0/{{ $reward->totalClaimable }}</span>
                                </div>
                            </td>
                            <td class="py-6 text-right">
                                <button class="text-blue-400 hover:text-blue-600 mr-3"><i class="fas fa-pen"></i></button>
                                <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-10 text-center text-slate-400">No active rewards found.</td></tr>
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
                            <td class="py-6 text-right">
                                <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Reward Modal --}}
    <div id="rewardModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-[3rem] p-10 max-w-xl w-full shadow-2xl relative animate-in fade-in zoom-in duration-300">
            <h2 class="text-3xl font-black text-slate-800 mb-8">Create New Reward</h2>
            <form action="{{ route('staff.rewards.store') }}" method="POST" class="space-y-5">
                @csrf
                {{-- Form fields remain the same as your previous working version --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Code Name</label>
                    <input type="text" name="voucherCode" placeholder="E.G. OFF10" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700 uppercase" required>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Type</label>
                        <select name="rewardType" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
                            <option value="Discount">Discount</option>
                            <option value="Extra Hours">Extra Hours</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Value (%)</label>
                        <input type="number" name="rewardAmount" placeholder="10" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Stamps Required</label>
                        <input type="number" name="rewardPoints" placeholder="5" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Max Customers</label>
                        <input type="number" name="totalClaimable" placeholder="10" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Expiry Date</label>
                        <input type="date" name="expiryDate" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status</label>
                        <select name="rewardStatus" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-red-500 font-bold text-slate-700">
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
    </script>
</x-layouts.staff>
@php
    // Fallback variables to prevent "Undefined variable" errors 
    // without modifying the Controller
    $stats = $stats ?? ['total' => 0, 'active' => 0, 'claims' => 0, 'slots' => 10];
    $activeRewards = $activeRewards ?? collect();
    $inactiveRewards = $inactiveRewards ?? collect();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reward Management - Staff Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .progress-bg { background-color: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; }
        .progress-fill { background-color: #10b981; height: 100%; border-radius: 4px; }
    </style>
</head>
<body class="min-h-screen p-8">

<div class="max-w-6xl mx-auto">
    <div class="mb-10">
        <h1 class="text-2xl font-bold text-gray-900">Reward Management</h1>
        <p class="text-gray-500 text-sm">Manage loyalty rewards and referral codes</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1 tracking-wide uppercase">Total Rewards</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
            </div>
            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500">
                <i class="fas fa-gift text-lg"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1 tracking-wide uppercase">Active Rewards</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['active'] }}</h3>
            </div>
            <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center text-green-500">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1 tracking-wide uppercase">Total Claims</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['claims'] }}</h3>
            </div>
            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                <i class="fas fa-users text-lg"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div>
                <p class="text-xs font-medium text-gray-400 mb-1 tracking-wide uppercase">Available Slots</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['slots'] }}</h3>
            </div>
            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-500">
                <i class="fas fa-box-open text-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Loyalty Rewards</h2>
            <button onclick="toggleModal()" class="bg-[#B91C1C] hover:bg-red-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition shadow-lg shadow-red-100">
                <i class="fas fa-plus"></i> Create Reward
            </button>
        </div>

        <div class="p-8">
            <div class="mb-10">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Active Rewards ({{ $activeRewards->count() }})</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] text-gray-400 uppercase font-black border-b border-gray-100">
                            <th class="pb-4">Code</th>
                            <th class="pb-4">Type</th>
                            <th class="pb-4">Value</th>
                            <th class="pb-4">Stamps Required</th>
                            <th class="pb-4">Claims Progress</th>
                            <th class="pb-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($activeRewards as $reward)
                        <tr class="border-b border-gray-50 group hover:bg-gray-50 transition">
                            <td class="py-5 font-mono text-xs"><span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-md font-bold uppercase">{{ $reward->voucherCode }}</span></td>
                            <td class="py-5 font-medium text-gray-700">{{ $reward->rewardType }}</td>
                            <td class="py-5 font-bold text-gray-900">{{ $reward->rewardAmount }}</td>
                            <td class="py-5 text-gray-600">{{ $reward->rewardPoints }} stamps</td>
                            <td class="py-5 w-48">
                                <div class="flex items-center gap-3">
                                    <div class="progress-bg flex-1"><div class="progress-fill w-0"></div></div>
                                    <span class="text-[11px] font-bold text-gray-400">0/{{ $reward->totalClaimable }}</span>
                                </div>
                            </td>
                            <td class="py-5 text-center">
                                <button class="text-blue-500 hover:text-blue-700 mx-2"><i class="fas fa-pen text-sm"></i></button>
                                <button class="text-red-500 hover:text-red-700 mx-2"><i class="fas fa-trash-alt text-sm"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-400 italic">No active rewards available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="rewardModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-[2.5rem] p-10 max-w-md w-full mx-4 shadow-2xl">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Create New Reward</h3>
        <form id="rewardForm" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Code Name</label>
                <input type="text" placeholder="e.g. LOYAL15" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition uppercase">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Type</label>
                    <select onchange="toggleFields(this.value)" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none">
                        <option value="discount">Discount</option>
                        <option value="extra_hours">Extra Hours</option>
                    </select>
                </div>
                <div>
                    <label id="value-label" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Value (%)</label>
                    <input type="number" step="0.1" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="toggleModal()" class="flex-1 py-3 border border-gray-200 text-gray-500 font-bold rounded-xl hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="flex-1 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition">Save Reward</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal() {
        document.getElementById('rewardModal').classList.toggle('hidden');
    }

    function toggleFields(val) {
        const label = document.getElementById('value-label');
        label.innerText = val === 'discount' ? 'Value (%)' : 'Value (hrs)';
    }

    document.getElementById('rewardForm').addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Reward successfully created (Design Only)!');
        toggleModal();
    });
</script>

</body>
</html>
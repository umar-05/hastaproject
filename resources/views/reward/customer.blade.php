<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loyalty Rewards') }}
        </h2>
    </x-slot>

    <style>
        .stamp { 
            width: 54px; height: 54px; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: bold; border: 2px solid #e5e7eb; border-radius: 50%; 
            font-size: 18px; color: #9ca3af;
            transition: all 0.3s ease;
        }
        .stamp.filled { 
            background-color: #ef4444; color: white; border-color: #ef4444; 
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
        }
    </style>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex gap-2 mb-8">
                <a href="/rewards" class="px-6 py-2 rounded-lg font-bold text-sm transition bg-red-600 text-white shadow-md">
                    Rewards Store
                </a>
                <a href="/rewards/my-claimed" class="px-6 py-2 rounded-lg font-bold text-sm transition bg-white text-gray-500 border border-gray-100 hover:bg-gray-50">
                    My Wallet
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-10 mb-10">
                <div class="flex items-start gap-4 mb-8">
                    <div class="mt-1 text-red-500">
                        <i class="fas fa-gift text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-1">Your Loyalty Status</h1>
                        <p class="text-gray-500 text-sm">
                            Earn 1 stamp for every rental of <span class="text-red-500 font-bold">10+ hours</span>. Every <span class="text-red-500 font-bold">3 stamps</span> = 1 reward!
                        </p>
                    </div>
                </div>

                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <div class="flex items-baseline gap-2 mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Stamps Earned</h2>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            @for ($i = 1; $i <= 9; $i++)
                                <div class="stamp {{ $i <= 5 ? 'filled' : '' }}">{{ $i }}</div>
                            @endfor
                        </div>
                    </div>

                    <div class="text-right flex flex-col items-end justify-center">
                        <span class="text-4xl font-bold text-red-500">5</span>
                        <span class="text-red-500 font-bold text-sm uppercase tracking-wider">Stamps</span>
                    </div>
                </div>

                <div class="mt-8 bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-3">
                    <span class="text-xl">🎉</span>
                    <p class="text-sm font-medium text-blue-800">
                        You have <span class="font-bold">5 stamps</span>. You can claim <span class="font-bold">1 reward</span> now!
                    </p>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Rewards</h2>
            
            <div class="space-y-4">
                <div class="bg-white rounded-3xl p-6 border border-gray-50 shadow-sm flex flex-col md:flex-row justify-between items-center transition hover:shadow-md">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[11px] font-bold rounded-lg uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-car"></i> Car Rental Discount
                            </span>
                            <span class="text-xs text-gray-400 font-medium tracking-tight">Cost: 3 stamps</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">15% Off Your Next Car Rental</h3>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Apply this code during checkout.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button onclick="claimReward(1, 'Car Rental Discount', '15% Off Your Next Car Rental', 'RENTAL-15P-8A3B')" 
                                class="px-10 py-3 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 shadow-lg shadow-red-100 transition active:scale-95">
                            Claim Reward
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-50 shadow-sm flex flex-col md:flex-row justify-between items-center transition hover:shadow-md">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[11px] font-bold rounded-lg uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-utensils"></i> Food Stall Discount
                            </span>
                            <span class="text-xs text-gray-400 font-medium tracking-tight">Cost: 3 stamps</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">20% Off Food Stall Purchase</h3>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Show this code at any partner food stall.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button onclick="claimReward(2, 'Food Stall Discount', '20% Off Food Stall Purchase', 'FOOD-20OFF-XYZ')" 
                                class="px-10 py-3 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 shadow-lg shadow-red-100 transition active:scale-95">
                            Claim Reward
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-gray-50 shadow-sm flex flex-col md:flex-row justify-between items-center opacity-60">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[11px] font-bold rounded-lg uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-clock"></i> Extra Hours
                            </span>
                            <span class="text-xs text-gray-400 font-medium tracking-tight">Cost: 6 stamps</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">+1.5 Free Hours</h3>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Redeem on your next rental session.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button class="px-10 py-3 bg-gray-100 text-gray-400 rounded-2xl font-bold cursor-not-allowed">
                            Need 1 More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="rewardModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-[40px] p-12 max-w-sm w-full mx-4 text-center shadow-2xl">
            <h3 class="font-bold text-2xl text-gray-900 mb-2">Reward Claimed!</h3>
            <p class="text-gray-500 mb-8 font-medium">Your discount code is:</p>
            
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl py-5 mb-8">
                <span id="modalCode" class="text-2xl font-mono font-bold text-gray-900 tracking-widest">---</span>
            </div>
            
            <button onclick="window.location.href='/rewards/my-claimed'" 
                class="w-full py-4 bg-red-600 text-white rounded-2xl font-bold text-lg hover:bg-red-700 shadow-xl shadow-red-200 transition-all active:scale-95 mb-4">
                Done
            </button>
            
            <button onclick="document.getElementById('rewardModal').classList.add('hidden')" class="text-gray-400 font-bold hover:text-gray-600 transition text-sm">
                Close
            </button>
        </div>
    </div>

    <script>
        function claimReward(id, title, description, code) {
            let claimed = JSON.parse(localStorage.getItem('claimedRewards') || '[]');
            const now = new Date();
            const expiry = new Date();
            expiry.setFullYear(now.getFullYear() + 1);

            if (!claimed.some(r => r.id === id && r.code === code)) {
                claimed.push({ 
                    id, 
                    title, 
                    description, 
                    code, 
                    claimedAt: now.toISOString(),
                    expiryDate: expiry.toISOString() 
                });
                localStorage.setItem('claimedRewards', JSON.stringify(claimed));
            }
            document.getElementById('modalCode').textContent = code;
            document.getElementById('rewardModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
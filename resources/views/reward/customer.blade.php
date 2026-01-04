<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Loyalty Rewards') }}
        </h2>
    </x-slot>

    <style>
        .stamp { 
            width: 44px; height: 44px; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: bold; border: 2px solid #e5e7eb; border-radius: 50%; 
            font-size: 14px;
        }
        .stamp.filled { 
            background-color: #ef4444; color: white; border-color: #ef4444; 
        }
        .reward-card { transition: all 0.2s; }
        .reward-card.unavailable { opacity: 0.7; }
    </style>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Your Loyalty Status</h1>
                <p class="text-gray-600">
                    Earn 1 stamp for every rental of <strong>10+ hours</strong>. Every <strong>3 stamps</strong> = 1 reward!
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-medium text-gray-800">Stamps Earned</h2>
                    <span class="text-lg font-bold text-red-600">5 Stamps</span>
                </div>
                
                <div class="flex flex-wrap gap-3 mb-6">
                    <div class="stamp filled">1</div>
                    <div class="stamp filled">2</div>
                    <div class="stamp filled">3</div>
                    <div class="stamp filled">4</div>
                    <div class="stamp filled">5</div>
                    <div class="stamp">6</div>
                    <div class="stamp">7</div>
                    <div class="stamp">8</div>
                    <div class="stamp">9</div>
                </div>

                <div class="bg-blue-50 rounded-lg p-4 flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <p class="text-sm text-gray-700">
                        You have <strong>5 stamps</strong>. You can claim <strong>1 reward</strong> now!
                    </p>
                </div>
            </div>

            <h2 class="text-xl font-semibold text-gray-800 mb-4">Available Rewards</h2>
            
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 mb-4 reward-card">
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full mb-2">
                            <i class="fas fa-car mr-1"></i> Car Rental Discount
                        </span>
                        <h3 class="font-bold text-gray-800">15% Off Your Next Car Rental</h3>
                        <p class="text-sm text-gray-600 mt-1">Apply this code during checkout.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 block mb-2">Cost: 3 stamps</span>
                        <button 
                            class="w-full md:w-auto px-6 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition"
                            onclick="claimReward(1, 'Car Rental Discount', '15% Off Your Next Car Rental', 'RENTAL-15P-8A3B')">
                            Claim Reward
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200 reward-card unavailable">
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mb-2">
                            <i class="fas fa-clock mr-1"></i> Extra Hours
                        </span>
                        <h3 class="font-bold text-gray-800">+1.5 Free Hours</h3>
                        <p class="text-sm text-gray-400 mt-1">RENTAL-1H30-XXXX</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 block mb-2">Cost: 6 stamps</span>
                        <button class="w-full md:w-auto px-6 py-2 bg-gray-300 text-gray-500 text-sm rounded-lg cursor-not-allowed" disabled>
                            Need 1 More Stamp
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="rewardModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 text-center shadow-2xl">
            <div class="text-green-500 text-5xl mb-4">
                <i class="fas fa-gift"></i>
            </div>
            <h3 class="font-bold text-xl text-gray-800 mb-2">Reward Claimed!</h3>
            <p class="text-gray-600 mb-6">Your discount code is:</p>
            <div class="font-mono font-bold text-lg bg-gray-100 p-3 rounded-lg border border-dashed border-gray-400 mb-6" id="modalCode"></div>
            
            <div class="space-y-3">
            <button 
                onclick="window.location.href='/rewards/claimed'" 
                class="w-full py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700">
                Done
            </button>
                <button onclick="document.getElementById('rewardModal').classList.add('hidden')" class="w-full py-2 text-gray-500 text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
function claimReward(id, title, description, code) {
    let claimed = JSON.parse(localStorage.getItem('claimedRewards') || '[]');
    
    // 1. Calculate the date (1 year from now)
    const now = new Date();
    const expiry = new Date();
    expiry.setFullYear(now.getFullYear() + 1); 

    const exists = claimed.some(r => r.id === id && r.code === code);
    
    if (!exists) {
        // 2. Add 'expiryDate' to the object
        claimed.push({ 
            id: id,
            title: title, 
            description: description, 
            code: code, 
            claimedAt: now.toISOString(),
            expiryDate: expiry.toISOString() // This is the missing piece!
        });
        localStorage.setItem('claimedRewards', JSON.stringify(claimed));
    }

    document.getElementById('modalCode').textContent = code;
    document.getElementById('rewardModal').classList.remove('hidden');
}
    </script>
    
</x-app-layout>
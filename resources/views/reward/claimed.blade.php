<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Claimed Rewards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('reward.customer') }}" class="text-red-600 inline-flex items-center text-sm font-semibold hover:underline mb-4">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Rewards Store
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900">My Reward Wallet</h1>
                <p class="text-gray-600 mt-1">Below are the discount codes you have successfully claimed.</p>
            </div>

            <div id="rewardsList" class="space-y-4">
                </div>

            <div id="noRewards" class="bg-white rounded-xl shadow-sm text-center py-16 border border-gray-200 hidden">
                <div class="text-gray-300 text-6xl mb-4">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Your wallet is empty</h3>
                <p class="text-gray-500 mt-1">You haven't claimed any rewards with your stamps yet.</p>
                <a href="{{ route('reward.customer') }}" class="mt-6 inline-block bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition shadow-lg">
                    Go Earn Stamps
                </a>
            </div>

            <div class="mt-10 text-center">
                <button onclick="clearRewards()" class="text-xs text-gray-400 hover:text-red-500 transition underline">
                    Reset My Claimed Rewards (Testing Only)
                </button>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        renderRewards();
    });

    function renderRewards() {
        const rewards = JSON.parse(localStorage.getItem('claimedRewards') || '[]');
        const listEl = document.getElementById('rewardsList');
        const noRewardsEl = document.getElementById('noRewards');

        // Toggle visibility if empty
        if (rewards.length === 0) {
            listEl.innerHTML = '';
            noRewardsEl.classList.remove('hidden');
        } else {
            noRewardsEl.classList.add('hidden');
            
            // Sort by newest first
            rewards.sort((a, b) => new Date(b.claimedAt) - new Date(a.claimedAt));

listEl.innerHTML = rewards.map(reward => `
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 transition hover:shadow-md border-l-4 border-l-green-500">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs font-bold rounded uppercase tracking-wider">
                            Active Reward
                        </span>
                        <span class="text-xs text-gray-400">
                            Claimed: ${new Date(reward.claimedAt).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}
                        </span>
                    </div>
                    <span class="text-[10px] font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded border">
                        REF: #00${reward.id || 'N/A'}
                    </span>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 mb-1">${reward.title}</h3>
                <p class="text-gray-600 text-sm mb-4">${reward.description}</p>
                
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">Promo Code</p>
                        <span class="font-mono text-lg font-extrabold text-red-600 tracking-widest">
                            ${reward.code}
                        </span>
                    </div>
                    <button onclick="copyToClipboard('${reward.code}', this)" class="text-sm text-blue-600 font-semibold hover:text-blue-800 flex items-center gap-1">
                        <i class="far fa-copy"></i> <span>Copy</span>
                    </button>
                </div>
                </div>
        </div>
    </div>
`).join('');
        }
    }

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const span = btn.querySelector('span');
            const originalText = span.textContent;
            span.textContent = 'Copied!';
            setTimeout(() => span.textContent = originalText, 2000);
        });
    }

    function clearRewards() {
        if(confirm('Are you sure you want to clear your reward history?')) {
            localStorage.removeItem('claimedRewards');
            renderRewards();
        }
    }
    </script>
</x-app-layout>
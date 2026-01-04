<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reward Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex gap-2 mb-8">
                <a href="/rewards" class="px-6 py-2 rounded-lg font-bold text-sm transition bg-white text-gray-500 border border-gray-100 hover:bg-gray-50">
                    Rewards Store
                </a>
                <a href="/rewards/my-claimed" class="px-6 py-2 rounded-lg font-bold text-sm transition bg-red-600 text-white shadow-md">
                    My Wallet
                </a>
            </div>

            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-900 tracking-tight">My Reward Wallet</h1>
                <p class="text-gray-500 mt-1">Below are the discount codes you have successfully claimed.</p>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Active Rewards</h2>

            <div id="rewardsList" class="space-y-6"></div>

            <div id="noRewards" class="bg-white rounded-[2rem] text-center py-20 border border-gray-200 hidden">
                <div class="text-gray-200 text-7xl mb-6"><i class="fas fa-ticket-alt"></i></div>
                <h3 class="text-xl font-bold text-gray-900">Your wallet is empty</h3>
                <a href="/rewards" class="mt-8 inline-block bg-red-600 text-white px-10 py-4 rounded-2xl font-bold shadow-xl shadow-red-100">Go Earn Stamps</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => { renderRewards(); });

        function renderRewards() {
            const rewards = JSON.parse(localStorage.getItem('claimedRewards') || '[]');
            const listEl = document.getElementById('rewardsList');
            const noRewardsEl = document.getElementById('noRewards');

            if (rewards.length === 0) {
                noRewardsEl.classList.remove('hidden');
            } else {
                noRewardsEl.classList.add('hidden');
                rewards.sort((a, b) => new Date(b.claimedAt) - new Date(a.claimedAt));

                listEl.innerHTML = rewards.map(reward => {
                    // Generate formatted dates
                    const claimedDate = new Date(reward.claimedAt).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
                    
                    // Logic to handle missing expiry data (as seen in your screenshot)
                    const expiryHtml = reward.expiryDate 
                        ? `<div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-2 flex items-center gap-2 mb-6">
                             <i class="far fa-calendar-alt text-orange-600"></i>
                             <p class="text-xs font-bold text-orange-800">Expires on ${new Date(reward.expiryDate).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}</p>
                           </div>`
                        : `<div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-2 flex items-center gap-2 mb-6">
                             <i class="fas fa-exclamation-circle text-orange-600"></i>
                             <p class="text-xs font-bold text-orange-800">Valid until: <span class="text-gray-400 font-normal italic">No expiry data found (Try re-claiming)</span></p>
                           </div>`;

                    return `
                    <div class="bg-white rounded-[2rem] p-8 border-2 border-transparent border-l-green-500 shadow-sm relative transition hover:shadow-md border-gray-100">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[11px] font-bold rounded-lg uppercase tracking-wide">Active Reward</span>
                                <span class="text-sm text-gray-400 font-medium">Claimed: ${claimedDate}</span>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded">REF: #${reward.id.toString().padStart(3, '0')}</span>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-1">${reward.title}</h3>
                        <p class="text-sm text-gray-500 mb-6 font-medium">Apply this code during checkout.</p>

                        ${expiryHtml}

                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Promo Code</span>
                                <span class="text-xl font-mono font-bold text-red-600 tracking-widest">${reward.code}</span>
                            </div>
                            <button onclick="copyToClipboard('${reward.code}', this)" class="flex items-center gap-2 text-blue-600 font-bold text-sm hover:text-blue-800 transition">
                                <i class="far fa-copy"></i> <span>Copy</span>
                            </button>
                        </div>
                    </div>
                `}).join('');
            }
        }

        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const span = btn.querySelector('span');
                const originalText = span.textContent;
                span.textContent = 'Copied!';
                btn.classList.replace('text-blue-600', 'text-green-600');
                setTimeout(() => {
                    span.textContent = originalText;
                    btn.classList.replace('text-green-600', 'text-blue-600');
                }, 2000);
            });
        }
    </script>
</x-app-layout>
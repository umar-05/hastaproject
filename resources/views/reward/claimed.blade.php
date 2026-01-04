<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reward Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigation Tabs --}}
            <div class="flex gap-2 mb-8">
                <a href="{{ route('reward.index') }}" class="px-6 py-2 rounded-lg font-bold text-sm transition bg-white text-gray-500 border border-gray-100 hover:bg-gray-50">
                    Rewards Store
                </a>
                <a href="{{ route('reward.claimed') }}" class="px-6 py-2 rounded-lg font-bold text-sm transition bg-red-600 text-white shadow-md">
                    My Wallet
                </a>
            </div>

            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-900 tracking-tight">My Reward Wallet</h1>
                <p class="text-gray-500 mt-1">Below are the discount codes you have successfully claimed.</p>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Active Rewards</h2>

            <div id="rewardsList" class="space-y-6">
                {{-- DYNAMIC DATABASE LOOP --}}
                @forelse($myRewards as $redemption)
                    <div class="bg-white rounded-[2rem] p-8 border-2 border-transparent border-l-green-500 shadow-sm relative transition hover:shadow-md border-gray-100">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[11px] font-bold rounded-lg uppercase tracking-wide">Active Reward</span>
                                <span class="text-sm text-gray-400 font-medium">
                                    Claimed: {{ \Carbon\Carbon::parse($redemption->redemptionDate)->format('d M Y') }}
                                </span>
                            </div>
                            <span class="text-[10px] font-mono font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded">
                                REF: #{{ str_pad($redemption->rewardID, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-1">
                            {{ $redemption->reward->rewardAmount }}{{ $redemption->reward->rewardType == 'Discount' ? '%' : '' }} Off 
                            {{ $redemption->reward->rewardType }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-6 font-medium">Apply this code during checkout.</p>

                        {{-- Expiry Logic --}}
                        <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-2 flex items-center gap-2 mb-6">
                            <i class="far fa-calendar-alt text-orange-600"></i>
                            <p class="text-xs font-bold text-orange-800">
                                Expires on {{ \Carbon\Carbon::parse($redemption->reward->expiryDate)->format('d M Y') }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Promo Code</span>
                                <span class="text-xl font-mono font-bold text-red-600 tracking-widest">
                                    {{ $redemption->reward->voucherCode }}
                                </span>
                            </div>
                            {{-- We keep the JS Copy function as it's a great User Experience feature --}}
                            <button onclick="copyToClipboard('{{ $redemption->reward->voucherCode }}', this)" class="flex items-center gap-2 text-blue-600 font-bold text-sm hover:text-blue-800 transition">
                                <i class="far fa-copy"></i> <span>Copy</span>
                            </button>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="bg-white rounded-[2rem] text-center py-20 border border-gray-200">
                        <div class="text-gray-200 text-7xl mb-6"><i class="fas fa-ticket-alt"></i></div>
                        <h3 class="text-xl font-bold text-gray-900">Your wallet is empty</h3>
                        <a href="{{ route('reward.index') }}" class="mt-8 inline-block bg-red-600 text-white px-10 py-4 rounded-2xl font-bold shadow-xl shadow-red-100">
                            Go Earn Stamps
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
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
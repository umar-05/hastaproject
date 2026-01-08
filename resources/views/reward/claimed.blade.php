<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fas fa-wallet text-red-600"></i>
            {{ __('My Reward Wallet') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>

    <div class="py-12 bg-[#F8FAFC] min-h-screen font-sans">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigation Pills --}}
            <div class="flex justify-center mb-10 animate-fade-in">
                <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 inline-flex">
                    <a href="{{ route('reward.index') }}" 
                       class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 {{ request()->routeIs('reward.index') ? 'bg-gradient-to-r from-gray-900 to-gray-800 text-white shadow-lg transform scale-105' : 'text-gray-500 hover:bg-gray-50' }}">
                        <i class="fas fa-store"></i> Rewards Store
                    </a>
                    <a href="{{ route('rewards.claimed') }}" 
                       class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 {{ request()->routeIs('rewards.claimed') ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg transform scale-105' : 'text-gray-500 hover:bg-gray-50' }}">
                        <i class="fas fa-wallet"></i> My Wallet
                    </a>
                </div>
            </div>

            {{-- Hero Section --}}
            <div class="animate-fade-in relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#B91C1C] via-[#DC2626] to-[#EF4444] shadow-2xl shadow-red-200 p-10 mb-12 text-white flex flex-col md:flex-row items-center justify-between">
                
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-2xl"></div>

                <div class="relative z-10 text-center md:text-left">
                    <div class="inline-block px-3 py-1 rounded-full bg-red-900/30 border border-red-400/30 text-xs font-bold uppercase tracking-widest mb-3 backdrop-blur-sm">
                        Secure Vault
                    </div>
                    <h1 class="text-4xl font-black tracking-tight mb-2">My Reward Wallet</h1>
                    <p class="text-red-100 font-medium max-w-lg leading-relaxed">
                        Here are your vouchers. Active ones can be used at checkout. Used ones show your booking history.
                    </p>
                </div>

                <div class="relative z-10 mt-6 md:mt-0 flex gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 text-center min-w-[120px]">
                        <span class="block text-4xl font-black">
                            {{ $myRewards->where('status', 'Active')->count() }}
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider opacity-80">Active</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/20 text-center min-w-[120px]">
                        <span class="block text-4xl font-black">
                            {{ $myRewards->where('status', 'Used')->count() }}
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider opacity-80">Used</span>
                    </div>
                </div>
            </div>

            {{-- Active Vouchers Section --}}
            <div class="animate-fade-in delay-100 mb-12">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800">Active Vouchers</h2>
                        <p class="text-slate-500 text-sm">Ready to use at checkout</p>
                    </div>
                    <div class="hidden md:block h-px bg-slate-200 flex-1 ml-6 relative top-[-10px]"></div>
                </div>

                <div class="space-y-6">
                    @forelse($myRewards->where('status', 'Active') as $redemption)
                        <div class="group bg-white rounded-[2.5rem] p-1 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-green-500/5 hover:-translate-y-1 transition-all duration-300">
                            <div class="p-8 flex flex-col md:flex-row gap-8 items-center">
                                
                                {{-- Icon & Value --}}
                                <div class="flex flex-col items-center justify-center min-w-[120px] text-center border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0 md:pr-8">
                                    <div class="h-16 w-16 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-2xl shadow-inner mb-3">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800 leading-none">
                                        {{ $redemption->reward->rewardAmount }}{{ $redemption->reward->rewardType == 'Discount' ? '%' : '' }}
                                    </h3>
                                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">OFF</span>
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 text-center md:text-left">
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-3">
                                        <span class="px-3 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                        <span class="text-xs text-slate-400 font-bold tracking-wide">
                                            <i class="far fa-clock mr-1"></i> Claimed: {{ \Carbon\Carbon::parse($redemption->redemptionDate)->format('d M Y') }}
                                        </span>
                                    </div>
                                    
                                    <h4 class="text-lg font-bold text-slate-800 mb-2">
                                        {{ $redemption->reward->rewardType }} Voucher
                                    </h4>
                                    
                                    <div class="text-sm text-slate-500 font-medium bg-orange-50 border border-orange-100 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg">
                                        <i class="far fa-calendar-times text-orange-500"></i>
                                        <span>Expires: {{ \Carbon\Carbon::parse($redemption->reward->expiryDate)->format('d M Y') }}</span>
                                    </div>
                                </div>

                                {{-- Code & Action --}}
                                <div class="w-full md:w-auto bg-slate-50 rounded-2xl p-5 border border-slate-100 flex flex-col items-center gap-3 min-w-[200px]">
                                    <div class="text-center">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest block mb-1">Promo Code</span>
                                        <span class="font-mono text-xl font-black text-slate-800 tracking-widest select-all">
                                            {{ $redemption->reward->voucherCode }}
                                        </span>
                                    </div>
                                    
                                    <button onclick="copyToClipboard('{{ $redemption->reward->voucherCode }}', this)" 
                                            class="w-full py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all active:scale-95 flex items-center justify-center gap-2">
                                        <i class="far fa-copy"></i> <span>Copy Code</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-[2.5rem] text-center py-16 border border-slate-100 shadow-sm">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-ticket-alt text-3xl text-slate-300"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">No Active Vouchers</h3>
                            <p class="text-slate-500 text-sm">Claim some from the Rewards Store!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Used Vouchers Section --}}
            <div class="animate-fade-in delay-200">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800">Used Vouchers</h2>
                        <p class="text-slate-500 text-sm">Your discount history</p>
                    </div>
                    <div class="hidden md:block h-px bg-slate-200 flex-1 ml-6 relative top-[-10px]"></div>
                </div>

                <div class="space-y-6">
                    @forelse($myRewards->where('status', 'Used') as $redemption)
                        <div class="bg-white rounded-[2.5rem] p-1 shadow-sm border border-slate-100 opacity-75">
                            <div class="p-8 flex flex-col md:flex-row gap-8 items-center">
                                
                                <div class="flex flex-col items-center justify-center min-w-[120px] text-center border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0 md:pr-8">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center text-2xl shadow-inner mb-3">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800 leading-none">
                                        {{ $redemption->reward->rewardAmount }}{{ $redemption->reward->rewardType == 'Discount' ? '%' : '' }}
                                    </h3>
                                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">OFF</span>
                                </div>

                                <div class="flex-1 text-center md:text-left">
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-3">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                            <i class="fas fa-check-double mr-1"></i> Used
                                        </span>
                                        <span class="text-xs text-slate-400 font-bold tracking-wide">
                                            <i class="far fa-clock mr-1"></i> Used: {{ $redemption->used_at ? \Carbon\Carbon::parse($redemption->used_at)->format('d M Y') : 'N/A' }}
                                        </span>
                                    </div>
                                    
                                    <h4 class="text-lg font-bold text-slate-800 mb-2">
                                        {{ $redemption->reward->rewardType }} Voucher
                                    </h4>
                                    
                                    @if($redemption->booking)
                                        <div class="text-sm text-slate-500 font-medium bg-blue-50 border border-blue-100 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-car text-blue-500"></i>
                                            <span>Booking: {{ $redemption->bookingID }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="w-full md:w-auto bg-slate-50 rounded-2xl p-5 border border-slate-100 flex flex-col items-center gap-3 min-w-[200px]">
                                    <div class="text-center">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest block mb-1">Code</span>
                                        <span class="font-mono text-xl font-black text-slate-400 tracking-widest line-through">
                                            {{ $redemption->reward->voucherCode }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-[2.5rem] text-center py-16 border border-slate-100 shadow-sm">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-3xl text-slate-300"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">No Used Vouchers</h3>
                            <p class="text-slate-500 text-sm">Your usage history will appear here</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-hasta-red text-white py-10 px-8 mt-16">
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center text-center">
            <div class="mb-4">
                <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Travel & Tours" class="h-12 w-auto object-contain">
            </div>
            <div class="space-y-2">
                <p class="text-sm font-medium">HASTA Travel & Tours</p>
                <p class="text-xs opacity-75">&copy; {{ date('Y') }} All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const span = btn.querySelector('span');
                const icon = btn.querySelector('i');
                const originalText = span.textContent;
                
                span.textContent = 'Copied!';
                icon.className = 'fas fa-check';
                btn.classList.add('bg-green-500', 'text-white', 'border-green-500');
                btn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200', 'hover:bg-slate-800');

                setTimeout(() => {
                    span.textContent = originalText;
                    icon.className = 'far fa-copy';
                    btn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
                    btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200', 'hover:bg-slate-800');
                }, 2000);
            });
        }
    </script>
</x-app-layout>
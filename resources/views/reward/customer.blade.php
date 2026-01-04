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
            
            {{-- 1. ALERT NOTIFICATIONS (Critical for Database redirects) --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif
            
            {{-- Navigation Tabs --}}
            <div class="flex gap-2 mb-8">
                <a href="{{ route('reward.index') }}" 
                   class="px-6 py-2 rounded-lg font-bold text-sm transition {{ request()->routeIs('reward.index') ? 'bg-red-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
                    Rewards Store
                </a>
                <a href="{{ route('reward.claimed') }}" 
                   class="px-6 py-2 rounded-lg font-bold text-sm transition {{ request()->routeIs('reward.claimed') ? 'bg-red-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
                    My Wallet
                </a>
            </div>

            {{-- Loyalty Status Card --}}
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
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Stamps Earned</h2>
                        <div class="flex flex-wrap gap-3">
                            @for ($i = 1; $i <= 9; $i++)
                                <div class="stamp {{ $i <= Auth::user()->stamps ? 'filled' : '' }}">{{ $i }}</div>
                            @endfor
                        </div>
                    </div>

                    <div class="text-right flex flex-col items-end justify-center">
                        <span class="text-4xl font-bold text-red-500">{{ Auth::user()->stamps }}</span>
                        <span class="text-red-500 font-bold text-sm uppercase tracking-wider">Stamps Total</span>
                    </div>
                </div>

                @if(Auth::user()->stamps >= 3)
                <div class="mt-8 bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-3">
                    <span class="text-xl">🎉</span>
                    <p class="text-sm font-medium text-blue-800">
                        You have <span class="font-bold">{{ Auth::user()->stamps }} stamps</span>. You can claim a reward now!
                    </p>
                </div>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Rewards</h2>
            
            <div class="space-y-4">
                @forelse($availableRewards as $reward)
                    <div class="bg-white rounded-3xl p-6 border border-gray-50 shadow-sm flex flex-col md:flex-row justify-between items-center transition hover:shadow-md">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-2">
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[11px] font-bold rounded-lg uppercase tracking-wide flex items-center gap-2">
                                    <i class="fas fa-tag"></i> {{ $reward->rewardType }}
                                </span>
                                <span class="text-xs text-gray-400 font-medium tracking-tight">Cost: {{ $reward->rewardPoints }} stamps</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $reward->rewardAmount }}{{ $reward->rewardType == 'Discount' ? '%' : '' }} Off Your Next Purchase
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 font-medium italic">Only {{ $reward->totalClaimable }} claims left!</p>
                        </div>

                        <div class="mt-4 md:mt-0">
                            @if(Auth::user()->stamps >= $reward->rewardPoints)
                                <form action="{{ route('rewards.claim') }}" method="POST" onsubmit="return confirm('Claim this reward for {{ $reward->rewardPoints }} stamps?')">
                                    @csrf
                                    <input type="hidden" name="rewardID" value="{{ $reward->rewardID }}">
                                    <button type="submit" 
                                            class="px-10 py-3 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 shadow-lg shadow-red-100 transition active:scale-95">
                                        Claim Reward
                                    </button>
                                </form>
                            @else
                                <div class="flex flex-col items-center gap-1">
                                    <button class="px-10 py-3 bg-gray-100 text-gray-400 rounded-2xl font-bold cursor-not-allowed" disabled>
                                        Locked
                                    </button>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Need {{ $reward->rewardPoints - Auth::user()->stamps }} More</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                        <i class="fas fa-ticket-alt text-4xl text-gray-200 mb-4"></i>
                        <p class="text-gray-500 font-medium">No rewards available at the moment. Check back later!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
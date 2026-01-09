<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fas fa-crown text-red-600"></i>
            {{ __('Loyalty Rewards') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES & ANIMATIONS --}}
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        
        /* CIRCLE ANIMATION STYLES */
        .stamp-wrapper {
            position: relative;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* The Background Circle (Gray) */
        .circle-bg {
            fill: none;
            stroke: rgba(255, 255, 255, 0.2);
            stroke-width: 3;
        }

        /* The Progress Circle (White) */
        .circle-progress {
            fill: none;
            stroke: #ffffff;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-dasharray: 126; /* Circumference of r=20 */
            stroke-dashoffset: 126; /* Start empty */
            transition: stroke-dashoffset 1s ease-out;
            transform: rotate(-90deg); /* Start from top */
            transform-origin: 50% 50%;
        }

        /* Filled State: Offset 0 means full circle */
        .stamp-wrapper.filled .circle-progress {
            stroke-dashoffset: 0;
        }

        /* Inner Content */
        .stamp-content {
            position: absolute;
            font-weight: 800;
            font-size: 16px;
            color: rgba(255,255,255,0.7);
            z-index: 10;
        }
        
        .stamp-wrapper.filled .stamp-content {
            color: #b91c1c; /* Red Checkmark */
            font-size: 20px;
        }

        /* White background circle behind checkmark for contrast */
        .stamp-bg-fill {
            position: absolute;
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transition-delay: 0.5s; /* Wait for circle to draw first */
        }

        .stamp-wrapper.filled .stamp-bg-fill {
            transform: scale(1);
        }
    </style>

    <div class="py-12 bg-[#F8FAFC] min-h-screen font-sans">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. NOTIFICATIONS --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     class="animate-fade-in mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-100 p-2 rounded-full"><i class="fas fa-check"></i></div>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="animate-fade-in mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm flex items-center gap-3">
                    <div class="bg-red-100 p-2 rounded-full"><i class="fas fa-times"></i></div>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif
            
            {{-- 2. NAVIGATION PILLS --}}
            <div class="flex justify-center mb-12 animate-fade-in">
                <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 inline-flex">
                    <a href="{{ route('reward.index') }}" 
                       class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 {{ request()->routeIs('reward.index') ? 'bg-gradient-to-r from-gray-900 to-gray-800 text-white shadow-lg transform scale-105' : 'text-gray-500 hover:bg-gray-50' }}">
                        <i class="fas fa-store"></i> Rewards Store
                    </a>
                    <a href="{{ route('rewards.claimed') }}" 
                       class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 {{ request()->routeIs('reward.claimed') ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg transform scale-105' : 'text-gray-500 hover:bg-gray-50' }}">
                        <i class="fas fa-wallet"></i> My Wallet
                    </a>
                </div>
            </div>

            {{-- 3. HERO SECTION: LOYALTY CARD --}}
            {{-- Using rewardPoints instead of stamps --}}
            <div x-data="{ page: {{ (int)Auth::user()->rewardPoints > 10 ? 2 : 1 }} }" 
                 class="animate-fade-in relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#B91C1C] via-[#DC2626] to-[#EF4444] shadow-2xl shadow-red-200 p-8 md:p-10 mb-20 text-white">
                
                {{-- Decorative Background --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row gap-8 items-center justify-between">
                    
                    {{-- Left: Text Info --}}
                    <div class="text-center md:text-left space-y-2 max-w-md">
                        <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight">
                            {{ Auth::user()->rewardPoints }} <span class="text-red-100 text-3xl block md:inline">Stamps Collected</span>
                        </h1>
                        <p class="text-red-100 font-medium text-lg leading-relaxed pt-2">
                            Earn <span class="text-white font-bold border-b-2 border-white/40">1 Stamp</span> for every day you rent! 
                            Collect stamps to unlock exclusive rewards below.
                        </p>
                    </div>

                    {{-- Right: Slider Stamp Grid --}}
                    <div class="flex-1 w-full max-w-xl flex items-center justify-center gap-6">
                        
                        {{-- LEFT ARROW --}}
                        <button @click="page = 1" 
                                :class="page === 1 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
                                class="p-4 rounded-full bg-white/20 hover:bg-white/40 text-white transition-all duration-300 backdrop-blur-md shadow-lg flex-shrink-0">
                            <i class="fas fa-chevron-left text-2xl"></i>
                        </button>

                        {{-- STAMPS CONTAINER --}}
                        <div class="flex-1 min-h-[140px] md:min-h-[120px] relative flex items-center justify-center">
                            
                            {{-- PAGE 1 (1-10) --}}
                            <div x-show="page === 1" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-x-8"
                                 x-transition:enter-end="opacity-100 transform translate-x-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 transform translate-x-0"
                                 x-transition:leave-end="opacity-0 transform -translate-x-8"
                                 class="absolute inset-0 flex flex-wrap justify-center gap-3 md:gap-4 content-center">
                                @for ($i = 1; $i <= 10; $i++)
                                    {{-- CORRECTED LOGIC: Use rewardPoints --}}
                                    @php $isFilled = (int)Auth::user()->rewardPoints >= $i; @endphp
                                    <div class="stamp-wrapper {{ $isFilled ? 'filled' : '' }}" title="Stamp {{ $i }}">
                                        {{-- SVG Circle --}}
                                        <svg width="60" height="60" viewBox="0 0 60 60" class="absolute inset-0">
                                            <circle cx="30" cy="30" r="20" class="circle-bg"></circle>
                                            <circle cx="30" cy="30" r="20" class="circle-progress"></circle>
                                        </svg>
                                        
                                        {{-- White Background (pops in later) --}}
                                        <div class="stamp-bg-fill"></div>

                                        {{-- Content (Checkmark or Number) --}}
                                        <div class="stamp-content">
                                            @if($isFilled) <i class="fas fa-check"></i> @else {{ $i }} @endif
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            {{-- PAGE 2 (11-20) --}}
                            <div x-show="page === 2" style="display: none;"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-x-8"
                                 x-transition:enter-end="opacity-100 transform translate-x-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 transform translate-x-0"
                                 x-transition:leave-end="opacity-0 transform -translate-x-8"
                                 class="absolute inset-0 flex flex-wrap justify-center gap-3 md:gap-4 content-center">
                                @for ($i = 11; $i <= 20; $i++)
                                    {{-- CORRECTED LOGIC: Use rewardPoints --}}
                                    @php $isFilled = (int)Auth::user()->rewardPoints >= $i; @endphp
                                    <div class="stamp-wrapper {{ $isFilled ? 'filled' : '' }}" title="Stamp {{ $i }}">
                                        <svg width="60" height="60" viewBox="0 0 60 60" class="absolute inset-0">
                                            <circle cx="30" cy="30" r="20" class="circle-bg"></circle>
                                            <circle cx="30" cy="30" r="20" class="circle-progress"></circle>
                                        </svg>
                                        <div class="stamp-bg-fill"></div>
                                        <div class="stamp-content">
                                            @if($isFilled) <i class="fas fa-check"></i> @else {{ $i }} @endif
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- RIGHT ARROW --}}
                        <button @click="page = 2" 
                                :class="page === 2 ? 'opacity-0 pointer-events-none' : 'opacity-100'"
                                class="p-4 rounded-full bg-white/20 hover:bg-white/40 text-white transition-all duration-300 backdrop-blur-md shadow-lg flex-shrink-0">
                            <i class="fas fa-chevron-right text-2xl"></i>
                        </button>
                    </div>

                </div>

                @if((int)Auth::user()->rewardPoints >= 3)
                <div class="mt-6 bg-white/10 border border-white/20 rounded-xl p-3 flex items-center justify-center gap-3 backdrop-blur-sm animate-pulse">
                    <i class="fas fa-gift text-yellow-300"></i>
                    <p class="text-sm font-bold">
                        Great job! You have enough stamps to claim a reward below.
                    </p>
                </div>
                @endif
            </div>

            {{-- 4. AVAILABLE REWARDS GRID --}}
            <div class="animate-fade-in delay-100">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800">Available Rewards</h2>
                        <p class="text-slate-500 text-sm">Exchange your hard-earned stamps for vouchers.</p>
                    </div>
                    <div class="hidden md:block h-px bg-slate-200 flex-1 ml-6 relative top-[-10px]"></div>
                </div>
                
                <div class="grid grid-cols-1 gap-5">
                    @forelse($availableRewards as $reward)
                        <div class="group relative bg-white rounded-3xl p-1 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-red-500/5 hover:-translate-y-1 transition-all duration-300">
                            <div class="flex flex-col md:flex-row items-center p-6 gap-6">
                                
                                {{-- Icon/Visual Side --}}
                                <div class="w-full md:w-auto flex justify-center">
                                    <div class="h-20 w-20 rounded-2xl {{ $reward->rewardType == 'Discount' ? 'bg-purple-50 text-purple-500' : 'bg-blue-50 text-blue-500' }} flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas {{ $reward->rewardType == 'Discount' ? 'fa-percent' : 'fa-clock' }}"></i>
                                    </div>
                                </div>

                                {{-- Content Side --}}
                                <div class="flex-1 text-center md:text-left">
                                    <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                                            {{ $reward->rewardType }}
                                        </span>
                                        @if($reward->totalClaimable < 5)
                                            <span class="px-2.5 py-0.5 rounded-md bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest animate-pulse">
                                                Hot
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-2xl font-bold text-slate-800 leading-tight mb-2">
                                        {{ $reward->rewardAmount }}{{ $reward->rewardType == 'Discount' ? '%' : '' }} Off Your Next Purchase
                                    </h3>
                                    
                                    <div class="flex items-center justify-center md:justify-start gap-4 text-sm text-slate-500 font-medium">
                                        <span class="flex items-center gap-1.5">
                                            <i class="fas fa-ticket-alt text-slate-300"></i> {{ $reward->totalClaimable }} left
                                        </span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="flex items-center gap-1.5 {{ (int)Auth::user()->rewardPoints >= (int)$reward->rewardPoints ? 'text-green-600 font-bold' : 'text-slate-400' }}">
                                            <i class="fas fa-stamp"></i> Cost: {{ $reward->rewardPoints }} Stamps
                                        </span>
                                    </div>
                                </div>

                                {{-- Action Side --}}
                                <div class="w-full md:w-auto">
                                    {{-- FIX: Using rewardPoints for comparison --}}
                                    @php 
                                        $canClaim = (int)Auth::user()->rewardPoints >= (int)$reward->rewardPoints;
                                        $hasStock = $reward->totalClaimable > 0;
                                    @endphp

                                    @if($canClaim && $hasStock)
                                        <form action="{{ route('rewards.claim') }}" method="POST" onsubmit="return confirm('Confirm claim for {{ $reward->rewardPoints }} stamps?')">
                                            @csrf
                                            <input type="hidden" name="rewardID" value="{{ $reward->rewardID }}">
                                            <button type="submit" 
                                                    class="w-full md:w-auto px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-[#B91C1C] transition-all duration-300 shadow-lg shadow-gray-200 active:scale-95 flex items-center justify-center gap-2 group-hover:shadow-red-200">
                                                <span>Claim</span>
                                                <i class="fas fa-arrow-right opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all"></i>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Locked State --}}
                                        <div class="flex flex-col items-center">
                                            <button disabled class="w-full md:w-auto px-8 py-4 bg-slate-50 text-slate-300 border border-slate-100 rounded-2xl font-bold cursor-not-allowed flex items-center gap-2">
                                                @if(!$hasStock)
                                                    <i class="fas fa-times-circle"></i> Out of Stock
                                                @else
                                                    <i class="fas fa-lock"></i> Locked
                                                @endif
                                            </button>
                                            @if($hasStock)
                                                <span class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                                    Need {{ (int)$reward->rewardPoints - (int)Auth::user()->rewardPoints }} More
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-box-open text-3xl text-slate-300"></i>
                            </div>
                            <h3 class="text-slate-800 font-bold text-lg">No Rewards Available</h3>
                            <p class="text-slate-400 text-sm">Check back later for new offers!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-hasta-red text-white py-10 px-8 mt-16">
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center text-center">
            
            <div class="mb-4">
                <img src="{{ asset('images/HASTALOGO.svg') }}" 
                     alt="HASTA Travel & Tours" 
                     class="h-12 w-auto object-contain">
            </div>

            <div class="space-y-2">
                <p class="text-sm font-medium">HASTA Travel & Tours</p>
                <p class="text-xs opacity-75">
                    &copy; {{ date('Y') }} All rights reserved.
                </p>
            </div>
            
        </div>
    </footer>

    @if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#B91C1C', '#EF4444', '#ffffff']
        });
    </script>
    @endif
</x-app-layout>
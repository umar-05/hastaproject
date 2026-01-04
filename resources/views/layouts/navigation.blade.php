<header class="w-full bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-8 py-6 flex items-center justify-between">
    
    {{-- ============================== --}}
    {{-- 1. LOGO SECTION                --}}
    {{-- ============================== --}}
    <div class="flex items-center">
        @auth('staff')
            {{-- Staff -> Staff Dashboard --}}
            <a href="{{ route('staff.dashboard') }}">
        @else
            {{-- Guest OR Customer -> Home/Root --}}
            <a href="{{ route('root') }}">
        @endauth
            <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Logo" class="h-10 w-auto">
        </a>
    </div>

    {{-- ============================== --}}
    {{-- 2. NAVIGATION LINKS            --}}
    {{-- ============================== --}}
    <nav class="hidden md:flex items-center space-x-8 font-medium">
        
        {{-- CASE A: STAFF INTERFACE --}}
        @auth('staff')
            <a href="{{ route('staff.dashboard') }}" class="text-gray-700 hover:text-hasta-red transition">
                Dashboard
            </a>

            <a href="{{ route('staff.add-staff') }}" class="bg-hasta-red hover:bg-red-700 text-white px-5 py-2 rounded-md font-bold transition shadow-md">
                Add Staff
            </a>

            <a href="{{ route('staff.pickup-return') }}" class="text-gray-700 hover:text-hasta-red transition">
                Pickup/Return
            </a>

            <a href="{{ route('vehicles.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Vehicles
            </a>

            <a href="{{ route('staff.report') }}" class="text-gray-700 hover:text-hasta-red transition">
                Report
            </a>
        
        {{-- CASE B: CUSTOMER & GUEST INTERFACE --}}
        {{-- They see the same options, but the HREF changes based on login status --}}
        @else
            
            {{-- 1. BOOK NOW BUTTON --}}
            {{-- If Customer: Go to Vehicles. If Guest: Go to Login --}}
            <a href="{{ Auth::guard('customer')->check() ? route('vehicles.index') : route('login') }}" 
               class="bg-hasta-red hover:bg-red-700 text-white px-5 py-2 rounded-md font-bold transition shadow-md">
                Book Now
            </a>

            {{-- 2. BOOKINGS LINK --}}
            {{-- If Customer: Go to Bookings. If Guest: Go to Login --}}
            <a href="{{ Auth::guard('customer')->check() ? route('bookings.index') : route('login') }}" 
               class="text-gray-700 hover:text-hasta-red transition">
                Bookings
            </a>

            {{-- 3. REWARDS LINK --}}
            {{-- If Customer: Go to Rewards. If Guest: Go to Login --}}
            <a href="{{ Auth::guard('customer')->check() ? route('rewards.index') : route('login') }}" 
               class="text-gray-700 hover:text-hasta-red transition">
                Rewards
            </a>

            {{-- 4. FAQ LINK --}}
            {{-- Accessible by everyone (Public Route) --}}
            <a href="{{ route('faq') }}" class="text-gray-700 hover:text-hasta-red transition">
                FAQ
            </a>
        @endauth
    </nav>

    {{-- ============================== --}}
    {{-- 3. RIGHT SIDE (PROFILE/LOGIN)  --}}
    {{-- ============================== --}}
    <div class="flex items-center space-x-6">
        
        {{-- IF LOGGED IN (Either Staff OR Customer) --}}
        @if(Auth::guard('staff')->check() || Auth::guard('customer')->check())
            
            {{-- Profile Link Logic --}}
            @if(Auth::guard('staff')->check())
                <a href="{{ route('staff.profile.edit') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition duration-150 ease-in-out">
            @else
                <a href="{{ route('profile.edit') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition duration-150 ease-in-out">
            @endif
            
                <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>
                    @if(Auth::guard('staff')->check())
                        {{ Auth::guard('staff')->user()->name }}
                    @else
                        {{ Auth::guard('customer')->user()->name }}
                    @endif
                </span>
            </a>

            {{-- Logout Button --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-hasta-red hover:bg-red-700 text-white font-bold py-2 px-8 rounded transition">
                    Logout
                </button>
            </form>

        {{-- IF GUEST (Not Logged In) --}}
        @else
            <a href="{{ route('login') }}" class="bg-hasta-red hover:bg-red-700 text-white font-bold py-2 px-8 rounded transition text-center">
                Login
            </a>
        @endif
    </div>
    </div>
</header>
<header class="w-full bg-white">
    <div class="max-w-7xl mx-auto px-8 py-6 flex items-center justify-between">
    
    <div class="flex items-center">
        @if(Auth::guard('staff')->check())
            <a href="{{ route('dashboard') }}">
        @elseif(Auth::guard('customer')->check())
            <a href="{{ route('home') }}">
        @else
            <a href="{{ route('home') }}">
        @endif
            <div class="border-2 border-hasta-red px-2 py-1 rounded-sm">
                <span class="text-2xl font-bold text-hasta-red">HASTA</span>
            </div>
        </a>
    </div>

    <nav class="hidden md:flex items-center space-x-8 font-medium">
        @auth('staff')
            <a href="{{ route('staff.add-staff') }}" class="bg-hasta-red hover:bg-red-700 text-white px-5 py-2 rounded-md font-bold transition shadow-md">
                Add Staff
            </a>

            <a href="{{ route('staff.pickup-return') }}" class="text-gray-700 hover:text-hasta-red transition">
                Pickup/Return
            </a>

            <a href="{{ route('vehicles.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Vehicles
            </a>

            <a href="#" class="text-gray-700 hover:text-hasta-red transition">
                Rewards
            </a>

            <a href="#" class="text-gray-700 hover:text-hasta-red transition">
                Report
            </a>
        
        @elseauth('customer')
            <a href="{{ route('vehicles.index') }}" class="bg-hasta-red hover:bg-red-700 text-white px-5 py-2 rounded-md font-bold transition shadow-md">
                Book Now
            </a>

            <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Bookings
            </a>

            <a href="{{ route('rewards.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Rewards
            </a>

            <a href="{{ route('faq') }}" class="text-gray-700 hover:text-hasta-red transition">
                FAQ
            </a>

        @else
            <a href="{{ route('vehicles.index') }}" class="bg-hasta-red hover:bg-red-700 text-white px-5 py-2 rounded-md font-bold transition shadow-md">
                Book Now
            </a>

            <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Bookings
            </a>

            <a href="{{ route('rewards.index') }}" class="text-gray-700 hover:text-hasta-red transition">
                Rewards
            </a>

            <a href="{{ route('faq') }}" class="text-gray-700 hover:text-hasta-red transition">
                FAQ
            </a>
        @endauth
    </nav>

    <div class="flex items-center space-x-6">
        @if(Auth::guard('staff')->check() || Auth::guard('customer')->check())
            
            <a href="{{ route('profile.edit') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition duration-150 ease-in-out">
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

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-hasta-red hover:bg-red-700 text-white font-bold py-2 px-8 rounded transition">
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="bg-hasta-red hover:bg-red-700 text-white font-bold py-2 px-8 rounded transition text-center">
                Login
            </a>
        @endif
    </div>
    </div>
</header>
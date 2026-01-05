<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HASTA Staff') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.4s ease-out forwards;
        }

        /* Sidebar Links Styling */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            cursor: pointer;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.05);
            color: white;
            padding-left: 30px;
        }

        .sidebar-link.active {
            background-color: rgba(255,255,255,0.15);
            color: white;
            border-left: 4px solid white;
            font-weight: 600;
            padding-left: 30px;
        }

        .sidebar-link svg { 
            width: 20px; 
            height: 20px; 
            margin-right: 15px; 
            opacity: 0.8;
        }

        .sidebar-link.active svg {
            opacity: 1;
        }

        /* Submenu Styling */
        .submenu {
            background-color: rgba(0, 0, 0, 0.15);
        }
        
        .submenu-link {
            display: flex;
            align-items: center;
            padding: 10px 25px 10px 55px;
            color: rgba(255,255,255,0.65);
            font-size: 13px;
            transition: all 0.2s;
            text-decoration: none;
            border-left: 4px solid transparent;
        }

        .submenu-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.08);
            padding-left: 60px;
        }
        
        /* Active state for submenu items */
        .submenu-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            font-weight: 600;
            border-left: 4px solid rgba(255,255,255,0.5);
        }

        .rotate-icon {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-row">
        
        <div class="w-72 bg-gradient-to-b from-[#b91c1c] to-[#7f1d1d] flex-shrink-0 flex flex-col text-white shadow-xl z-20">
            
            <div class="h-24 flex items-center justify-center border-b border-white/10 p-4">
                <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Logo" class="h-10 w-auto">
            </div>

            <div class="py-6 flex-1 overflow-y-auto">
                <p class="px-6 text-[10px] font-bold text-red-200 uppercase tracking-widest mb-4 opacity-70">Main Menu</p>
                
                <nav class="flex flex-col space-y-1">
                    <a href="{{ route('staff.dashboard') }}" class="sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('staff.bookingmanagement') }}" class="sidebar-link {{ request()->routeIs('staff.bookingmanagement') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Bookings
                    </a>

                    <a href="{{ route('staff.pickup-return') }}" class="sidebar-link {{ request()->routeIs('staff.pickup-return') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Inspection / Pickup
                    </a>

                    <a href="{{ route('staff.fleet.index') }}" class="sidebar-link {{ request()->routeIs('staff.fleet.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v3.28a1 1 0 00.684.948l19.184 6.362M15 12h2a2 2 0 002-2V8a2 2 0 00-2-2h-4.143"></path></svg>
                        Fleet
                    </a>

                    <a href="{{ route('staff.mission.index') }}" class="sidebar-link {{ request()->routeIs('staff.mission.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Missions
                    </a>

                    <a href="{{ route('staff.reward.index') }}" class="sidebar-link {{ request()->routeIs('staff.reward.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        Rewards
                    </a>

                    <div x-data="{ open: {{ request()->routeIs('staff.report.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="sidebar-link w-full justify-between focus:outline-none {{ request()->routeIs('staff.report.*') ? 'active' : '' }}">
                            <div class="flex items-center">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Reports
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div x-show="open" x-collapse class="submenu animate-fade-up" style="display: none;">
                            <a href="{{ route('staff.report.daily-income') }}" class="submenu-link {{ request()->routeIs('staff.report.daily-income') ? 'active' : '' }}">
                                Daily Income
                            </a>
                            <a href="#" class="submenu-link">
                                Monthly Income
                            </a>
                            <a href="#" class="submenu-link">
                                Income & Expenses
                            </a>
                            <a href="#" class="submenu-link">
                                Blacklist Record
                            </a>
                        </div>
                    </div>

                    <div class="mt-8 mb-4 px-6 border-t border-white/10 pt-4">
                        <p class="text-[10px] font-bold text-red-200 uppercase tracking-widest opacity-70">Administration</p>
                    </div>

                    <a href="{{ route('staff.customermanagement') }}" class="sidebar-link {{ request()->routeIs('staff.customermanagement') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Customer
                    </a>

                    <a href="{{ route('staff.add-staff') }}" class="sidebar-link {{ request()->routeIs('staff.add-staff') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Staff Record
                    </a>
                </nav>
            </div>
            
            <div class="p-6 border-t border-white/10 text-xs text-red-200/60 text-center">
                &copy; {{ date('Y') }} HASTA System
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 shadow-sm z-10">
                <div></div>

                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3 border-l border-gray-100 pl-6">
                         <div class="text-right hidden sm:block">
                            <div class="text-sm font-bold text-gray-800">{{ Auth::guard('staff')->user()->name ?? 'Staff' }}</div>
                            <div class="text-xs text-gray-500">Administrator</div>
                         </div>
                         <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-100 to-red-50 flex items-center justify-center text-red-600 font-bold border border-red-100">
                             {{ substr(Auth::guard('staff')->user()->name ?? 'S', 0, 1) }}
                         </div>
                         
                         <form method="POST" action="{{ route('logout') }}" class="ml-2" onsubmit="console.log('Logout form submitted')">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-600 p-2 rounded hover:bg-red-50 transition text-lg" title="Logout" onclick="console.log('Logout button clicked')" style="min-width: 40px; min-height: 40px; display: flex; align-items: center; justify-content: center;">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                         </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50/50 p-8">
                {{ $slot }}
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
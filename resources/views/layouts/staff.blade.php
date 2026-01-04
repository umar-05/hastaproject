<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>HASTA Staff Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.staff-navigation')

<<<<<<< Updated upstream
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
=======
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
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-row">
        
        <div class="w-72 bg-gradient-to-b from-[#b91c1c] to-[#7f1d1d] flex-shrink-0 flex flex-col text-white shadow-xl z-20">
            
            <div class="h-24 flex items-center justify-center border-b border-white/10 p-4">
                <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Logo" class="h-10 w-auto"> {{-- Filter makes the red logo white for contrast --}}
            </div>

            <div class="py-6 flex-1 overflow-y-auto">
                <p class="px-6 text-[10px] font-bold text-red-200 uppercase tracking-widest mb-4 opacity-70">Main Menu</p>
                
                <nav class="flex flex-col space-y-1">
                    <a href="{{ route('staff.dashboard') }}" class="sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('staff.bookings.index') }}" class="sidebar-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
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


                    <a href="{{ route('staff.report') }}" class="sidebar-link {{ request()->routeIs('staff.report') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Reporting
                    </a>

                    <div class="mt-8 mb-4 px-6 border-t border-white/10 pt-4">
                        <p class="text-[10px] font-bold text-red-200 uppercase tracking-widest opacity-70">Administration</p>
>>>>>>> Stashed changes
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HASTA Travel & Tours</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    <header class="py-6 px-8 flex items-center justify-between max-w-7xl mx-auto">
    <div class="flex items-center">
        <div class="border-2 border-hasta-red px-2 py-1 rounded-sm">
            <span class="text-2xl font-bold text-hasta-red">HASTA</span>
        </div>
    </div>

    <nav class="hidden md:flex items-center space-x-8 font-medium">
        <a href="{{ route('book-now') }}" class="bg-hasta-red hover:bg-red-700 text-white px-5 py-2 rounded-md font-bold transition shadow-md">
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
    </nav>

    <div class="flex items-center space-x-6">
        @auth
            <a href="{{ route('profile.edit') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-red-600 transition duration-150 ease-in-out">
                <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>{{ Auth::user()->name }}</span>
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
        @endauth
    </div>
</header>

    <main class="max-w-7xl mx-auto px-8">

        <section class="relative bg-hasta-red rounded-[40px] p-12 md:p-16 overflow-hidden mb-16 text-white">
            <div class="absolute top-0 right-0 w-1/2 h-full pointer-events-none opacity-30 md:opacity-100 mix-blend-overlay" style="background-image: url('{{ asset('images/herocar.png') }}');"></div>
            
            <div class="relative z-10 max-w-xl">
    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6 drop-shadow-lg">
        Experience the road like never before
    </h1>
    <p class="text-sm md:text-base opacity-90 mb-8 max-w-md drop-shadow-md">
        We believe your rental car should enhance your trip, not just be a part of it. Our fleet delivers a premium driving experience.
    </p>
    <button class="bg-hasta-yellow hover:bg-amber-500 text-black font-bold py-3 px-8 rounded-md transition shadow-lg">
        View all cars
    </button>
</div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center mb-20">
            <div class="flex flex-col items-center">
                <div class="mb-4">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Availability</h3>
                <p class="text-gray-600 text-sm max-w-xs">Browse our extensive fleet and book your ideal car for any date and time.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Comfort</h3>
                <p class="text-gray-600 text-sm max-w-xs">We are committed to providing a clean, safe, and comfortable experience.</p>
            </div>
             <div class="flex flex-col items-center">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Savings</h3>
                <p class="text-gray-600 text-sm max-w-xs">Travel smartly with our fuel-efficient and budget-friendly rental options.</p>
            </div>
        </section>

        <section class="mb-20">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-extrabold max-w-xs">Choose the car that suits you</h2>
                <a href="#" class="text-gray-900 font-bold flex items-center hover:text-hasta-red transition">
                    View All <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] p-6">
                    <img src=" {{ asset('/images/axia-2018.png') }}" alt="Perodua Axia" class="w-full h-40 object-contain mb-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="text-xl font-bold">Perodua Axia 2018</h4>
                            <p class="text-gray-500 text-sm">Hatchback</p>
                        </div>
                        <div class="text-right">
                            <span class="text-hasta-red text-xl font-bold">RM120</span>
                            <p class="text-gray-500 text-xs">per day</p>
                        </div>
                    </div>
                    <div class="flex space-x-4 text-gray-500 text-sm mb-6">
                        <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg> Automatic</span>
                        <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> RON 95</span>
                         <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg> Air Conditioner</span>
                    </div>
                    <button class="w-full bg-hasta-red hover:bg-hasta-redHover text-white font-bold py-3 rounded transition">
                        View Details
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] p-6">
                    <img src="{{ asset('/images/bezza-2018.png') }}" alt="Perodua Bezza" class="w-full h-40 object-contain mb-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="text-xl font-bold">Perodua Bezza 2018</h4>
                            <p class="text-gray-500 text-sm">Sedan</p>
                        </div>
                        <div class="text-right">
                            <span class="text-hasta-red text-xl font-bold">RM140</span>
                            <p class="text-gray-500 text-xs">per day</p>
                        </div>
                    </div>
                     <div class="flex space-x-4 text-gray-500 text-sm mb-6">
                        <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg> Automatic</span>
                        <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> RON 95</span>
                         <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg> Air Conditioner</span>
                    </div>
                    <button class="w-full bg-hasta-red hover:bg-hasta-redHover text-white font-bold py-3 rounded transition">
                        View Details
                    </button>
                </div>

                 <div class="bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.08)] p-6">
                    <img src="{{ asset('/images/myvi-2015.png') }}" alt="Perodua Myvi" class="w-full h-40 object-contain mb-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="text-xl font-bold">Perodua Myvi 2015</h4>
                            <p class="text-gray-500 text-sm">Sport</p>
                        </div>
                        <div class="text-right">
                            <span class="text-hasta-red text-xl font-bold">RM120</span>
                            <p class="text-gray-500 text-xs">per day</p>
                        </div>
                    </div>
                     <div class="flex space-x-4 text-gray-500 text-sm mb-6">
                        <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg> Automatic</span>
                        <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> RON 95</span>
                         <span class="flex items-center"><svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg> Air Conditioner</span>
                    </div>
                    <button class="w-full bg-hasta-red hover:bg-hasta-redHover text-white font-bold py-3 rounded transition">
                        View Details
                    </button>
                </div>
            </div>
        </section>

    </main>

    <footer class="bg-hasta-red text-white py-12 px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="border-2 border-white px-2 py-1 rounded-sm inline-block mb-8">
                    <span class="text-2xl font-bold">HASTA</span>
                </div>
                 <div class="flex items-start mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 mt-1 text-hasta-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <p class="text-sm leading-relaxed">Address<br>Student Mall UTM<br>Skudai, 81300, Johor Bahru</p>
                </div>
                 <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-hasta-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <p class="text-sm">Email<br>hastatravel@gmail.com</p>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-hasta-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    <p class="text-sm">Phone<br>011-1090 0700</p>
                </div>
            </div>

            <div>
                <h5 class="font-bold mb-6">Useful links</h5>
                <ul class="space-y-3 text-sm opacity-80">
                    <li><a href="#" class="hover:opacity-100">About us</a></li>
                    <li><a href="#" class="hover:opacity-100">Contact us</a></li>
                    <li><a href="#" class="hover:opacity-100">Gallery</a></li>
                    <li><a href="#" class="hover:opacity-100">Blog</a></li>
                    <li><a href="#" class="hover:opacity-100">F.A.Q</a></li>
                </ul>
            </div>

             <div>
                <h5 class="font-bold mb-6">Vehicles</h5>
                <ul class="space-y-3 text-sm opacity-80">
                    <li><a href="#" class="hover:opacity-100">Sedan</a></li>
                    <li><a href="#" class="hover:opacity-100">Hatchback</a></li>
                    <li><a href="#" class="hover:opacity-100">MPV</a></li>
                    <li><a href="#" class="hover:opacity-100">Minivan</a></li>
                    <li><a href="#" class="hover:opacity-100">SUV</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-red-800 flex space-x-6">
             <a href="#" class="bg-black rounded-full p-2"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
             <a href="#" class="bg-black rounded-full p-2"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.012 3.584-.07 4.85c-.148 3.252-1.667 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.012-3.584.07-4.85c.148-3.252 1.667-4.771 4.919-4.919 1.266-.058 1.645-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
        </div>
    </footer>

</body>
</html>
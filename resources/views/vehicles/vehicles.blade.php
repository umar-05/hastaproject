<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Vehicle Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .vehicle-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .vehicle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .filter-btn.active {
            background-color: #dc2626;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-red-600 text-white px-4 py-2 font-bold text-xl rounded">
                        HASTA
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-700 hover:text-red-600">Home</a>
                    <a href="#" class="text-red-600 font-semibold">Vehicles</a>
                    <a href="#" class="text-gray-700 hover:text-red-600">Details</a>
                    <a href="#" class="text-gray-700 hover:text-red-600">Loyalty</a>
                    <a href="#" class="text-gray-700 hover:text-red-600">Contact Us</a>
                </div>
                <a href="#" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Title -->
        <h1 class="text-4xl font-bold text-center mb-8">Select A Vehicle Group</h1>

        <!-- Filter Buttons -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button class="filter-btn active bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition" data-filter="all">
                All vehicles
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition" data-filter="Sedan">
                🚗 Sedan
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition" data-filter="Hatchback">
                🚙 Hatchback
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition" data-filter="MPV">
                🚐 MPV
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition" data-filter="SUV">
                🚙 SUV
            </button>
            <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition" data-filter="Minivan">
                🚐 Minivan
            </button>
        </div>

        <!-- Vehicle Grid -->
        <div id="vehicleGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Vehicle Card 1 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="Hatchback">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚗</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Perodua Axia 2018</h3>
                            <p class="text-gray-500 text-sm">Hatchback</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM120</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 2 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="Sedan">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚙</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Perodua Bezza 2018</h3>
                            <p class="text-gray-500 text-sm">Sedan</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM140</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 3 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="Hatchback">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚗</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Perodua Myvi 2015</h3>
                            <p class="text-gray-500 text-sm">Hatchback</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM120</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 4 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="Hatchback">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚗</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Perodua Myvi 2020</h3>
                            <p class="text-gray-500 text-sm">Hatchback</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM150</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 5 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="Hatchback">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚗</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Perodua Axia 2024</h3>
                            <p class="text-gray-500 text-sm">Hatchback</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM130</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 6 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="Sedan">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚙</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Proton Saga 2017</h3>
                            <p class="text-gray-500 text-sm">Sedan</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM120</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 7 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="MPV">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚐</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Perodua Alza 2019</h3>
                            <p class="text-gray-500 text-sm">MPV</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM200</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 8 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="SUV">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚙</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Perodua Aruz 2020</h3>
                            <p class="text-gray-500 text-sm">SUV</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM180</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Vehicle Card 9 -->
            <div class="vehicle-card bg-white rounded-lg shadow-md overflow-hidden" data-type="MPV">
                <div class="p-6">
                    <div class="bg-gray-100 rounded-lg h-48 flex items-center justify-center mb-4">
                        <div class="text-6xl">🚐</div>
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">Toyota Vellfire 2020</h3>
                            <p class="text-gray-500 text-sm">MPV</p>
                        </div>
                        <div class="text-right">
                            <p class="text-red-600 text-2xl font-bold">RM500</p>
                            <p class="text-gray-500 text-sm">per day</p>
                        </div>
                    </div>

                    <div class="flex justify-between text-xs text-gray-600 mb-4 flex-wrap gap-2">
                        <div class="flex items-center">
                            <span class="mr-1">⚙️</span>
                            <span>Automat</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">⛽</span>
                            <span>RON 95</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-1">❄️</span>
                            <span>Air Conditioner</span>
                        </div>
                    </div>

                    <button class="w-full bg-red-600 text-white py-3 rounded hover:bg-red-700 transition">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-red-600 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="bg-white text-red-600 px-4 py-2 font-bold text-xl inline-block mb-4 rounded">
                        HASTA
                    </div>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="hover:text-gray-200 text-2xl">📘</a>
                        <a href="#" class="hover:text-gray-200 text-2xl">📷</a>
                        <a href="#" class="hover:text-gray-200 text-2xl">✖️</a>
                        <a href="#" class="hover:text-gray-200 text-2xl">▶️</a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold mb-4 flex items-center">
                        <span class="text-2xl mr-2">📍</span> Address
                    </h4>
                    <p>Student Mall UTM</p>
                    <p>Skudai, 81300, Johor Bahru</p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Useful Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-gray-200">About us</a></li>
                        <li><a href="#" class="hover:text-gray-200">Contact us</a></li>
                        <li><a href="#" class="hover:text-gray-200">Gallery</a></li>
                        <li><a href="#" class="hover:text-gray-200">Blog</a></li>
                        <li><a href="#" class="hover:text-gray-200">F.A.Q</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Vehicles</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-gray-200">Sedan</a></li>
                        <li><a href="#" class="hover:text-gray-200">Hatchback</a></li>
                        <li><a href="#" class="hover:text-gray-200">MPV</a></li>
                        <li><a href="#" class="hover:text-gray-200">Minivan</a></li>
                        <li><a href="#" class="hover:text-gray-200">SUV</a></li>
                    </ul>
                    <div class="mt-4 space-y-2">
                        <p class="flex items-center">
                            <span class="text-xl mr-2">📧</span> hastatravel@gmail.com
                        </p>
                        <p class="flex items-center">
                            <span class="text-xl mr-2">📞</span> 011-1090 0100
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const vehicleCards = document.querySelectorAll('.vehicle-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;
                
                // Update active button
                filterButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');

                // Filter vehicles with fade effect
                vehicleCards.forEach(card => {
                    if (filter === 'all' || card.dataset.type === filter) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    </script>
</body>
</html>
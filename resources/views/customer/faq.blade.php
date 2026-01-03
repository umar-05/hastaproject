<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - HASTA</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- THEME SETUP --- */
        /* Note: We scope these styles so they don't break the Navbar */
        .faq-body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fdfbfb 0%, #fff0f0 100%);
            color: #333;
            min-height: 100vh;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        /* --- FAQ HEADER (Renamed to avoid conflict with Nav) --- */
        .faq-page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .brand-label {
            font-size: 14px;
            font-weight: 700;
            color: #bb1419;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            display: block;
        }

        .faq-page-header h1 {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .faq-page-header p {
            color: #666;
            font-size: 16px;
        }

        /* --- ACCORDION STYLES --- */
        .faq-item {
            background: #ffffff;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.02);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            box-shadow: 0 8px 25px rgba(187, 20, 25, 0.08);
            transform: translateY(-2px);
        }

        .faq-question {
            padding: 20px 25px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 16px;
            color: #333;
            transition: color 0.3s ease;
        }

        .faq-item.active .faq-question {
            color: #bb1419;
            background: #fffdfd;
        }

        .icon {
            width: 24px;
            height: 24px;
            transition: transform 0.3s ease;
            color: #999;
        }

        .faq-item.active .icon {
            transform: rotate(180deg);
            color: #bb1419;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0, 1, 0, 1);
            background: #fffdfd;
            padding: 0 25px;
            color: #555;
            font-size: 14px;
            line-height: 1.6;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            padding-bottom: 25px;
        }

        /* --- CONTACT SECTION --- */
        .contact-box {
            text-align: center;
            margin-top: 60px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04);
        }
        
        .contact-box h3 { font-size: 18px; margin-bottom: 10px; }
        .contact-box p { color: #666; font-size: 14px; margin-bottom: 20px; }
        
        .contact-link {
            color: #bb1419;
            font-weight: 600;
            text-decoration: none;
        }
        .contact-link:hover { text-decoration: underline; }

    </style>
</head>
<body class="faq-body">

    @include('layouts.navigation')

    <div class="faq-container">
        
        <div class="faq-page-header">
            <span class="brand-label">HASTA SUPPORT</span>
            <h1>Frequently Asked Questions</h1>
            <p>Everything you need to know about renting with HASTA.</p>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                1. How do I book a rental car?
                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer">
                Simply log in to your account, browse our "Vehicles" page, select the car you want, choose your dates, and proceed to payment. You will receive a booking confirmation instantly.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                2. What documents are required?
                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer">
                Since this is a student service, we primarily require your Matric Card for verification. A valid Driving License is also mandatory for the driver picking up the vehicle.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                3. Can I cancel my booking?
                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer">
                Yes, you can cancel your booking up to 24 hours before the scheduled pickup time for a full refund. Cancellations made less than 24 hours in advance may be subject to a fee.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                4. Is fuel included in the rental price?
                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer">
                We operate on a "Full-to-Full" policy. The car comes with a full tank, and we ask that you return it full. If returned empty, refueling charges will apply.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                5. What happens if I return the car late?
                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer">
                We offer a 30-minute grace period. After that, an hourly late fee will be charged to your account. Please contact us if you know you will be delayed.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                6. Do you offer student discounts?
                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer">
                Absolutely! HASTA is built for students. Our prices are already subsidized, and you can earn "Rewards Points" on every trip to redeem for future discounts.
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                7. How do I contact support?
                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer">
                You can reach our support team via the "Contact Us" page or email us directly at support@hasta.com. We are available 9 AM - 10 PM daily.
            </div>
        </div>

    </div>

    <script>
        const items = document.querySelectorAll('.faq-item');

        items.forEach(item => {
            item.querySelector('.faq-question').addEventListener('click', () => {
                // Close all others
                items.forEach(i => {
                    if (i !== item) i.classList.remove('active');
                });
                // Toggle current
                item.classList.toggle('active');
            });
        });
    </script>

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
                    <li><a href="{{ route('faq') }}" class="hover:opacity-100">F.A.Q</a></li>
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
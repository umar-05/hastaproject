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
</body>
</html>
{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA - Car Rental</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #d84545;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav-links a:first-child {
            color: #d84545;
        }

        .login-btn {
            background: #d84545;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #d84545 0%, #c73939 100%);
            padding: 80px 50px;
            margin: 30px 50px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            min-height: 400px;
        }

        .hero-content {
            max-width: 600px;
            color: white;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 16px;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .view-cars-btn {
            background: #ffc107;
            color: #333;
            padding: 15px 35px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }

        .hero-image {
            position: absolute;
            right: 100px;
            top: 50%;
            transform: translateY(-50%);
            width: 100px;
            height: 250px;
            background: rgba(0,0,0,0.2);
            border-radius: 20px;
        }

        /* Features Section */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 50px;
            padding: 80px 100px;
            text-align: center;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .feature h3 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .feature p {
            color: #666;
            line-height: 1.6;
        }

        /* Cars Section */
        .cars-section {
            padding: 50px 100px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 36px;
        }

        .view-all {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .cars-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .car-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            background: white;
        }

        .car-image {
            width: 100%;
            height: 200px;
            background: #f5f5f5;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
        }

        .car-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }

        .car-name {
            font-size: 18px;
            font-weight: bold;
        }

        .car-type {
            color: #666;
            font-size: 14px;
        }

        .car-price {
            text-align: right;
        }

        .price-amount {
            color: #d84545;
            font-weight: bold;
            font-size: 20px;
        }

        .price-period {
            color: #999;
            font-size: 12px;
        }

        .car-specs {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            font-size: 14px;
            color: #666;
        }

        .spec {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .view-details-btn {
            width: 100%;
            background: #d84545;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Footer */
        footer {
            background: #d84545;
            color: white;
            padding: 50px 100px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 50px;
            margin-bottom: 30px;
        }

        .footer-section h3 {
            margin-bottom: 20px;
        }

        .footer-section p {
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.9;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        @media (max-width: 992px) {
    /* TABLET/SMALL DESKTOP ADJUSTMENTS */
    nav {
        padding: 20px 30px; /* Reduce padding */
    }

    .hero {
        margin: 20px 30px;
        padding: 50px 30px;
    }

    .hero h1 {
        font-size: 40px; /* Slightly smaller heading */
    }

    .hero-image {
        /* Move the car image slightly closer and shrink it a bit */
        right: 50px;
        width: 150px;
        height: 200px;
    }

    .features, .cars-section {
        padding: 50px 50px; /* Reduce horizontal padding */
    }
    
    .features {
        /* Switch to 2 columns on tablet */
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }
    
    .cars-grid {
        /* Switch to 2 columns on tablet */
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    footer {
        padding: 40px 50px;
    }

    .footer-content {
        /* Switch to 2 columns on tablet */
        grid-template-columns: 2fr 1fr;
    }
}


@media (max-width: 768px) {
    /* MOBILE PHONE ADJUSTMENTS */
    
    /* Navigation */
    nav {
        flex-direction: column; /* Stack logo and links vertically */
        padding: 15px 20px;
    }
    
    .nav-links {
        display: none; /* Hide main links on small screens (can be toggled with JS) */
        /* For this example, we hide them to focus on core structure */
    }

    .logo {
        margin-bottom: 10px;
    }
    
    .login-btn {
        margin-top: 10px;
        width: 100%; /* Full width button */
        text-align: center;
    }

    /* Hero Section */
    .hero {
        margin: 20px;
        padding: 40px 20px;
        min-height: auto;
    }

    .hero-content {
        max-width: 100%;
    }

    .hero h1 {
        font-size: 32px;
    }

    .hero-image {
        /* Remove the absolute positioned image or move it below the content */
        display: none; 
        /* Since the Hero is using flex/positioning, the simplest fix is often to hide the non-essential image */
    }

    /* Features Section */
    .features {
        padding: 40px 20px;
        /* Stack features vertically on mobile */
        grid-template-columns: 1fr; 
    }

    /* Cars Section */
    .cars-section {
        padding: 40px 20px;
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        margin-bottom: 30px;
    }

    .section-header h2 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .cars-grid {
        /* Switch to a single column for car cards on mobile */
        grid-template-columns: 1fr; 
    }

    /* Footer */
    footer {
        padding: 30px 20px;
    }

    .footer-content {
        /* Stack all footer sections vertically */
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .footer-bottom p {
        /* Ensure bottom text is readable */
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">HASTA</div>
        <ul class="nav-links">
            <li><a href="/">Home</a></li>
            <li><a href="/vehicles">Vehicles</a></li>
            <li><a href="/details">Details</a></li>
            <li><a href="/loyalty">Loyalty</a></li>
            <li><a href="/contact">Contact Us</a></li>
        </ul>
        <a href="/login" class="login-btn">Login</a>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Experience the road like never before</h1>
            <p>We believe your rental car should enhance your trip, not just be a part of it. Our fleet delivers a premium driving experience that combines style, comfort, and reliability.</p>
            <a href="#" class="view-cars-btn">View all cars</a>
        </div>
        <div class="hero-image"><img src="{{ asset('images/bgcar.png') }}" alt="Car image"></div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="feature">
            <div class="feature-icon">📍</div>
            <h3>Availability</h3>
            <p>Browse our extensive fleet and book your ideal car for any date and time.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">🚗</div>
            <h3>Comfort</h3>
            <p>We are committed to providing a clean, safe, and comfortable experience.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">💰</div>
            <h3>Savings</h3>
            <p>Travel smartly with our fuel-efficient and budget-friendly rental options.</p>
        </div>
    </section>

    <!-- Cars Section -->
    <section class="cars-section">
        <div class="section-header">
            <h2>Choose the car that suits you</h2>
            <a href="#" class="view-all">View All →</a>
        </div>
        
        <div class="cars-grid">
            <!-- Car 1 -->
            <div class="car-card">
                <div class="car-image">🚗</div>
                <div class="car-header">
                    <div>
                        <div class="car-name">Perodua Axia 2018</div>
                        <div class="car-type">Hatchback</div>
                    </div>
                    <div class="car-price">
                        <div class="price-amount">RM120</div>
                        <div class="price-period">per day</div>
                    </div>
                </div>
                <div class="car-specs">
                    <span class="spec">⚙️ Automat</span>
                    <span class="spec">⛽ RON 95</span>
                    <span class="spec">❄️ Air Conditioner</span>
                </div>
                <button class="view-details-btn">View Details</button>
            </div>

            <!-- Car 2 -->
            <div class="car-card">
                <div class="car-image">🚙</div>
                <div class="car-header">
                    <div>
                        <div class="car-name">Perodua Bezza 2018</div>
                        <div class="car-type">Sedan</div>
                    </div>
                    <div class="car-price">
                        <div class="price-amount">RM140</div>
                        <div class="price-period">per day</div>
                    </div>
                </div>
                <div class="car-specs">
                    <span class="spec">⚙️ Automat</span>
                    <span class="spec">⛽ RON 95</span>
                    <span class="spec">❄️ Air Conditioner</span>
                </div>
                <button class="view-details-btn">View Details</button>
            </div>

            <!-- Car 3 -->
            <div class="car-card">
                <div class="car-image">🚗</div>
                <div class="car-header">
                    <div>
                        <div class="car-name">Perodua Myvi 2015</div>
                        <div class="car-type">Sport</div>
                    </div>
                    <div class="car-price">
                        <div class="price-amount">RM120</div>
                        <div class="price-period">per day</div>
                    </div>
                </div>
                <div class="car-specs">
                    <span class="spec">⚙️ Automat</span>
                    <span class="spec">⛽ PB 95</span>
                    <span class="spec">❄️ Air Conditioner</span>
                </div>
                <button class="view-details-btn">View Details</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="logo">HASTA</h3>
                <div class="social-icons">
                    <div class="social-icon">f</div>
                    <div class="social-icon">📷</div>
                    <div class="social-icon">𝕏</div>
                    <div class="social-icon">▶️</div>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Useful links</h3>
                <ul class="footer-links">
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">Gallery</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">F.A.Q</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Vehicles</h3>
                <ul class="footer-links">
                    <li><a href="#">Sedan</a></li>
                    <li><a href="#">Hatchback</a></li>
                    <li><a href="#">MPV</a></li>
                    <li><a href="#">Minivan</a></li>
                    <li><a href="#">SUV</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>📍 Address: Student Mall UTM, Skudai, 81300, Johor Bahru</p>
            <p>✉️ Email: hastatravel@gmail.com</p>
            <p>📞 Phone: 011-1000 0700</p>
        </div>
    </footer>
</body>
</html>
{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HASTA</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Poppins, sans-serif;
            background: #bb1419ff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: inline-block;
            padding: 10px 20px;
            border: 3px solid #d84545;
            border-radius: 5px;
        }

        .logo-text {
            font-size: 48px;
            font-weight: bold;
            color: #d84545;
            letter-spacing: 2px;
        }

        /* Login Card */
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 20px 50px;
            width: 30%;
            max-width: 600px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 10px;
        }

        .welcome-text h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-text p {
            color: #999;
            font-size: 14px;
        }

        /* Form */
        .login-form {
            margin-top: 40px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 20px;
        }

        .form-input {
            width: 100%;
            padding: 18px 20px 18px 30px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #d84545;
        }

        .form-input::placeholder {
            color: #ccc;
        }

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            color: #333;
            font-size: 14px;
            cursor: pointer;
        }

        .forgot-password {
            color: #4a90e2;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Sign In Button */
        .signin-btn {
            width: 100%;
            padding: 18px;
            background: #d84545;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .signin-btn:hover {
            background: #c73939;
        }

        /* Sign Up Link */
        .signup-link {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 15px;
        }

        .signup-link a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background: #d84545;
            color: white;
            padding: 40px 100px;
            margin-top: auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 50px;
            margin-bottom: 20px;
        }

        .footer-section h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .footer-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            opacity: 0.9;
            font-size: 14px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.9;
            font-size: 14px;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        .social-icons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .social-icon {
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 16px;
        }

        .social-icon:hover {
            background: rgba(255,255,255,0.3);
        }

        .footer-logo {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 30px;
            }

            footer {
                padding: 30px 20px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

            .logo-container img {
                display:block;
                height: auto;
                width: 60%;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 30px;
            }
    </style>
</head>
<body>
    <!-- Main Content -->

    <div class="main-content">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Logo">
            </div>

            <!-- Welcome Text -->
            <div class="welcome-text">
                <h1>Welcome</h1>
                <p>Sign in to your account to continue</p>
            </div>

            <!-- Login Form -->
            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>
                    @error('email')
                        <span style="color: #d84545; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="**********"
                            required
                        >
                    </div>
                    @error('password')
                        <span style="color: #d84545; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Forgot Password -->
                <div class="form-options">
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                </div>

                <!-- Sign In Button -->
                <button type="submit" class="signin-btn">Sign In</button>

                <!-- Sign Up Link -->
                <div class="signup-link">
                    Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#b91c1c' // Matches your HASTA red color
        });
    </script>
@endif

</body>
</html>
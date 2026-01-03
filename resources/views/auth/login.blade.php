{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - HASTA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- RESET & BASE --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* CHANGED: Clean white background with a very subtle red tint for theme harmony */
            background: linear-gradient(135deg, #ffffff 0%, #fff0f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        /* --- CARD DESIGN --- */
        .login-wrapper {
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            /* Slightly stronger shadow for better contrast on white background */
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 40px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
        }

        /* Accent bar remains to keep branding strong */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: #bb1419;
        }

        /* --- LOGO & HEADER --- */
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-container img {
            height: 60px; 
            width: auto;
        }

        .header-text {
            text-align: center;
            margin-bottom: 35px;
        }

        .header-text h1 {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .header-text p {
            font-size: 14px;
            color: #666;
            font-weight: 400;
        }

        /* --- FORM STYLING --- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            color: #333;
            /* Slightly darker input background for contrast */
            background-color: #f3f4f6;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-input:focus {
            outline: none;
            background-color: #fff;
            border-color: #bb1419;
            box-shadow: 0 0 0 4px rgba(187, 20, 25, 0.1);
        }

        .form-input::placeholder {
            color: #aaa;
        }

        /* --- ALERTS --- */
        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
        }

        .global-error {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            color: #b91c1c;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 13px;
        }

        /* --- ACTIONS --- */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }

        .forgot-link {
            font-size: 13px;
            color: #666;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #bb1419;
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, #bb1419, #d84545);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(187, 20, 25, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(187, 20, 25, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* --- FOOTER LINKS --- */
        .signup-text {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .signup-text a {
            color: #bb1419;
            font-weight: 600;
            text-decoration: none;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        /* Mobile Adjustments */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="logo-container">
                <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Logo">
            </div>

            @if ($errors->any())
                <div class="global-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="header-text">
                <h1>Welcome Back</h1>
                <p>Please enter your details to sign in</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            required 
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="submit-btn">
                    Sign In
                </button>

                <div class="signup-text">
                    Don't have an account? 
                    <a href="{{ route('register') }}">Create account</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('status') }}"
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Welcome!',
                    text: "{{ session('success') }}", 
                    icon: 'success',
                    confirmButtonText: 'Let\'s Go',
                    confirmButtonColor: '#bb1419',
                    background: '#fff',
                    iconColor: '#bb1419'
                });
            });
        </script>
    @endif

</body>
</html>
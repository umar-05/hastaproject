{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - HASTA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- RESET & BASE --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #fff0f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        /* --- CARD DESIGN --- */
        .auth-wrapper {
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 40px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 6px;
            background: #bb1419;
        }

        /* --- LOGO & HEADER --- */
        .logo-container { text-align: center; margin-bottom: 25px; }
        .logo-container img { height: 60px; width: auto; }

        .header-text { text-align: center; margin-bottom: 25px; }
        .header-text h1 { font-size: 24px; font-weight: 700; color: #1a1a1a; margin-bottom: 10px; }
        .header-text p { font-size: 14px; color: #666; line-height: 1.5; }

        /* --- FORM STYLING --- */
        .form-group { margin-bottom: 25px; }

        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            color: #555; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;
        }

        .input-wrapper { position: relative; }

        /* Icon styling */
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            width: 20px;
            height: 20px;
            pointer-events: none;
        }

        .form-input {
            width: 100%; padding: 14px 16px 14px 45px; /* Extra padding-left for icon */
            font-size: 15px; color: #333;
            background-color: #f3f4f6; border: 2px solid #e5e7eb;
            border-radius: 10px; transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-input:focus {
            outline: none; background-color: #fff;
            border-color: #bb1419; box-shadow: 0 0 0 4px rgba(187, 20, 25, 0.1);
        }

        /* --- BUTTONS --- */
        .submit-btn {
            width: 100%; padding: 16px;
            background: linear-gradient(to right, #bb1419, #d84545);
            color: white; border: none; border-radius: 12px;
            
            /* CHANGED: Smaller font size */
            font-size: 13px; 
            font-weight: 600; 
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(187, 20, 25, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(187, 20, 25, 0.4); }
        .submit-btn:active { transform: translateY(0); }

        .back-link {
            display: block; text-align: center; margin-top: 25px;
            font-size: 14px; color: #666; text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #bb1419; text-decoration: underline; }

        /* --- ALERTS --- */
        .error-message { color: #dc2626; font-size: 12px; margin-top: 6px; font-weight: 500; }
        
        .status-alert {
            background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
            padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;
            text-align: center;
        }

    </style>
</head>
<body>

    <div class="auth-wrapper">
        <div class="auth-card">
            
            <div class="logo-container">
                <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Logo">
            </div>

            <div class="header-text">
                <h1>Forgot Password?</h1>
                <p>No problem. Just let us know your email address and we will email you a password reset link.</p>
            </div>

            @if (session('status'))
                <div class="status-alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        
                        <input type="email" id="email" name="email" class="form-input" 
                               value="{{ old('email') }}" required autofocus placeholder="example@gmail.com">
                    </div>
                    @error('email') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="submit-btn">
                    Email Password Reset Link
                </button>

                <a href="{{ route('login') }}" class="back-link">
                    &larr; Back to Login
                </a>

            </form>
        </div>
    </div>

</body>
</html>
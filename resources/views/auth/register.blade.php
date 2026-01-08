{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - HASTA</title>
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
            padding: 40px 0;
        }

        .register-wrapper { width: 100%; padding: 20px; display: flex; justify-content: center; }

        .register-card {
            background: #ffffff;
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 40px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 6px;
            background: #bb1419;
        }

        /* --- LOGO & HEADER --- */
        .logo-container { text-align: center; margin-bottom: 20px; }
        .logo-container img { height: 50px; width: auto; }

        .header-text { text-align: center; margin-bottom: 20px; }
        .header-text h1 { font-size: 24px; font-weight: 700; color: #1a1a1a; margin-bottom: 5px; }
        .header-text p { font-size: 14px; color: #666; }

        /* --- TAB SWITCHER --- */
        .tab-container {
            display: flex;
            background: #f3f4f6;
            padding: 5px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: #666;
        }

        .tab-btn.active {
            background: #ffffff;
            color: #bb1419;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        /* --- OCR SECTION --- */
        .ocr-wrapper { margin-bottom: 25px; }
        #icImage { display: none; }
        .ocr-upload-card {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: #ffffff; border: 2px dashed #e0e0e0; border-radius: 16px;
            padding: 20px; cursor: pointer; transition: all 0.3s ease; text-align: center;
        }
        .ocr-upload-card:hover { border-color: #bb1419; background: #fff9f9; transform: translateY(-2px); }
        .icon-circle { width: 45px; height: 45px; background: #fff0f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .upload-icon { width: 24px; height: 24px; color: #bb1419; }
        .ocr-title { font-size: 14px; font-weight: 700; color: #333; }
        .ocr-title span { color: #bb1419; }
        .ocr-subtitle { font-size: 11px; color: #888; }

        /* --- FORM STYLING --- */
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-input {
            width: 100%; padding: 12px 16px; font-size: 14px; color: #333;
            background-color: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 10px; transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        .form-input:focus { outline: none; background-color: #fff; border-color: #bb1419; box-shadow: 0 0 0 4px rgba(187, 20, 25, 0.1); }
        .error-message { color: #dc2626; font-size: 11px; margin-top: 4px; font-weight: 500; }
        .hidden { display: none; }

        /* --- BUTTONS --- */
        .submit-btn {
            width: 100%; padding: 16px; margin-top: 10px;
            background: linear-gradient(to right, #bb1419, #d84545);
            color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer;
            box-shadow: 0 4px 15px rgba(187, 20, 25, 0.3); transition: transform 0.2s;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(187, 20, 25, 0.4); }
        .login-text { text-align: center; margin-top: 20px; font-size: 13px; color: #666; }
        .login-text a { color: #bb1419; font-weight: 600; text-decoration: none; }
        
        .status-box { padding: 10px; border-radius: 8px; margin-top: 8px; font-size: 12px; }
        .status-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .status-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    </style>
</head>
<body>

    <div class="register-wrapper">
        <div class="register-card">
            
            <div class="logo-container">
                <img src="{{ asset('images/HASTALOGO.svg') }}" alt="HASTA Logo">
            </div>

            <div class="header-text">
                <h1>Create Account</h1>
                <p>Join the UTM Community today!</p>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" id="btn-student" onclick="setRole('student')">UTM Student</button>
                <button type="button" class="tab-btn" id="btn-staff" onclick="setRole('staff')">UTM Staff</button>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                
                <input type="hidden" name="role" id="roleInput" value="student">

                <div id="ocrSection" class="ocr-wrapper">
                    <input type="file" id="icImage" accept="image/*">
                    <label for="icImage" class="ocr-upload-card">
                        <div class="icon-circle">
                            <svg class="upload-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ocr-title">Quick Fill with <span>Matric Card</span></div>
                        <div class="ocr-subtitle">Tap to scan or upload image</div>
                    </label>
                    <div id="ocrStatus" class="hidden"></div>
                </div>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Enter full name" value="{{ old('name') }}" required autofocus>
                    @error('name') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="matricNum" id="idLabel">Matric Number</label>
                    <input type="text" id="matricNum" name="matricNum" class="form-input" placeholder="AXXCSXXXX" value="{{ old('matricNum') }}" required>
                    @error('matricNum') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" id="facultyGroup">
                    <label for="faculty">Faculty</label>
                    <input type="text" id="faculty" name="faculty" class="form-input" placeholder="Computing" value="{{ old('faculty') }}">
                    @error('faculty') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="student@graduate.utm.my" value="{{ old('email') }}" required>
                    @error('email') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required autocomplete="new-password">
                    @error('password') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="submit-btn">Sign Up</button>

                <div class="login-text">
                    Already have an account? <a href="{{ route('login') }}">Sign In</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    /** ROLE SWITCHING LOGIC **/
    function setRole(role) {
        const roleInput = document.getElementById('roleInput');
        const idLabel = document.getElementById('idLabel');
        const idInput = document.getElementById('matricNum');
        const facultyGroup = document.getElementById('facultyGroup');
        const ocrSection = document.getElementById('ocrSection');
        const emailInput = document.getElementById('email');
        
        // Tab styling
        document.getElementById('btn-student').classList.toggle('active', role === 'student');
        document.getElementById('btn-staff').classList.toggle('active', role === 'staff');

        roleInput.value = role;

        if (role === 'student') {
            idLabel.innerText = 'Matric Number';
            idInput.placeholder = 'AXXCSXXXX';
            facultyGroup.classList.remove('hidden');
            ocrSection.classList.remove('hidden');
            emailInput.placeholder = 'student@graduate.utm.my';
        } else {
            idLabel.innerText = 'Staff ID';
            idInput.placeholder = 'UTMXXXX';
            facultyGroup.classList.add('hidden');
            ocrSection.classList.add('hidden');
            emailInput.placeholder = 'staff@utm.my';
        }
    }

    /** OCR LOGIC FOR STUDENTS **/
    const fileInput = document.getElementById('icImage');
    if (fileInput) {
        fileInput.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            const statusDiv = document.getElementById('ocrStatus');
            statusDiv.classList.remove('hidden');
            statusDiv.innerHTML = '<span style="color:#2563eb; font-weight:600;">🔄 Scanning card... please wait...</span>';

            try {
                const formData = new FormData();
                formData.append('image', file);

                const response = await fetch('/ocr/process', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    if (result.data.name) document.getElementById('name').value = result.data.name;
                    if (result.data.matricNum) document.getElementById('matricNum').value = result.data.matricNum;
                    if (result.data.faculty) document.getElementById('faculty').value = result.data.faculty;

                    statusDiv.innerHTML = `<div class="status-box status-success"><strong>✅ Success!</strong> Card scanned and filled.</div>`;
                } else {
                    statusDiv.innerHTML = `<div class="status-box status-error">❌ ${result.message || 'Scan failed'}</div>`;
                }
            } catch (error) {
                statusDiv.innerHTML = `<div class="status-box status-error">❌ Connection error.</div>`;
            }

            setTimeout(() => { statusDiv.classList.add('hidden'); }, 5000);
            this.value = '';
        });
    }
    </script>
</body>
</html>
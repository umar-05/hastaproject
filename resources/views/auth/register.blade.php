<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<x-guest-layout>
    <div class="flex flex-col items-center">
        <div class="mb-6 text-center">
            <div class="flex justify-center mb-2">
                <span class="px-4 py-1 text-2xl font-bold text-red-600 border-2 border-red-600 rounded-sm">HASTA</span>
            </div>
            <h2 class="text-xl font-semibold text-gray-900">Enter Your Personal Details</h2>
            <p class="text-sm text-gray-500">Sign Up to Continue</p>
        </div>

        <div class="w-full sm:max-w-md bg-white p-8 shadow-lg rounded-3xl border border-gray-100">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- OCR Section -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📸 Quick Fill with Matric Card Scan (Optional)
                    </label>
                    <input type="file" id="icImage" accept="image/*" 
                        class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600 file:text-white
                        hover:file:bg-blue-700
                        file:cursor-pointer">
                    <p class="mt-2 text-xs text-gray-500">Upload your matric card to auto-fill username</p>
                    
                    <!-- Progress indicator -->
                    <div id="ocrStatus" class="mt-2 text-sm hidden"></div>
                </div>

                <!-- Name Field -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name" type="text" name="name" placeholder="Muhammad Ahmad Bin Abdullah" :value="old('name')" required autofocus 
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 py-2.5">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Matric Number Field -->
                <div class="mb-4">
                    <label for="matric_number" class="block text-sm font-medium text-gray-700 mb-1">Matric Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <input id="matric_number" type="text" name="matric_number" placeholder="A24CS0144" :value="old('matric_number')" required
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 py-2.5">
                    </div>
                    <x-input-error :messages="$errors->get('matric_number')" class="mt-2" />
                </div>

                <!-- Faculty Field -->
                <div class="mb-6">
                    <label for="faculty" class="block text-sm font-medium text-gray-700 mb-1">Faculty</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <input id="faculty" type="text" name="faculty" placeholder="Computing" :value="old('faculty')" required
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 py-2.5">
                    </div>
                    <x-input-error :messages="$errors->get('faculty')" class="mt-2" />
                </div>

                <div class="mt-4">
    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
    <div class="relative mt-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
            </svg>
        </div>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Email Address">
    </div>
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div class="mt-4">
    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
    <div class="relative mt-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Password">
    </div>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="mt-4">
    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
    <div class="relative mt-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <input id="password_confirmation" type="password" name="password_confirmation" required class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Confirm Password">
    </div>
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>

                <div class="flex items-center justify-center mb-6">
                    <span class="text-sm text-gray-600">Already Have an Account? </span>
                    <a href="{{ route('login') }}" class="ml-1 text-sm font-semibold text-blue-500 hover:text-blue-700">
                        Sign In
                    </a>
                </div>

                <button type="submit" class="w-full bg-red-700 text-white font-bold py-3 px-4 rounded-md hover:bg-red-800 transition duration-150 ease-in-out shadow-lg">
                    Sign Up
                </button>
            </form>
        </div>
    </div>

    <script>
    console.log('Script loaded!');

    const fileInput = document.getElementById('icImage');

    if (fileInput) {
        fileInput.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Please upload an image file');
                this.value = '';
                return;
            }

            const statusDiv = document.getElementById('ocrStatus');
            const nameInput = document.getElementById('name');
            const matricInput = document.getElementById('matric_number');
            const facultyInput = document.getElementById('faculty');

            statusDiv.classList.remove('hidden');
            statusDiv.innerHTML = '<span class="text-blue-600">🔄 Processing matric card...</span>';

            try {
                const formData = new FormData();
                formData.append('image', file);

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }

                const response = await fetch('/ocr/process', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const result = await response.json();
                console.log('OCR Result:', result);

                if (result.success) {
                    // Update Name field
                    if (result.data.name) {
                        nameInput.value = result.data.name;
                    } else {
                        nameInput.value = '';
                    }
                    nameInput.dispatchEvent(new Event('input', { bubbles: true }));

                    // Update Matric Number field
                    if (result.data.matricNum) {
                        matricInput.value = result.data.matricNum;
                    } else {
                        matricInput.value = '';
                    }
                    matricInput.dispatchEvent(new Event('input', { bubbles: true }));

                    // Update Faculty field
                    if (result.data.faculty) {
                        facultyInput.value = result.data.faculty;
                    } else {
                        facultyInput.value = '';
                    }
                    facultyInput.dispatchEvent(new Event('input', { bubbles: true }));

                    // Build success/warning message
                    const detected = [];
                    const missing = [];
                    
                    if (result.data.name) detected.push('Name ✓');
                    else missing.push('Name ✗');
                    
                    if (result.data.matricNum) detected.push('Matric ✓');
                    else missing.push('Matric ✗');
                    
                    if (result.data.faculty) detected.push('Faculty ✓');
                    else missing.push('Faculty ✗');

                    // Show appropriate message
                    if (detected.length === 3) {
                        let message = `<span class="text-green-600 font-semibold">✅ All fields detected!</span>`;
                        message += '<div class="mt-2 text-xs text-gray-700 bg-white p-2 rounded border border-green-200">';
                        if (result.data.name) message += `<div><strong>Name:</strong> ${result.data.name}</div>`;
                        if (result.data.matricNum) message += `<div><strong>Matric:</strong> ${result.data.matricNum}</div>`;
                        if (result.data.faculty) message += `<div><strong>Faculty:</strong> ${result.data.faculty}</div>`;
                        message += '<div class="mt-1 text-gray-500 italic">Please verify the information is correct</div>';
                        message += '</div>';
                        statusDiv.innerHTML = message;
                    } else if (detected.length > 0) {
                        let message = `<span class="text-yellow-600 font-semibold">⚠️ Partial detection: ${detected.join(', ')}</span>`;
                        message += '<div class="mt-2 text-xs text-gray-700 bg-white p-2 rounded border border-yellow-200">';
                        
                        if (result.data.name) message += `<div class="text-green-600"><strong>✓ Name:</strong> ${result.data.name}</div>`;
                        if (result.data.matricNum) message += `<div class="text-green-600"><strong>✓ Matric:</strong> ${result.data.matricNum}</div>`;
                        if (result.data.faculty) message += `<div class="text-green-600"><strong>✓ Faculty:</strong> ${result.data.faculty}</div>`;
                        
                        if (!result.data.name) message += `<div class="text-red-600"><strong>✗ Name:</strong> Not detected</div>`;
                        if (!result.data.matricNum) message += `<div class="text-red-600"><strong>✗ Matric:</strong> Not detected</div>`;
                        if (!result.data.faculty) message += `<div class="text-red-600"><strong>✗ Faculty:</strong> Not detected</div>`;
                        
                        message += '<div class="mt-1 text-gray-500 italic">Please fill in the missing fields manually</div>';
                        message += '</div>';
                        statusDiv.innerHTML = message;
                    } else {
                        statusDiv.innerHTML = '<span class="text-red-600 font-semibold">❌ No data detected. Please fill all fields manually.</span>';
                    }
                } else {
                    statusDiv.innerHTML = `<span class="text-red-600">❌ ${result.message || 'Processing failed'}</span>`;
                }

                setTimeout(() => {
                    statusDiv.classList.add('hidden');
                }, 12000);

            } catch (error) {
                console.error('OCR Error:', error);
                
                let errorMessage = error.message;
                if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Cannot connect to server. Make sure Laravel is running.';
                }
                
                statusDiv.innerHTML = `<span class="text-red-600">❌ ${errorMessage}</span>`;
                
                setTimeout(() => {
                    statusDiv.classList.add('hidden');
                }, 5000);
            }

            this.value = '';
        });
    } else {
        console.error('File input not found!');
    }
    </script>
</x-guest-layout>
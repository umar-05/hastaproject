<x-staff-layout>
    <div class="max-w-4xl mx-auto">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Add New Staff</h2>
                <p class="text-gray-500 text-sm mt-1">Register a new administrator or staff member. Staff ID will be generated automatically.</p>
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8 md:p-10">

                <div class="flex items-center mb-8 pb-6 border-b border-gray-100">
                    <div class="h-12 w-12 rounded-full bg-red-50 flex items-center justify-center text-[#bb1419] mr-4 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Account Details</h3>
                        <p class="text-sm text-gray-500">Please fill in the information below to create a staff account.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('staff.store') }}">       
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Ahmad Ali"
                                    class="pl-11 block w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 text-gray-700 py-3 transition duration-200 placeholder-gray-400 font-medium">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="position" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Staff Role</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <select id="position" name="position" required 
                                    class="pl-11 block w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 text-gray-700 py-3 transition duration-200 font-medium 
appearance-none">
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Manager">Manager</option>   
                                    <option value="Technician">Technician</option>
                                    <option value="Finance">Finance</option>   
                                    <option value="IT Officer">IT Officer</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>

                        <div class="col-span-1 md:col-span-2">
    <label for="email_username" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Corporate Email</label>
    <div class="relative flex items-center">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
            </svg>
        </div>

        <input id="email_username" type="text" name="email_username" value="{{ old('email_username') }}" required placeholder="username"
            class="pl-11 block w-full bg-gray-50 border border-gray-200 border-r-0 rounded-l-xl focus:bg-white focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 text-gray-700 py-3 transition duration-200 placeholder-gray-400 font-medium z-0">

        <span class="inline-flex items-center px-4 py-3 rounded-r-xl border border-l-0 border-gray-200 bg-gray-100 text-gray-500 font-bold text-sm tracking-wide">
            @hasta.com
        </span>
    </div>
    <p class="text-xs text-gray-400 mt-1 ml-1">The domain is locked to internal staff email addresses only.</p>

    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                                    class="pl-11 block w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#bb1419] focus:ring-2 
focus:ring-[#bb1419]/20 text-gray-700 py-3 transition duration-200 placeholder-gray-400 font-medium">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••"
                                    class="pl-11 block w-full bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 text-gray-700 py-3 transition duration-200 placeholder-gray-400 font-medium">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-6 pt-10 mt-6 border-t border-gray-100">
                        <button type="submit" class="bg-[#bb1419] hover:bg-red-800 text-white font-bold py-3 px-10 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 ease-in-out">
                            Create Account
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @if (session('status'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('status') }}",
                    icon: 'success',
                    confirmButtonText: 'Add Another Staff',
                    confirmButtonColor: '#bb1419',
                    showCancelButton: true,
                    cancelButtonText: 'Go to Dashboard',
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {        
                        window.location.href = "{{ route('staff.dashboard') }}";
                    }
                });
            });
        </script>
    @endif
</x-staff-layout>
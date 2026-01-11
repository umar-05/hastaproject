<section 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 300)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500 transform"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="bg-white"
>
    <header class="mb-6 border-b border-gray-100 pb-5">
        <div class="flex items-center mb-2">
            <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center text-[#bb1419] mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">
                {{ __('Change Password') }}
            </h2>
        </div>
        <p class="mt-1 text-sm text-gray-500 pl-[3.25rem]">
            {{ __("Ensure your account is using a long, random password to stay secure.") }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('Current Password') }}
            </label>
            <x-text-input id="current_password" name="current_password" type="password" 
                class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 text-base focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('New Password') }}
            </label>
            <x-text-input id="password" name="password" type="password" 
                class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 text-base focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">
                {{ __('Confirm Password') }}
            </label>
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" 
                class="block w-full bg-white border border-gray-300 rounded-lg py-3 px-4 text-gray-700 text-base focus:border-[#bb1419] focus:ring-2 focus:ring-[#bb1419]/20 transition duration-200" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-6 pt-6">
            <button type="submit" class="bg-[#bb1419] hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transform transition hover:-translate-y-0.5 duration-200 text-base">
                {{ __('UPDATE') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
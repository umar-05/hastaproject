<section 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 300)"
    x-show="show"
    x-transition:enter="transition ease-out duration-700 transform"
    x-transition:enter-start="opacity-0 translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="bg-[#f3f4f6] p-8 rounded-[24px] shadow-[12px_12px_24px_#d1d5db,-12px_-12px_24px_#ffffff]"
>
    <header class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
            {{ __('Change Password') }}
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            {{ __("Ensure your account is using a long, random password to stay secure.") }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="space-y-6 max-w-full">
            <div class="relative">
                <x-input-label for="current_password" :value="__('Current Password')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                <x-text-input id="current_password" name="current_password" type="password" 
                    class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="relative">
                <x-input-label for="password" :value="__('New Password')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                <x-text-input id="password" name="password" type="password" 
                    class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="relative">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs uppercase tracking-wider text-gray-500 font-bold mb-2 ml-1" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" 
                    class="block w-full bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20 transition-all duration-300" 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-6">
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-bold">{{ __('Saved') }}</p>
            @endif

            <button type="submit" class="bg-[#bb1419] text-white font-bold py-4 px-12 rounded-xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] hover:shadow-[inset_4px_4px_8px_#8a0f12,inset_-4px_-4px_8px_#ec191f] active:scale-95 transition-all duration-300 transform">
                {{ __('UPDATE PASSWORD') }}
            </button>
        </div>
    </form>
</section>
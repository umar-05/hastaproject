<section 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 500)"
    x-show="show"
    x-transition:enter="transition ease-out duration-700 transform"
    x-transition:enter-start="opacity-0 translate-y-10"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="space-y-6"
>
    <header class="mb-4">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            {{ __('Account Deletion') }}
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            {{ __("Once your account is deleted, all of its resources and data will be permanently deleted.") }}
        </p>
    </header>

    <div class="bg-[#f3f4f6] rounded-[24px] p-8 shadow-[12px_12px_24px_#d1d5db,-12px_-12px_24px_#ffffff] border-l-4 border-[#bb1419]">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Delete Account</h3>
                <p class="mt-1 text-sm text-gray-600 max-w-xl">
                    {{ __('Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>
            </div>

            <button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="bg-[#bb1419] text-white font-bold py-4 px-12 rounded-xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] hover:shadow-[inset_4px_4px_8px_#8a0f12,inset_-4px_-4px_8px_#ec191f] active:scale-95 transition-all duration-300 transform"
            >
                {{ __('DELETE ACCOUNT') }}
            </button>
        </div>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-[#f3f4f6]">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 mb-6">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-3/4 bg-[#f3f4f6] border-none rounded-xl py-4 px-5 text-gray-700 shadow-[inset_4px_4px_8px_#d1d5db,inset_-4px_-4px_8px_#ffffff] focus:ring-2 focus:ring-[#bb1419]/20"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <x-secondary-button x-on:click="$dispatch('close')" class="py-3 px-6 rounded-xl border-none shadow-[5px_5px_10px_#d1d5db,-5px_-5px_10px_#ffffff] bg-[#f3f4f6] text-gray-700 hover:text-gray-900">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <button type="submit" class="bg-[#bb1419] text-white font-bold py-3 px-8 rounded-xl shadow-[6px_6px_12px_#d1d5db,-6px_-6px_12px_#ffffff] hover:shadow-[inset_4px_4px_8px_#8a0f12,inset_-4px_-4px_8px_#ec191f] active:scale-95 transition-all duration-300">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
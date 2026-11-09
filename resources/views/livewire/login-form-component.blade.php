<div class="space-y-4">
    {{-- Informational & Error Banners --}}
    @if ($infoMessage)
        <div class="bg-gray-50 p-3 border-black border-l-2 text-gray-700 text-xs leading-relaxed">
            {{ $infoMessage }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="bg-red-50 p-3 border-red-600 border-l-2 text-red-700 text-xs leading-relaxed">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit.prevent="login" class="space-y-4">
            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang("Email")</label>
                <div class="relative">
                    <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input
                        type="email"
                        wire:model="loginEmail"
                        placeholder="@lang('Enter your email address')"
                        required
                        class="py-2.5 pr-3 pl-9 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                    />
                </div>
                @error('loginEmail') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Password')</label>
                <div class="relative">
                    <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input
                        type="{{ $showPassword ? 'text' : 'password' }}"
                        wire:model="loginPassword"
                        placeholder="@lang('Enter your password')"
                        required
                        class="py-2.5 pr-9 pl-9 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                    />
                    <button
                        type="button"
                        wire:click="togglePasswordVisibility"
                        class="right-0 absolute inset-y-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                    >
                        @if ($showPassword)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        @endif
                    </button>
                </div>
                @error('loginPassword') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center pt-1 text-xs">
                <button
                    type="button"
                    wire:click="$dispatch('open-auth-mode', { mode: 'forgot_password' })"
                    class="text-gray-500 hover:text-black underline transition-colors"
                >
                    @lang('Forgot Password?')
                </button>
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-4 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
            >
                <span wire:loading.remove wire:target="login">@lang('MASUK')</span>
                <span wire:loading wire:target="login">@lang('MEMPROSES...')</span>
            </button>
    </form>
</div>

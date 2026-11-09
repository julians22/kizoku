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

    <div class="flex justify-between items-center mb-4 pb-3 border-gray-200 border-b">
        <h4 class="font-semibold text-gray-900 text-xs uppercase tracking-[0.2em]">@lang('RESET PASSWORD')</h4>
        <button
            type="button"
            wire:click="$dispatch('open-auth-mode', { mode: 'login' })"
            class="text-gray-400 hover:text-black text-xs"
        >
            &times; @lang('Cancel')
        </button>
    </div>

    @if ($forgotStep === 'email')
        <form wire:submit.prevent="requestForgotOtp" class="space-y-4">
            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Email Registered')</label>
                <input
                    type="email"
                    wire:model="forgotEmail"
                    placeholder="nama@email.com"
                    required
                    class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                />
                @error('forgotEmail') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <p class="text-gray-500 text-base leading-normal">
                @lang('We’ll send a verification code (OTP) to your email before you complete your profile.')
            </p>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-4 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
            >
                <span wire:loading.remove wire:target="requestForgotOtp">@lang('SEND RESET OTP')</span>
                <span wire:loading wire:target="requestForgotOtp">@lang('SENDING...')</span>
            </button>
        </form>

    @elseif ($forgotStep === 'reset')
        <form wire:submit.prevent="submitResetPassword" class="space-y-3">
            <div class="bg-gray-50 p-2.5 border border-gray-200 rounded text-xs">
                <span class="text-gray-500">Email:</span>
                <span class="ml-1 font-semibold text-gray-900">{{ $forgotEmail }}</span>
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('OTP Code (6 Digits)')</label>
                <input
                    type="text"
                    wire:model="forgotOtp"
                    maxlength="6"
                    pattern="\d{6}"
                    placeholder="••••••"
                    required
                    class="py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full font-bold text-lg text-center tracking-[0.4em] transition-colors"
                />
                @error('forgotOtp') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider"></label>
                <input
                    type="password"
                    wire:model="forgotPassword"
                    placeholder="{{ __('placeholder.password.min_char', ['min' => 8]) }}"
                    required
                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                />
                @error('forgotPassword') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Repeat New Password')</label>
                <input
                    type="password"
                    wire:model="forgotPasswordConfirmation"
                    placeholder="Konfirmasi kata sandi baru"
                    required
                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                />
                @error('forgotPasswordConfirmation') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-3 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
            >
                <span wire:loading.remove wire:target="submitResetPassword">@lang('SIMPAN KATA SANDI BARU')</span>
                <span wire:loading wire:target="submitResetPassword">@lang('MEMPROSES...')</span>
            </button>
        </form>
    @endif
</div>

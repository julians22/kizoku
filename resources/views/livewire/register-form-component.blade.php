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

    {{-- Step 1: Input Email --}}
    @if ($registerStep === 'email')
        <form wire:submit.prevent="requestRegisterOtp" class="space-y-4">
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
                        wire:model="registerEmail"
                        placeholder="@lang('Enter your email address')"
                        required
                        class="py-2.5 pr-3 pl-9 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                    />
                </div>
                @error('registerEmail') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <p class="text-gray-500 text-base leading-normal">
                @lang('We’ll send a verification code (OTP) to your email before you complete your profile.')
            </p>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-4 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
            >
                <span wire:loading.remove wire:target="requestRegisterOtp">@lang('REQUEST REGISTRATION OTP')</span>
                <span wire:loading wire:target="requestRegisterOtp">@lang('SENDING...')</span>
            </button>
        </form>

    {{-- Step 2: Complete Profile & OTP --}}
    @elseif ($registerStep === 'details')
        <form wire:submit.prevent="submitRegister" class="space-y-3">
            <div class="bg-gray-50 p-2.5 border border-gray-200 rounded text-xs">
                <span class="text-gray-500">Email:</span>
                <span class="ml-1 font-semibold text-gray-900">{{ $registerEmail }}</span>
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">
                    @lang('OTP Code (6 Digits)')
                </label>
                <input
                    type="text"
                    wire:model="registerOtp"
                    maxlength="6"
                    pattern="\d{6}"
                    placeholder="••••••"
                    required
                    class="py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full font-bold text-lg text-center tracking-[0.4em] transition-colors"
                />
                @error('registerOtp') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Full Name')</label>
                <input
                    type="text"
                    wire:model="registerFullName"
                    placeholder="@lang('Full name as shown on your ID')"
                    required
                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                />
                @error('registerFullName') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <div class="gap-2 grid grid-cols-2">
                <div>
                    <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('CONTACT NUMBER')</label>
                    <input
                        type="tel"
                        wire:model="registerPhone"
                        placeholder="08123456789"
                        class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                    />
                    @error('registerPhone') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang("Gender")</label>
                    <select
                        wire:model="registerGender"
                        class="bg-white px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                    >
                        <option value="male">@lang("Male")</option>
                        <option value="female">@lang("Female")</option>
                    </select>
                    @error('registerGender') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Password (Min. 8 Characters)')</label>
                <input
                    type="password"
                    wire:model="registerPassword"
                    placeholder="@lang('Enter your password')"
                    required
                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                />
                @error('registerPassword') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Confirm Password')</label>
                <input
                    type="password"
                    wire:model="registerPasswordConfirmation"
                    placeholder="{{ __('passwords.repeat') }}"
                    required
                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                />
                @error('registerPasswordConfirmation') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-3 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
            >
                <span wire:loading.remove wire:target="submitRegister">@lang('COMPLETE REGISTRATION')</span>
                <span wire:loading wire:target="submitRegister">@lang('PROCESSING...')</span>
            </button>

            <div class="pt-2 text-center">
                <button
                    type="button"
                    wire:click="$set('registerStep', 'email')"
                    class="text-gray-500 hover:text-black text-xs underline"
                >
                    &larr; @lang('Change Email')
                </button>
            </div>
        </form>
    @endif
</div>

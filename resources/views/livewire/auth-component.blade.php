<div class="w-full">
    {{-- PROFILE VIEW (LOGGED IN) --}}
    @if ($mode === 'profile' && Auth::check())
        @php
            $currentUser = Auth::user();
            $initials = collect(explode(' ', $currentUser->full_name ?? 'C'))
                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <div class="space-y-6 pt-6">
            <div class="flex items-center space-x-4 border-b border-gray-200 pb-6">
                <div class="w-14 h-14 rounded-full bg-black text-white flex items-center justify-center text-lg font-bold tracking-wider">
                    {{ $initials ?: 'KZ' }}
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-gray-900 truncate">
                        {{ $currentUser->full_name ?? 'Pelanggan Kizoku' }}
                    </h3>
                    <p class="text-xs text-gray-500 truncate">{{ $currentUser->email }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] tracking-widest uppercase bg-gray-100 text-gray-700 rounded">
                        Customer
                    </span>
                </div>
            </div>

            <div class="space-y-3 text-xs text-gray-600">
                @if ($currentUser->phone)
                    <div class="flex justify-between py-1 border-b border-gray-100">
                        <span class="text-gray-400">@lang('Telepon')</span>
                        <span class="font-medium text-gray-800">{{ $currentUser->phone }}</span>
                    </div>
                @endif
                @if ($currentUser->address)
                    <div class="flex flex-col py-1 border-b border-gray-100">
                        <span class="text-gray-400 mb-1">@lang('Alamat Pengiriman')</span>
                        <span class="font-medium text-gray-800 leading-relaxed">{{ $currentUser->address }}</span>
                    </div>
                @endif
                <div class="flex justify-between py-1 border-b border-gray-100">
                    <span class="text-gray-400">@lang('Jenis Kelamin')</span>
                    <span class="font-medium text-gray-800">{{ $currentUser->gender }}</span>
                </div>
            </div>

            <div class="pt-6">
                <button
                    wire:click="logout"
                    wire:loading.attr="disabled"
                    type="button"
                    class="w-full py-3 border border-black text-black hover:bg-black hover:text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                >
                    <span wire:loading.remove wire:target="logout">@lang('LOGOUT')</span>
                    <span wire:loading wire:target="logout">@lang('MEMPROSES...')</span>
                </button>
            </div>
        </div>

    {{-- GUEST MODES (LOGIN, REGISTER, FORGOT, SET PASSWORD) --}}
    @else
        <div class="w-full">
            {{-- Tabs Header for Login / Register --}}
            @if (in_array($mode, ['login', 'register']))
                <div class="flex mb-6 border-b border-gray-200">
                    <button
                        wire:click="setAuthMode('login')"
                        class="flex-1 pb-3 text-xs font-semibold text-center tracking-[0.2em] uppercase transition-all duration-300 {{ $mode === 'login' ? 'border-b-2 border-black text-black' : 'border-transparent text-gray-400 hover:text-gray-600' }}"
                    >
                        @lang('LOGIN')
                    </button>
                    <button
                        wire:click="setAuthMode('register')"
                        class="flex-1 pb-3 text-xs font-semibold text-center tracking-[0.2em] uppercase transition-all duration-300 {{ $mode === 'register' ? 'border-b-2 border-black text-black' : 'border-transparent text-gray-400 hover:text-gray-600' }}"
                    >
                        @lang('REGISTER')
                    </button>
                </div>
            @endif

            {{-- Informational & Error Banners --}}
            @if ($infoMessage)
                <div class="mb-4 p-3 bg-gray-50 border-l-2 border-black text-gray-700 text-xs leading-relaxed">
                    {{ $infoMessage }}
                </div>
            @endif

            @if ($errorMessage)
                <div class="mb-4 p-3 bg-red-50 border-l-2 border-red-600 text-red-700 text-xs leading-relaxed">
                    {{ $errorMessage }}
                </div>
            @endif

            {{-- 1. LOGIN MODE --}}
            @if ($mode === 'login')
                <div class="space-y-4">
                    {{-- Step 1: Email and Password --}}
                    @if ($loginStep === 'credentials')
                        <form wire:submit.prevent="requestLoginOtp" class="space-y-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        wire:model="loginEmail"
                                        placeholder="nama@email.com"
                                        required
                                        class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                    />
                                </div>
                                @error('loginEmail') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">@lang('Kata Sandi')</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="{{ $showPassword ? 'text' : 'password' }}"
                                        wire:model="loginPassword"
                                        placeholder="Minimal 6 karakter"
                                        required
                                        class="w-full pl-9 pr-9 py-2.5 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                    />
                                    <button
                                        type="button"
                                        wire:click="togglePasswordVisibility"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        @if ($showPassword)
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        @endif
                                    </button>
                                </div>
                                @error('loginPassword') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-between items-center text-xs pt-1">
                                <button
                                    type="button"
                                    wire:click="setAuthMode('forgot_password')"
                                    class="text-gray-500 hover:text-black underline transition-colors"
                                >
                                    @lang('Lupa Kata Sandi?')
                                </button>
                                <button
                                    type="button"
                                    wire:click="setAuthMode('set_password')"
                                    class="text-gray-500 hover:text-black underline transition-colors"
                                >
                                    @lang('Buat Sandi Pertama Kali')
                                </button>
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-4 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="requestLoginOtp">@lang('MINTA KODE OTP')</span>
                                <span wire:loading wire:target="requestLoginOtp">@lang('MENGIRIM OTP...')</span>
                            </button>
                        </form>

                    {{-- Step 2: Input OTP --}}
                    @elseif ($loginStep === 'otp')
                        <form wire:submit.prevent="submitLogin" class="space-y-4">
                            <div class="text-center py-2">
                                <p class="text-xs text-gray-500">
                                    Masukkan 6 digit kode OTP yang kami kirimkan ke:
                                </p>
                                <p class="text-xs font-semibold text-gray-900 mt-1">{{ $loginEmail }}</p>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 text-center mb-2">Kode OTP (6 Digit)</label>
                                <input
                                    type="text"
                                    wire:model="loginOtp"
                                    maxlength="6"
                                    pattern="\d{6}"
                                    placeholder="••••••"
                                    required
                                    class="w-full py-3 text-center text-xl font-bold tracking-[0.5em] border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('loginOtp') <span class="text-red-500 text-[11px] mt-1 block text-center">{{ $message }}</span> @enderror
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-4 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="submitLogin">@lang('VERIFIKASI & MASUK')</span>
                                <span wire:loading wire:target="submitLogin">@lang('MEMVERIFIKASI...')</span>
                            </button>

                            <div class="flex justify-between items-center text-xs pt-2">
                                <button
                                    type="button"
                                    wire:click="$set('loginStep', 'credentials')"
                                    class="text-gray-500 hover:text-black underline"
                                >
                                    &larr; @lang('Kembali')
                                </button>
                                <button
                                    type="button"
                                    wire:click="requestLoginOtp"
                                    class="text-gray-500 hover:text-black underline"
                                >
                                    @lang('Kirim Ulang OTP')
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

            {{-- 2. REGISTER MODE --}}
            @if ($mode === 'register')
                <div class="space-y-4">
                    {{-- Step 1: Input Email --}}
                    @if ($registerStep === 'email')
                        <form wire:submit.prevent="requestRegisterOtp" class="space-y-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        wire:model="registerEmail"
                                        placeholder="nama@email.com"
                                        required
                                        class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                    />
                                </div>
                                @error('registerEmail') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <p class="text-[11px] text-gray-500 leading-normal">
                                Kami akan mengirimkan 6 digit kode OTP verifikasi ke email Anda sebelum melengkapi profil.
                            </p>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-4 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="requestRegisterOtp">@lang('MINTA KODE OTP REGISTRASI')</span>
                                <span wire:loading wire:target="requestRegisterOtp">@lang('MENGIRIM OTP...')</span>
                            </button>
                        </form>

                    {{-- Step 2: Complete Profile & OTP --}}
                    @elseif ($registerStep === 'details')
                        <form wire:submit.prevent="submitRegister" class="space-y-3">
                            <div class="text-xs bg-gray-50 p-2.5 rounded border border-gray-200">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-semibold text-gray-900 ml-1">{{ $registerEmail }}</span>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Kode OTP (6 Digit)</label>
                                <input
                                    type="text"
                                    wire:model="registerOtp"
                                    maxlength="6"
                                    pattern="\d{6}"
                                    placeholder="••••••"
                                    required
                                    class="w-full py-2.5 text-center text-lg font-bold tracking-[0.4em] border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('registerOtp') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Nama Lengkap</label>
                                <input
                                    type="text"
                                    wire:model="registerFullName"
                                    placeholder="Nama sesuai KTP/identitas"
                                    required
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('registerFullName') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Nomor Telepon</label>
                                    <input
                                        type="tel"
                                        wire:model="registerPhone"
                                        placeholder="08123456789"
                                        class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                    />
                                    @error('registerPhone') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Jenis Kelamin</label>
                                    <select
                                        wire:model="registerGender"
                                        class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors bg-white"
                                    >
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                    @error('registerGender') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Alamat Domisili</label>
                                <input
                                    type="text"
                                    wire:model="registerAddress"
                                    placeholder="Jalan, No Rumah, Kota"
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('registerAddress') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Kata Sandi (Min. 8 Karakter)</label>
                                <input
                                    type="password"
                                    wire:model="registerPassword"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('registerPassword') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Konfirmasi Kata Sandi</label>
                                <input
                                    type="password"
                                    wire:model="registerPasswordConfirmation"
                                    placeholder="Ulangi kata sandi"
                                    required
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('registerPasswordConfirmation') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-3 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="submitRegister">@lang('SELESAIKAN PENDAFTARAN')</span>
                                <span wire:loading wire:target="submitRegister">@lang('MEMPROSES...')</span>
                            </button>

                            <div class="text-center pt-2">
                                <button
                                    type="button"
                                    wire:click="$set('registerStep', 'email')"
                                    class="text-xs text-gray-500 hover:text-black underline"
                                >
                                    &larr; @lang('Ganti Email')
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

            {{-- 3. FORGOT PASSWORD MODE --}}
            @if ($mode === 'forgot_password')
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-3 mb-4 flex items-center justify-between">
                        <h4 class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-900">@lang('RESET KATA SANDI')</h4>
                        <button
                            type="button"
                            wire:click="setAuthMode('login')"
                            class="text-xs text-gray-400 hover:text-black"
                        >
                            &times; @lang('Batal')
                        </button>
                    </div>

                    @if ($forgotStep === 'email')
                        <form wire:submit.prevent="requestForgotOtp" class="space-y-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Email Terdaftar</label>
                                <input
                                    type="email"
                                    wire:model="forgotEmail"
                                    placeholder="nama@email.com"
                                    required
                                    class="w-full px-3 py-2.5 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('forgotEmail') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <p class="text-[11px] text-gray-500 leading-normal">
                                Kami akan mengirimkan 6 digit kode OTP ke email Anda untuk membuat kata sandi baru.
                            </p>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-4 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="requestForgotOtp">@lang('KIRIM KODE OTP RESET')</span>
                                <span wire:loading wire:target="requestForgotOtp">@lang('MENGIRIM...')</span>
                            </button>
                        </form>

                    @elseif ($forgotStep === 'reset')
                        <form wire:submit.prevent="submitResetPassword" class="space-y-3">
                            <div class="text-xs bg-gray-50 p-2.5 rounded border border-gray-200">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-semibold text-gray-900 ml-1">{{ $forgotEmail }}</span>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Kode OTP (6 Digit)</label>
                                <input
                                    type="text"
                                    wire:model="forgotOtp"
                                    maxlength="6"
                                    pattern="\d{6}"
                                    placeholder="••••••"
                                    required
                                    class="w-full py-2.5 text-center text-lg font-bold tracking-[0.4em] border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('forgotOtp') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Kata Sandi Baru (Min. 8 Karakter)</label>
                                <input
                                    type="password"
                                    wire:model="forgotPassword"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('forgotPassword') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Ulangi Kata Sandi Baru</label>
                                <input
                                    type="password"
                                    wire:model="forgotPasswordConfirmation"
                                    placeholder="Konfirmasi kata sandi baru"
                                    required
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('forgotPasswordConfirmation') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-3 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="submitResetPassword">@lang('SIMPAN KATA SANDI BARU')</span>
                                <span wire:loading wire:target="submitResetPassword">@lang('MEMPROSES...')</span>
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- 4. SET PASSWORD MODE (FOR CUSTOMERS WITHOUT PASSWORD) --}}
            @if ($mode === 'set_password')
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-3 mb-4 flex items-center justify-between">
                        <h4 class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-900">@lang('BUAT KATA SANDI')</h4>
                        <button
                            type="button"
                            wire:click="setAuthMode('login')"
                            class="text-xs text-gray-400 hover:text-black"
                        >
                            &times; @lang('Batal')
                        </button>
                    </div>

                    @if ($setStep === 'email')
                        <form wire:submit.prevent="requestSetOtp" class="space-y-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Email Anda</label>
                                <input
                                    type="email"
                                    wire:model="setEmail"
                                    placeholder="nama@email.com"
                                    required
                                    class="w-full px-3 py-2.5 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('setEmail') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <p class="text-[11px] text-gray-500 leading-normal">
                                Masukkan email Anda. Kami akan mengirimkan kode verifikasi 6 digit untuk membuat kata sandi pertama kali.
                            </p>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-4 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="requestSetOtp">@lang('KIRIM KODE OTP')</span>
                                <span wire:loading wire:target="requestSetOtp">@lang('MENGIRIM...')</span>
                            </button>
                        </form>

                    @elseif ($setStep === 'set')
                        <form wire:submit.prevent="submitSetPassword" class="space-y-3">
                            <div class="text-xs bg-gray-50 p-2.5 rounded border border-gray-200">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-semibold text-gray-900 ml-1">{{ $setEmail }}</span>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Kode OTP (6 Digit)</label>
                                <input
                                    type="text"
                                    wire:model="setOtp"
                                    maxlength="6"
                                    pattern="\d{6}"
                                    placeholder="••••••"
                                    required
                                    class="w-full py-2.5 text-center text-lg font-bold tracking-[0.4em] border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('setOtp') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Kata Sandi Baru (Min. 8 Karakter)</label>
                                <input
                                    type="password"
                                    wire:model="setPassword"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('setPassword') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-wider text-gray-500 mb-1">Konfirmasi Kata Sandi</label>
                                <input
                                    type="password"
                                    wire:model="setPasswordConfirmation"
                                    placeholder="Konfirmasi kata sandi baru"
                                    required
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:border-black transition-colors"
                                />
                                @error('setPasswordConfirmation') <span class="text-red-500 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full mt-3 py-3 bg-black hover:bg-neutral-800 text-white text-xs font-semibold uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center space-x-2"
                            >
                                <span wire:loading.remove wire:target="submitSetPassword">@lang('BUAT KATA SANDI')</span>
                                <span wire:loading wire:target="submitSetPassword">@lang('MEMPROSES...')</span>
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>

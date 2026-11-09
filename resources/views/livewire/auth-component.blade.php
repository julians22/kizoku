<div class="w-full">
    {{-- PROFILE VIEW (LOGGED IN) --}}
    @if ($mode === 'profile' && Auth::check())
        <div class="mb-8">
            <h1 class="font-light text-3xl tracking-wide">@lang('My Account')</h1>
            <p class="mt-2 text-gray-500 text-sm">@lang('Manage your orders and account information.')</p>
        </div>

        <div class="gap-10 grid grid-cols-1 lg:grid-cols-[15rem_minmax(0,1fr)]">
            <livewire:account-sidebar-component :account-tab="$accountTab" :account-page="$accountPage" />

            <section id="account-tab-content" class="min-w-0" role="tabpanel" aria-labelledby="account-tab-{{ $accountTab }}">
                @if ($accountTab === 'details')
                    <livewire:account-details-component />
                @elseif ($accountTab === 'orders')
                    <livewire:order-history-component />
                @elseif ($accountTab === 'preferences')
                    <livewire:email-preferences-component />
                @elseif ($accountTab === 'addresses')
                    <livewire:address-book-component />
                @endif
            </section>
        </div>

    {{-- GUEST MODES (LOGIN, REGISTER, FORGOT PASSWORD) --}}
    @else
        <div class="w-full">
            {{-- Tabs Header for Login / Register --}}
            @if (in_array($mode, ['login', 'register']))
                <div class="flex mb-6 border-gray-200 border-b">
                    <button
                        wire:click="setAuthMode('login')"
                        class="flex-1 pb-3 text-xs font-semibold text-center tracking-[0.2em] uppercase transition-all duration-300 {{ $mode === 'login' ? 'border-b-2 border-black text-black' : 'border-transparent text-gray-400 hover:text-gray-600' }}"
                    >
                        @lang('LOG IN')
                    </button>
                    <button
                        wire:click="setAuthMode('register')"
                        class="flex-1 pb-3 text-xs font-semibold text-center tracking-[0.2em] uppercase transition-all duration-300 {{ $mode === 'register' ? 'border-b-2 border-black text-black' : 'border-transparent text-gray-400 hover:text-gray-600' }}"
                    >
                        @lang('SIGN UP')
                    </button>
                </div>
            @endif

            @if ($mode === 'login')
                <livewire:login-form-component :initial-email="$prefilledLoginEmail" :redirect-after-auth="$redirectAfterAuth" />
            @elseif ($mode === 'register')
                <livewire:register-form-component :redirect-after-auth="$redirectAfterAuth" />
            @elseif ($mode === 'forgot_password')
                <livewire:forgot-password-form-component :initial-email="$prefilledForgotEmail" :initial-message="$forgotInfoMessage" />
            @endif
        </div>
    @endif
</div>

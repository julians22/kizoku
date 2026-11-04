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

        <div class="mb-8">
            <h1 class="font-light text-3xl tracking-wide">@lang('My Account')</h1>
            <p class="mt-2 text-gray-500 text-sm">@lang('Manage your orders and account information.')</p>
        </div>

        <div class="gap-10 grid grid-cols-1 lg:grid-cols-[15rem_minmax(0,1fr)]">
            <aside class="flex flex-col lg:min-h-[32rem]">
                <div class="flex items-center gap-3 pb-6 border-gray-200 border-b">
                    <div class="flex justify-center items-center bg-black rounded-full w-12 h-12 font-bold text-white tracking-wider">
                        {{ $initials ?: 'KZ' }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-medium text-gray-900 text-sm truncate">{{ $currentUser->full_name ?? 'Kizoku Customer' }}</h2>
                        <p class="text-gray-500 text-xs truncate">{{ $currentUser->email }}</p>
                    </div>
                </div>

                <nav class="lg:flex lg:flex-col gap-2 grid grid-cols-2 py-6" role="tablist" aria-label="Account sections">
                    @foreach ([
                        'orders' => __('Order History'),
                        'details' => __('Account Details'),
                        'preferences' => __('Email Preferences'),
                        'addresses' => __('Address'),
                    ] as $tab => $label)
                        <button
                            id="account-tab-{{ $tab }}"
                            type="button"
                            role="tab"
                            aria-selected="{{ $accountTab === $tab ? 'true' : 'false' }}"
                            aria-controls="account-tab-content"
                            wire:click="setAccountTab('{{ $tab }}')"
                            class="px-3 py-3 border-l-2 text-sm text-left transition-colors {{ $accountTab === $tab ? 'border-black bg-gray-50 font-medium text-black' : 'border-transparent text-gray-500 hover:text-black' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>

                <button
                    wire:click="logout"
                    wire:loading.attr="disabled"
                    type="button"
                    class="mt-auto px-3 py-3 border-gray-200 border-t text-gray-500 hover:text-black text-sm text-left transition-colors"
                >
                    <span wire:loading.remove wire:target="logout">@lang("Log out")</span>
                    <span wire:loading wire:target="logout">@lang("Logging out...")</span>
                </button>
            </aside>

            <section id="account-tab-content" class="min-w-0" role="tabpanel" aria-labelledby="account-tab-{{ $accountTab }}">
                <div class="space-y-6">
            @if ($accountTab === 'details')
                @php($primaryAddress = $addresses->firstWhere('is_primary', true))
                <div class="pb-4 border-gray-200 border-b">
                    <h2 class="font-light text-2xl">@lang('Account Details')</h2>
                    <p class="mt-1 text-gray-500 text-sm">@lang("Your personal information.")</p>
                </div>
                <dl class="space-y-4 text-sm">
                    <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
                        <dt class="text-gray-500">@lang('Full Name')</dt>
                        <dd class="font-medium text-gray-900">{{ $currentUser->full_name ?? '—' }}</dd>
                    </div>
                    <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
                        <dt class="text-gray-500">@lang('Email')</dt>
                        <dd class="font-medium text-gray-900">{{ $currentUser->email }}</dd>
                    </div>
                    <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
                        <dt class="text-gray-500">@lang('Phone Number')</dt>
                        <dd class="font-medium text-gray-900">{{ $currentUser->phone ?: '—' }}</dd>
                    </div>
                    <div class="flex sm:flex-row flex-col sm:justify-between gap-1 py-3 border-gray-100 border-b">
                        <dt class="text-gray-500">@lang('Gender')</dt>
                        <dd class="font-medium text-gray-900">{{ $currentUser->gender ?: '—' }}</dd>
                    </div>
                    <div class="flex flex-col gap-1 py-3 border-gray-100 border-b">
                        <dt class="text-gray-500">@lang('Primary shipping address')</dt>
                        <dd class="font-medium text-gray-900 leading-relaxed">{{ $primaryAddress?->full_address ?? 'No saved address.' }}</dd>
                    </div>
                </dl>
            @elseif ($accountTab === 'orders')
                <div class="pb-4 border-gray-200 border-b">
                    <h2 class="font-light text-2xl">@lang('Order History')</h2>
                    <p class="mt-1 text-gray-500 text-sm">@lang('Review your recent purchases.')</p>
                </div>
                @if ($ordersLoading)
                    <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-sm text-center">@lang('Memuat riwayat transaksi...')</div>
                @elseif ($ordersError)
                    <div class="p-4 border border-red-200 text-red-500 text-sm">{{ $ordersError }}</div>
                @elseif (! $ordersLoaded)
                    <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-sm text-center">@lang('Memuat riwayat transaksi...')</div>
                @elseif (empty($orders))
                    <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-sm text-center">@lang('Belum ada transaksi.')</div>
                @else
                    <div class="space-y-3">
                        @foreach ($orders as $order)
                            <div class="space-y-3 p-4 border border-gray-200">
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <p class="font-semibold text-xs tracking-wider">{{ $order['order_number'] ?? '-' }}</p>
                                        <p class="mt-1 text-gray-400 text-sm">
                                            {{ isset($order['order_date']) ? \Illuminate\Support\Carbon::parse($order['order_date'])->translatedFormat('d M Y, H:i') : '' }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-0.5 border text-sm uppercase tracking-widest {{ $order['status_class'] ?? 'border-gray-300 bg-gray-50 text-gray-600' }}">
                                        {{ $order['status'] ? __($order['status']) : __('pending') }}
                                    </span>
                                </div>
                                <ul class="space-y-1 text-gray-600 text-base">
                                    @foreach ($order['items'] ?? [] as $item)
                                        <li class="flex justify-between gap-3">
                                            <span class="truncate">{{ $item['name'] }} &times; {{ $item['qty'] }}</span>
                                            <span class="whitespace-nowrap">IDR {{ number_format((float) $item['price'] * (int) $item['qty'], 0) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="flex justify-between pt-2 border-gray-100 border-t text-xs">
                                    <span class="text-gray-400">Total</span>
                                    <span class="font-semibold">IDR {{ number_format((float) ($order['total_price'] ?? 0), 0) }}</span>
                                </div>
                                @if (! empty($order['shipment']))
                                    @php($shipment = $order['shipment'])
                                    <div class="pt-3 border-gray-100 border-t">
                                        <h3 class="font-medium text-xs uppercase tracking-wider">@lang("Shipment timeline")</h3>
                                        <div class="mt-4">
                                            <div class="flex gap-3">
                                                <div class="flex flex-col items-center">
                                                    <span class="bg-black rounded-full size-2.5"></span>
                                                    <span class="bg-gray-200 w-px min-h-8 grow"></span>
                                                </div>
                                                <div class="pb-4">
                                                    <p class="font-medium text-gray-800 text-xs">@lang("Order placed")</p>
                                                    @if (! empty($order['order_date']))
                                                        <p class="mt-1 text-gray-400 text-sm">
                                                            {{ \Illuminate\Support\Carbon::parse($order['order_date'])->translatedFormat('d M Y, H:i') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex gap-3">
                                                <div class="flex flex-col items-center">
                                                    <span class="bg-black rounded-full size-2.5"></span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-medium text-gray-800 text-xs">@lang('Latest shipment status')</p>
                                                    <p class="mt-1 text-gray-600 text-xs">
                                                        {{ ucfirst(str_replace('_', ' ', $shipment['status'] ? __($shipment['status']) : __('pending'))) }}
                                                    </p>
                                                    <p class="mt-1 text-gray-500 text-sm leading-relaxed">
                                                        {{ $shipment['courier_name'] ?? '' }} {{ $shipment['courier_service_name'] ?? '' }}
                                                        @if (! empty($shipment['waybill_id']))
                                                            &middot; @lang('Waybill:') <span class="font-medium text-gray-700">{{ $shipment['waybill_id'] }}</span>
                                                        @endif
                                                    </p>
                                                    <p class="mt-2 text-gray-400 text-sm">
                                                        @lang("Detailed carrier scan events are not available from the current order service.")
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if (($ordersMeta['last_page'] ?? 1) > 1)
                        <div class="flex justify-between items-center pt-2 text-base">
                            <button wire:click="previousOrdersPage" type="button" @disabled(($ordersMeta['current_page'] ?? 1) <= 1) class="disabled:opacity-40 text-gray-600 hover:text-black underline">
                                &larr; @lang('Sebelumnya')
                            </button>
                            <span class="text-gray-400">{{ $ordersMeta['current_page'] ?? 1 }} / {{ $ordersMeta['last_page'] ?? 1 }}</span>
                            <button wire:click="nextOrdersPage" type="button" @disabled(($ordersMeta['current_page'] ?? 1) >= ($ordersMeta['last_page'] ?? 1)) class="disabled:opacity-40 text-gray-600 hover:text-black underline">
                                @lang('Berikutnya') &rarr;
                            </button>
                        </div>
                    @endif
                @endif
            @elseif ($accountTab === 'preferences')
                <div class="pb-4 border-gray-200 border-b">
                    <h2 class="font-light text-2xl">@lang('Email Preferences')</h2>
                    <p class="mt-1 text-gray-500 text-sm">@lang('Choose the updates you would like to see.')</p>
                </div>
                <div class="border-gray-200 border-y divide-y divide-gray-200">
                    <label class="flex justify-between items-center gap-6 py-5 cursor-pointer">
                        <span>
                            <span class="block font-medium text-sm">@lang('Newsletter')</span>
                            <span class="block mt-1 text-gray-500 text-xs">@lang('Receive news, launches, and special offers.')</span>
                        </span>
                        <input type="checkbox" wire:model="newsletterEnabled" class="size-5 accent-black">
                    </label>
                    <label class="flex justify-between items-center gap-6 py-5 cursor-pointer">
                        <span>
                            <span class="block font-medium text-sm">@lang('Email notifications')</span>
                            <span class="block mt-1 text-gray-500 text-xs">@lang('Receive updates about your orders and account.')</span>
                        </span>
                        <input type="checkbox" wire:model="emailNotificationsEnabled" class="size-5 accent-black">
                    </label>
                </div>
                <div class="py-4">
                    <label for="account-language" class="block mb-2 font-medium text-sm">@lang('Language')</label>
                    <select
                        id="account-language"
                        onchange="window.location.href = this.value"
                        class="px-3 py-3 border border-gray-300 focus:border-black focus:outline-none w-full sm:max-w-sm text-sm"
                    >
                        <option value="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL('en') }}" @selected(app()->getLocale() === 'en')>English</option>
                        <option value="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL('id') }}" @selected(app()->getLocale() === 'id')>Bahasa Indonesia</option>
                    </select>
                </div>
                <p class="text-gray-500 text-xs">@lang('Newsletter and email notification selections are temporary and are not saved to your account yet.')</p>
            @elseif ($accountTab === 'addresses' && $profileView === 'addresses')
                <div class="pb-4 border-gray-200 border-b">
                    <h2 class="font-light text-2xl">@lang('Address')</h2>
                    <p class="mt-1 text-gray-500 text-sm">@lang('Manage your saved delivery addresses.')</p>
                </div>
                <div class="space-y-3">
                    @forelse ($addresses as $address)
                        <div class="p-4 border {{ $address->is_primary ? 'border-black' : 'border-gray-200' }}">
                            <div class="flex justify-between items-start gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-xs uppercase tracking-wider">{{ $address->label ?: __('Address') }}</h4>
                                        @if ($address->is_primary)
                                            <span class="bg-black px-2 py-0.5 text-[9px] text-white uppercase tracking-widest">@lang('Primary')</span>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-gray-700 text-xs leading-relaxed">{{ $address->full_address }}</p>
                                    @if ($address->note)
                                        <p class="mt-1 text-gray-400 text-base">@lang('Note:') {{ $address->note }}</p>
                                    @endif
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button wire:click="editAddress({{ $address->id }})" type="button" class="text-gray-500 hover:text-black text-base underline">@lang('Edit')</button>
                                    <button wire:click="deleteAddress({{ $address->id }})" wire:confirm="{{ __('Delete this address?') }}" type="button" class="text-gray-500 hover:text-red-600 text-base underline">@lang('Delete')</button>
                                </div>
                            </div>
                            @if (! $address->is_primary)
                                <button wire:click="setPrimaryAddress({{ $address->id }})" type="button" class="mt-3 text-gray-500 hover:text-black text-base underline">@lang('Set as Primary Address')</button>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-xs text-center">@lang('No saved addresses yet.')</div>
                    @endforelse
                </div>

                <button wire:click="newAddress" type="button" class="bg-black hover:bg-neutral-800 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.18em] transition-colors">@lang('+ ADD ADDRESS')</button>
            @elseif ($accountTab === 'addresses')
                <div class="flex justify-between items-center pb-4 border-gray-200 border-b">
                    <button wire:click="showAddresses" type="button" class="text-gray-500 hover:text-black text-xs">&larr; @lang('Back')</button>
                    <h3 class="font-semibold text-xs uppercase tracking-[0.18em]">{{ $editingAddressId ? __('EDIT ADDRESS') : __('NEW ADDRESS') }}</h3>
                    <span class="w-12"></span>
                </div>

                <form wire:submit.prevent="saveAddress" class="space-y-4">
                    <div class="gap-2 grid grid-cols-1 sm:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang("Recipient's Name")</label>
                            <input type="text" wire:model="addressRecipientName" placeholder="{{ __('placeholder.address.recipient_name') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                            @error('addressRecipientName') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Contact Number')</label>
                            <input type="tel" wire:model="addressRecipientPhone" placeholder="{{ __('placeholder.address.phone') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                            @error('addressRecipientPhone') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang("Recipient's Email (Optional)")</label>
                        <input type="email" wire:model="addressRecipientEmail" placeholder="{{ __('placeholder.address.recipient_email') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                        @error('addressRecipientEmail') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Address Type')</label>
                        <input type="text" wire:model="addressLabel" placeholder="{{ __('placeholder.address.label') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                        @error('addressLabel') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                    </div>
                    <div class="relative">
                        <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('City / Town')</label>
                        <input
                            type="search"
                            wire:model.live.debounce.400ms="addressAreaSearch"
                            wire:input.debounce.400ms="searchAddressAreas"
                            placeholder="{{ __('placeholder.address.area_search') }}"
                            autocomplete="off"
                            class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs"
                        >
                        @if ($addressAreaResults)
                            <div class="top-full right-0 left-0 z-10 absolute bg-white shadow-lg mt-1 border border-gray-200 max-h-48 overflow-y-auto">
                                @foreach ($addressAreaResults as $area)
                                    <button type="button" wire:click="selectAddressArea(@js($area))" class="block hover:bg-gray-50 px-3 py-2 w-full text-xs text-left">
                                        <span class="block font-medium text-gray-800">{{ $area['name'] }}</span>
                                        @if ($area['postal_code']) <span class="text-gray-400">{{ $area['postal_code'] }}</span> @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        <input type="hidden" wire:model="addressAreaId">
                        <input type="hidden" wire:model="addressAreaName">
                        <input type="hidden" wire:model="addressPostalCode">
                        @if ($addressAreaName)
                            <p class="mt-1 text-gray-500 text-base">@lang('Selected:') {{ $addressAreaName }}{{ $addressPostalCode ? ' - ' . $addressPostalCode : '' }}</p>
                        @endif
                        @error('addressAreaId') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Street Address')</label>
                        <textarea wire:model="addressFull" rows="4" required placeholder="{{ __('placeholder.address.full') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs resize-none"></textarea>
                        @error('addressFull') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Notes for the Courier')</label>
                        <textarea wire:model="addressNote" rows="2" placeholder="{{ __('placeholder.address.note') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs resize-none"></textarea>
                        @error('addressNote') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                    </div>
                    <label class="flex items-center gap-2 text-gray-600 text-xs">
                        <input type="checkbox" wire:model="addressIsPrimary" class="border-gray-400 rounded-none focus:ring-0 accent-black">
                        @lang('Set as Primary Address')
                    </label>
                    <button type="submit" wire:loading.attr="disabled" class="bg-black hover:bg-neutral-800 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.18em] transition-colors">
                        <span wire:loading.remove wire:target="saveAddress">@lang('SAVE ADDRESS')</span>
                        <span wire:loading wire:target="saveAddress">@lang('PROCESSING...')</span>
                    </button>
                </form>
            @endif
        </div>
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
                <div class="bg-gray-50 mb-4 p-3 border-black border-l-2 text-gray-700 text-xs leading-relaxed">
                    {{ $infoMessage }}
                </div>
            @endif

            @if ($errorMessage)
                <div class="bg-red-50 mb-4 p-3 border-red-600 border-l-2 text-red-700 text-xs leading-relaxed">
                    {{ $errorMessage }}
                </div>
            @endif

            {{-- 1. LOGIN MODE --}}
            @if ($mode === 'login')
                <div class="space-y-4">
                    <form wire:submit.prevent="login" class="space-y-4">
                            <div>
                                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">Email</label>
                                <div class="relative">
                                    <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        wire:model="loginEmail"
                                        placeholder="nama@email.com"
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
                                        placeholder="Minimal 6 karakter"
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
                                    wire:click="setAuthMode('forgot_password')"
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
            @endif

            {{-- 2. REGISTER MODE --}}
            @if ($mode === 'register')
                <div class="space-y-4">
                    {{-- Step 1: Input Email --}}
                    @if ($registerStep === 'email')
                        <form wire:submit.prevent="requestRegisterOtp" class="space-y-4">
                            <div>
                                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">Email</label>
                                <div class="relative">
                                    <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        wire:model="registerEmail"
                                        placeholder="nama@email.com"
                                        required
                                        class="py-2.5 pr-3 pl-9 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                    />
                                </div>
                                @error('registerEmail') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                            </div>

                            <p class="text-gray-500 text-base leading-normal">
                                @lang('We will send a 6-digit OTP verification code to your email before completing your profile.')
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
                                    placeholder="Nama sesuai KTP/identitas"
                                    required
                                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('registerFullName') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                            </div>

                            <div class="gap-2 grid grid-cols-2">
                                <div>
                                    <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Phone Number')</label>
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
                                    placeholder="{{ __('placeholder.password.min_char', ['min' => '8']) }}"
                                    required
                                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('registerPassword') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Password Confirmation')</label>
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
            @endif

            {{-- 3. FORGOT PASSWORD MODE --}}
            @if ($mode === 'forgot_password')
                <div class="space-y-4">
                    <div class="flex justify-between items-center mb-4 pb-3 border-gray-200 border-b">
                        <h4 class="font-semibold text-gray-900 text-xs uppercase tracking-[0.2em]">@lang('RESET PASSWORD')</h4>
                        <button
                            type="button"
                            wire:click="setAuthMode('login')"
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
                                @lang('We will send a 6-digit OTP verification code to your email before completing your profile.')
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
            @endif

        </div>
    @endif
</div>

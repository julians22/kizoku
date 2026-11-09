<div class="gap-10 lg:gap-16 grid grid-cols-1 lg:grid-cols-12">
    <section class="space-y-12 lg:col-span-7">
        @if ($completed)
            <div class="bg-mint-light mb-8 p-8 border border-mint-border rounded-lg">
                <span class="block mb-2 font-label-caps text-label-caps text-mint-text uppercase tracking-widest">@lang('Payment Simulated')</span>
                <h1 class="mb-4 font-saa-series-e-dot text-mint-text text-5xl">@lang('Your order is confirmed.')</h1>
                <p class="opacity-80 mb-4 max-w-md text-mint-text">
                    @lang("You can view your past orders in your Order History page.")
                </p>
                <div class="mt-6 pt-4 border-mint-border/30 border-t">
                    <p class="opacity-70 text-mint-text">
                        @lang('Transaction reference:') <span class="font-saa-series-e-dot">{{ $transactionReference }}</span>
                    </p>
                </div>
            </div>
        @else
            <form wire:submit.prevent="completePurchase" class="space-y-12">
                <div class="space-y-4">
                    <h2 class="font-bold uppercase tracking-[0.15em]">@lang('1. Contact Information')</h2>
                    <div class="space-y-1">
                        <label class="font-medium text-gray-400 text-xs uppercase tracking-wider">@lang('Email')</label>
                        <input
                            type="email"
                            wire:model.defer="email"
                            placeholder="email@address.com"
                            class="pt-1 pb-2 border-gray-400 focus:border-black border-b focus:outline-none w-full text-sm transition-colors placeholder-gray-300"
                        >
                        @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4 pt-4">
                    <h2 class="font-bold uppercase tracking-[0.15em]">@lang('2. Gift Option')</h2>
                    @if (config('checkout.gift_wrapping_enabled'))
                        <label class="flex items-center space-x-3 cursor-pointer select-none">
                            <input wire:model.defer="giftWrapping" type="checkbox" class="checked:bg-black border border-gray-400 rounded-none focus:ring-0 w-4 h-4 accent-black">
                            <span class="font-medium text-gray-600 uppercase tracking-wider">@lang('Add Complimentary Gift Wrapping')</span>
                        </label>
                    @else
                        <p class="font-medium text-gray-400 uppercase tracking-wider">@lang('Service is not yet available')</p>
                    @endif
                </div>

                <div class="space-y-6 pt-4">
                    <h2 class="font-bold uppercase tracking-[0.15em]">@lang('3. Shipping & Delivery')</h2>

                    <div class="gap-6 grid grid-cols-1 md:grid-cols-2">
                        <div class="space-y-1">
                            <label class="font-medium text-gray-400 text-xs uppercase tracking-wider">@lang('First Name')</label>
                            <input type="text" wire:model.defer="firstName" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
                            @error('firstName') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-gray-400 text-xs uppercase tracking-wider">@lang('Last Name')</label>
                            <input type="text" wire:model.defer="lastName" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
                            @error('lastName') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <label class="font-medium text-gray-400 text-xs uppercase tracking-wider">@lang('Shipping Address')</label>
                            <button type="button" wire:click="$toggle('showNewAddressForm')" class="text-gray-600 hover:text-black text-xs underline uppercase tracking-wider">
                                {{ $showNewAddressForm ? __('Cancel') : __('+ Add Address') }}
                            </button>
                        </div>

                        @if ($addresses->isNotEmpty())
                            <select wire:model.live="selectedAddressId" class="bg-white px-3 py-3 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm transition-colors">
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->label ?: __('Address') }}{{ $address->is_primary ? ' - ' . __('Primary') : '' }}: {{ $address->full_address }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        @if ($showNewAddressForm)
                            <div class="space-y-3 bg-gray-50 p-4 border border-gray-200">
                                <p class="font-semibold text-gray-500 text-xs uppercase tracking-wider">@lang('New Address')</p>
                                <div class="gap-2 grid grid-cols-1 sm:grid-cols-2">
                                    <input type="text" wire:model="newRecipientName" placeholder="{{ __('placeholder.address.recipient_name') }}" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                    <input type="tel" wire:model="newRecipientPhone" placeholder="{{ __('placeholder.address.phone') }}" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                </div>
                                @error('newRecipientName') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                @error('newRecipientPhone') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <input type="email" wire:model="newRecipientEmail" placeholder="{{ __('placeholder.address.recipient_email') }}" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                @error('newRecipientEmail') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <input type="text" wire:model="newAddressLabel" placeholder="{{ __('placeholder.address.label') }}" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                @error('newAddressLabel') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <div class="relative">
                                    <input
                                        type="search"
                                        wire:model.live.debounce.400ms="newAddressAreaSearch"
                                        wire:input.debounce.400ms="searchNewAddressAreas"
                                        placeholder="{{ __('placeholder.address.area_search') }}"
                                        autocomplete="off"
                                        class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm"
                                    >
                                    @if ($newAddressAreaResults)
                                        <div class="top-full right-0 left-0 z-10 absolute bg-white shadow-lg mt-1 border border-gray-200 max-h-48 overflow-y-auto">
                                            @foreach ($newAddressAreaResults as $area)
                                                <button type="button" wire:click="selectNewAddressArea(@js($area))" class="block hover:bg-gray-50 px-3 py-2 w-full text-xs text-left">
                                                    <span class="block font-medium text-gray-800">{{ $area['name'] }}</span>
                                                    @if ($area['postal_code']) <span class="text-gray-400">{{ $area['postal_code'] }}</span> @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    <input type="hidden" wire:model="newAddressAreaId">
                                    <input type="hidden" wire:model="newAddressAreaName">
                                    <input type="hidden" wire:model="newAddressPostalCode">
                                    @if ($newAddressAreaName)
                                        <p class="mt-1 text-gray-500">@lang('Selected:') {{ $newAddressAreaName }}{{ $newAddressPostalCode ? ' - ' . $newAddressPostalCode : '' }}</p>
                                    @endif
                                </div>
                                @error('newAddressAreaId') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <textarea wire:model="newAddressFull" rows="3" required placeholder="{{ __('placeholder.address.full') }}" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm resize-none"></textarea>
                                @error('newAddressFull') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <textarea wire:model="newAddressNote" rows="2" placeholder="{{ __('placeholder.address.note') }}" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm resize-none"></textarea>
                                @error('newAddressNote') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <label class="flex items-center gap-2 text-gray-600 text-xs">
                                    <input type="checkbox" wire:model="newAddressIsPrimary" class="border-gray-400 rounded-none focus:ring-0 accent-black">
                                    @lang('Set as Primary Address')
                                </label>
                                <button type="button" wire:click="saveNewAddress" wire:loading.attr="disabled" class="bg-black hover:bg-neutral-800 px-4 py-3 w-full text-white text-xs uppercase tracking-wider">
                                    <span wire:loading.remove wire:target="saveNewAddress">@lang('Save and Use Address')</span>
                                    <span wire:loading wire:target="saveNewAddress">@lang('Saving...')</span>
                                </button>
                            </div>
                        @elseif ($selectedAddressId)
                            <div class="p-3 border border-gray-200 text-gray-700 text-sm leading-relaxed">
                                {{ $shippingAddress }}
                            </div>
                        @else
                            <p class="text-gray-500 text-xs">@lang('Add an address to continue checkout.')</p>
                        @endif

                        @error('shippingAddress') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between items-center">
                            <h3 class="font-medium text-gray-400 text-xs uppercase tracking-wider">@lang('Courier Rates')</h3>
                        </div>

                        <div
                            wire:loading.flex
                            wire:target="selectedAddressId,saveNewAddress,fetchCourierRates"
                            role="status"
                            aria-live="polite"
                            class="justify-center items-center gap-3 p-4 border border-gray-200 text-gray-500 text-xs"
                        >
                            <svg class="size-4 text-gray-700 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>@lang('Checking available delivery services...')</span>
                        </div>

                        <div wire:loading.remove wire:target="selectedAddressId,saveNewAddress,fetchCourierRates">
                            @if ($isLoadingRates)
                                <div role="status" aria-live="polite" class="flex justify-center items-center gap-3 p-4 border border-gray-200 text-gray-500 text-xs">
                                    <svg class="size-4 text-gray-700 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>@lang('Checking available delivery services...')</span>
                                </div>
                            @elseif ($ratesError)
                                <div class="p-4 border border-red-200 text-red-500 text-xs">{{ $ratesError }}</div>
                            @elseif ($courierPricings)
                                <div class="space-y-3">
                                    @foreach (collect($courierPricings)->groupBy(fn ($rate) => $rate['courier_name'] ?? $rate['company'] ?? 'Courier') as $company => $rates)
                                        <fieldset class="space-y-2">

                                            <legend class="flex items-center gap-2 font-semibold text-gray-700 uppercase tracking-wider">
                                                {{ $company }}
                                                <img src="{{ asset('vendor/couriers/' . strtolower(str_replace(' ', '-', $company)) . '.png') }}" alt="{{ $company }} logo" class="w-auto h-5 object-contain">
                                            </legend>
                                            @foreach ($rates as $rate)
                                                @php($rateKey = ($rate['courier_code'] ?? $rate['company'] ?? '') . ':' . ($rate['courier_service_code'] ?? $rate['type'] ?? ''))
                                                <label class="flex items-start gap-3 hover:bg-gray-50 p-3 border border-gray-200 transition-colors cursor-pointer">
                                                    <input type="radio" wire:model.live="selectedCourierService" value="{{ $rateKey }}" class="mt-1 border-gray-400 focus:ring-0 accent-black">
                                                    <span class="flex flex-1 justify-between gap-3 text-xs">
                                                        <span>
                                                            <span class="block font-medium text-gray-800">{{ $rate['courier_service_name'] ?? $rate['type'] ?? 'Service' }}</span>
                                                            <span class="block mt-1 text-gray-500">{{ $rate['duration'] ?? 'Delivery time unavailable' }}</span>
                                                        </span>
                                                        <span class="font-semibold text-gray-800 whitespace-nowrap">IDR {{ number_format((int) ($rate['price'] ?? $rate['shipping_fee'] ?? 0), 0) }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </fieldset>
                                    @endforeach
                                </div>
                            @elseif ($selectedAddressId)
                                <p class="p-4 border border-gray-200 text-gray-500 text-xs">@lang('No courier rates are available for this postal code.')</p>
                            @else
                                <p class="p-4 border border-gray-200 text-gray-500 text-xs">@lang('Select or create an address to calculate delivery rates.')</p>
                            @endif
                        </div>

                        @error('courier') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                @error('payment')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </form>
        @endif
    </section>

    <section class="lg:col-span-5">
        <div class="lg:top-6 lg:sticky bg-stone-50 p-5 md:p-10">
            <h2 class="mb-8 font-bold text-[#111625] text-xs uppercase tracking-[0.15em]">@lang('Order Summary')</h2>

            <div class="space-y-6 mb-8">
                @foreach ($selectedParfumes as $parfume)
                    <div class="flex justify-between items-center space-x-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex justify-center items-center bg-[#2d3238] shadow-inner w-20 h-20 overflow-hidden shrink-0">
                                <img src="{{ $parfume['image'] }}" alt="{{ $parfume['name'] }}" class="size-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-[#111625] text-xs uppercase tracking-wider">{{ $parfume['name'] }}</h3>
                                <p class="mt-1 text-stone-400 text-xs uppercase tracking-wider">Qty: {{ $parfume['quantity'] }}</p>
                                <p class="mt-1 font-medium text-stone-500 text-xs tracking-wider">IDR {{ number_format($parfume['price'], 0) }}</p>
                            </div>
                        </div>
                        <span class="font-medium text-gray-700 text-sm tracking-wider">IDR {{ number_format($parfume['price'] * $parfume['quantity'], 0) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="space-y-3 pt-6 border-gray-300 border-t text-sm tracking-wider">
                <div class="flex justify-between text-gray-600">
                    <span class="uppercase">@lang('Subtotal')</span>
                    <span>IDR {{ number_format($subtotal, 0) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span class="uppercase">@lang('Shipping')</span>
                    <span class="font-medium uppercase">
                        {{ $selectedCourierService ? 'IDR ' . number_format($shippingCost, 0) : __('Select a service') }}
                    </span>
                </div>
                <div class="flex justify-between text-stone-500">
                    <span class="uppercase">@lang('Taxes (Included)')</span>
                    <span>IDR {{ number_format($taxes, 0) }}</span>
                </div>
            </div>

            <div class="flex justify-between items-baseline mt-6 mb-8 pt-6 border-black border-t">
                <span class="font-bold text-sm uppercase tracking-[0.15em]">@lang('Total')</span>
                <span class="font-bold text-base tracking-wider">IDR {{ number_format($subtotal + $shippingCost, 0) }}</span>
            </div>

            @if (! $completed)
                <button
                    type="button"
                    wire:click="completePurchase"
                    wire:loading.attr="disabled"
                    @disabled($isLoadingRates || ($courierPricings && ! $selectedCourierService))
                    class="bg-[#111625] hover:bg-black disabled:opacity-70 py-4 w-full font-bold text-white text-xs uppercase tracking-[0.2em] transition-colors cursor-pointer disabled:cursor-wait"
                >
                    <span wire:loading.remove wire:target="completePurchase">@lang('Complete Purchase')</span>
                    <span wire:loading wire:target="completePurchase">@lang('Preparing Payment...')</span>
                </button>
            @else
                <div class="bg-mint-light/50 p-4 border border-mint-border/50 rounded text-center">
                    <span class="font-body-md text-body-md text-mint-text text-sm">@lang('Payment completed and cart cleared.')</span>
                </div>
            @endif
        </div>
    </section>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('snap-token-created', (event) => {
                const payload = Array.isArray(event) ? event[0] : event;
                const component = Livewire.find(payload.componentId);

                if (!window.snap) {
                    console.error('Midtrans Snap script is not loaded.');
                    return;
                }

                if (!component) {
                    console.error('Unable to find Livewire checkout component instance.');
                    return;
                }

                window.snap.pay(payload.token, {
                    onSuccess: (result) => {
                        component.call('markPaymentSuccess', result);
                    },
                    onPending: (result) => {
                        component.call('markPaymentPending', result);
                    },
                    onError: (result) => {
                        component.call('markPaymentError', result);
                    },
                    onClose: () => {
                        component.call('markPaymentError', { status_message: 'popup closed' });
                    },
                });
            });
        });
    </script>
</div>

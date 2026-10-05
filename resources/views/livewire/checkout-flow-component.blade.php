<div class="gap-16 grid grid-cols-1 lg:grid-cols-12">
    <section class="space-y-12 lg:col-span-7">
        @if ($completed)
        <div class="bg-mint-light mb-8 p-8 border border-mint-border rounded-lg">
            <span class="block mb-2 font-label-caps text-label-caps text-mint-text uppercase tracking-widest">Payment Simulated</span>
            <h1 class="mb-4 font-saa-series-e-dot text-mint-text text-5xl">Your order is confirmed.</h1>
            <p class="opacity-80 mb-4 max-w-md text-mint-text">
                Midtrans is still a blueprint here, so this flow uses a short loading delay to simulate the payment request.
            </p>
            <div class="mt-6 pt-4 border-mint-border/30 border-t">
                <p class="opacity-70 text-mint-text">
                    Transaction reference: <span class="font-saa-series-e-dot">{{ $transactionReference }}</span>
                </p>
            </div>
        </div>
        @else
            <form wire:submit.prevent="completePurchase" class="space-y-12">
                <div class="space-y-4">
                    <h2 class="font-bold uppercase tracking-[0.15em]">1. Contact Information</h2>
                    <div class="space-y-1">
                        <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Email Address</label>
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
                    <h2 class="font-bold uppercase tracking-[0.15em]">2. Gift Option</h2>
                    <label class="flex items-center space-x-3 cursor-pointer select-none">
                        <input wire:model.defer="giftWrapping" type="checkbox" class="checked:bg-black border border-gray-400 rounded-none focus:ring-0 w-4 h-4 accent-black">
                        <span class="font-medium text-[11px] text-gray-600 uppercase tracking-wider">Add Complimentary Gift Wrapping</span>
                    </label>
                </div>

                <div class="space-y-6 pt-4">
                    <h2 class="font-bold uppercase tracking-[0.15em]">3. Shipping & Delivery</h2>

                    <div class="gap-6 grid grid-cols-1 md:grid-cols-2">
                        <div class="space-y-1">
                            <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">First Name</label>
                            <input type="text" wire:model.defer="firstName" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
                            @error('firstName') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Last Name</label>
                            <input type="text" wire:model.defer="lastName" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
                            @error('lastName') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Shipping Address</label>
                            <button type="button" wire:click="$toggle('showNewAddressForm')" class="text-[10px] text-gray-600 hover:text-black underline uppercase tracking-wider">
                                {{ $showNewAddressForm ? 'Batal' : '+ Tambah Alamat' }}
                            </button>
                        </div>

                        @if ($addresses->isNotEmpty())
                            <select wire:model="selectedAddressId" class="bg-white px-3 py-3 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm transition-colors">
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->label ?: 'Alamat' }}{{ $address->is_primary ? ' - Utama' : '' }}: {{ $address->full_address }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        @if ($showNewAddressForm)
                            <div class="space-y-3 bg-gray-50 p-4 border border-gray-200">
                                <p class="font-semibold text-[10px] text-gray-500 uppercase tracking-wider">Alamat Baru</p>
                                <div class="gap-2 grid grid-cols-1 sm:grid-cols-2">
                                    <input type="text" wire:model="newRecipientName" placeholder="Nama penerima" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                    <input type="tel" wire:model="newRecipientPhone" placeholder="Nomor telepon" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                </div>
                                @error('newRecipientName') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                @error('newRecipientPhone') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <input type="email" wire:model="newRecipientEmail" placeholder="Email penerima (opsional)" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                @error('newRecipientEmail') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <input type="text" wire:model="newAddressLabel" placeholder="Label, contoh: Rumah" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm">
                                @error('newAddressLabel') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <div class="relative">
                                    <input
                                        type="search"
                                        wire:model.live.debounce.400ms="newAddressAreaSearch"
                                        wire:input.debounce.400ms="searchNewAddressAreas"
                                        placeholder="Cari kota / area, contoh: Jakarta Selatan"
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
                                        <p class="mt-1 text-[11px] text-gray-500">Terpilih: {{ $newAddressAreaName }}{{ $newAddressPostalCode ? ' - ' . $newAddressPostalCode : '' }}</p>
                                    @endif
                                </div>
                                @error('newAddressAreaId') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <textarea wire:model="newAddressFull" rows="3" required placeholder="Alamat lengkap" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm resize-none"></textarea>
                                @error('newAddressFull') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <textarea wire:model="newAddressNote" rows="2" placeholder="Catatan kurir (opsional)" class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-sm resize-none"></textarea>
                                @error('newAddressNote') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                <label class="flex items-center gap-2 text-gray-600 text-xs">
                                    <input type="checkbox" wire:model="newAddressIsPrimary" class="border-gray-400 rounded-none focus:ring-0 accent-black">
                                    Jadikan alamat utama
                                </label>
                                <button type="button" wire:click="saveNewAddress" wire:loading.attr="disabled" class="bg-black hover:bg-neutral-800 px-4 py-3 w-full text-white text-xs uppercase tracking-wider">
                                    <span wire:loading.remove wire:target="saveNewAddress">Simpan dan Gunakan Alamat</span>
                                    <span wire:loading wire:target="saveNewAddress">Menyimpan...</span>
                                </button>
                            </div>
                        @elseif ($selectedAddressId)
                            <div class="p-3 border border-gray-200 text-gray-700 text-sm leading-relaxed">
                                {{ $shippingAddress }}
                            </div>
                        @else
                            <p class="text-gray-500 text-xs">Tambahkan alamat untuk melanjutkan checkout.</p>
                        @endif

                        @error('shippingAddress') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between items-center">
                            <h3 class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Courier Rates</h3>
                            @if ($isLoadingRates)
                                <span class="text-[10px] text-gray-400 uppercase tracking-wider">Calculating...</span>
                            @endif
                        </div>

                        @if ($isLoadingRates)
                            <div class="p-4 border border-gray-200 text-gray-500 text-xs">Checking available delivery services...</div>
                        @elseif ($ratesError)
                            <div class="p-4 border border-red-200 text-red-500 text-xs">{{ $ratesError }}</div>
                        @elseif ($courierPricings)
                            @foreach (collect($courierPricings)->groupBy(fn ($rate) => $rate['courier_name'] ?? $rate['company'] ?? 'Courier') as $company => $rates)
                                <fieldset class="space-y-2">
                                    <legend class="font-semibold text-[11px] text-gray-700 uppercase tracking-wider">{{ $company }}</legend>
                                    @foreach ($rates as $rate)
                                        @php($rateKey = ($rate['courier_code'] ?? $rate['company'] ?? '') . ':' . ($rate['courier_service_code'] ?? $rate['type'] ?? ''))
                                        <label class="flex items-start gap-3 hover:bg-gray-50 p-3 border border-gray-200 transition-colors cursor-pointer">
                                            <input type="radio" wire:model.live="selectedCourierService" value="{{ $rateKey }}" class="mt-1 border-gray-400 focus:ring-0 accent-black">
                                            <span class="flex flex-1 justify-between gap-3 text-xs">
                                                <span>
                                                    <span class="block font-medium text-gray-800">{{ $rate['courier_service_name'] ?? $rate['type'] ?? 'Service' }}</span>
                                                    <span class="block mt-1 text-[11px] text-gray-500">{{ $rate['duration'] ?? 'Delivery time unavailable' }}</span>
                                                </span>
                                                <span class="font-semibold text-gray-800 whitespace-nowrap">IDR {{ number_format((int) ($rate['price'] ?? $rate['shipping_fee'] ?? 0), 0) }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </fieldset>
                            @endforeach
                        @elseif ($selectedAddressId)
                            <p class="p-4 border border-gray-200 text-gray-500 text-xs">No courier rates are available for this postal code.</p>
                        @else
                            <p class="p-4 border border-gray-200 text-gray-500 text-xs">Select or create an address to calculate delivery rates.</p>
                        @endif

                        @error('courier') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4 pt-4">
                    <h2 class="font-bold text-[#111625] text-sm uppercase tracking-[0.15em]">4. Payment</h2>
                    <div class="bg-[#f8f9fa] p-4 border border-gray-200 font-medium text-[11px] text-gray-400 uppercase tracking-widest">
                        Midtrans Snap payment gateway
                    </div>
                </div>

                @error('payment')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </form>
        @endif
    </section>

    <section class="lg:col-span-5">
        <div class="top-6 sticky bg-stone-50 p-8 md:p-10">
            <h2 class="mb-8 font-bold text-[#111625] text-xs uppercase tracking-[0.15em]">Order Summary</h2>

            <div class="space-y-6 mb-8">
                @foreach ($selectedParfumes as $parfume)
                    <div class="flex justify-between items-center space-x-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex justify-center items-center bg-[#2d3238] shadow-inner w-20 h-20 overflow-hidden shrink-0">
                                <img src="{{ $parfume['image'] }}" alt="{{ $parfume['name'] }}" class="size-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-bold text-[#111625] text-xs uppercase tracking-wider">{{ $parfume['name'] }}</h3>
                                <p class="mt-1 text-[10px] text-stone-400 uppercase tracking-wider">Qty: {{ $parfume['quantity'] }}</p>
                                <p class="mt-1 font-medium text-stone-500 text-xs tracking-wider">IDR {{ number_format($parfume['price'], 0) }}</p>
                            </div>
                        </div>
                        <span class="font-medium text-gray-700 text-sm tracking-wider">IDR {{ number_format($parfume['price'] * $parfume['quantity'], 0) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="space-y-3 pt-6 border-gray-300 border-t text-sm tracking-wider">
                <div class="flex justify-between text-gray-600">
                    <span class="uppercase">Subtotal</span>
                    <span>IDR {{ number_format($subtotal, 0) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span class="uppercase">Shipping</span>
                    <span class="font-medium text-[11px] uppercase">
                        {{ $selectedCourierService ? 'IDR ' . number_format($shippingCost, 0) : 'Select a service' }}
                    </span>
                </div>
                <div class="flex justify-between text-[11px] text-stone-500">
                    <span class="uppercase">Taxes (Included)</span>
                    <span>IDR {{ number_format($taxes, 0) }}</span>
                </div>
            </div>

            <div class="flex justify-between items-baseline mt-6 mb-8 pt-6 border-black border-t">
                <span class="font-bold text-sm uppercase tracking-[0.15em]">Total</span>
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
                    <span wire:loading.remove wire:target="completePurchase">Complete Purchase</span>
                    <span wire:loading wire:target="completePurchase">Preparing Payment...</span>
                </button>
            @else
                <div class="bg-mint-light/50 p-4 border border-mint-border/50 rounded text-center">
                    <span class="font-body-md text-body-md text-mint-text text-sm">Payment completed and cart cleared.</span>
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

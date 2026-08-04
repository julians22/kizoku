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

                    <div class="space-y-1">
                        <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Shipping Address</label>
                        <input type="text" wire:model.defer="shippingAddress" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
                        @error('shippingAddress') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
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
                    <span class="font-medium text-[11px] uppercase">Free</span>
                </div>
                <div class="flex justify-between text-[11px] text-stone-500">
                    <span class="uppercase">Taxes (Included)</span>
                    <span>IDR {{ number_format($taxes, 0) }}</span>
                </div>
            </div>

            <div class="flex justify-between items-baseline mt-6 mb-8 pt-6 border-black border-t">
                <span class="font-bold text-sm uppercase tracking-[0.15em]">Total</span>
                <span class="font-bold text-base tracking-wider">IDR {{ number_format($subtotal, 0) }}</span>
            </div>

            @if (! $completed)
                <button
                    type="button"
                    wire:click="completePurchase"
                    wire:loading.attr="disabled"
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

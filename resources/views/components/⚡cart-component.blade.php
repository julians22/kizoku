<?php

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    use App\Livewire\Traits\ParfumeCollection;

    public $cartItems = [];

    public function mount()
    {
        $this->loadProducts();
    }

    #[Computed()]
    public function total()
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    #[On('cartUpdated')]
    public function onCartUpdated()
    {
        $this->loadProducts();
    }

    #[On('addToCart')]
    public function addToCart($slug)
    {
        $cartItems = session()->get('cart', []);

        $parfumes = $this->getParfumeCollections();

        // check if the product exists in the collection

        $selectedParfume = collect($parfumes)->firstWhere('slug', $slug);

        if ($selectedParfume) {
            // Log the selected product for debugging
            Log::info('Selected Parfume:', ['product' => $selectedParfume]);

            $quantity = 1;

            // Check if the product already exists in the cart
            $existingCartItemIndex = collect($cartItems)->search(function ($item) use ($selectedParfume) {
                return $item['slug'] === $selectedParfume['slug'];
            });

            if ($existingCartItemIndex !== false) {
                // If the product already exists, update the quantity
                $cartItems[$existingCartItemIndex]['quantity'] += $quantity;
            } else {
                // If the product does not exist, add it to the cart
                $cartItems[] = [
                    'name' => $selectedParfume['name'],
                    'slug' => $selectedParfume['slug'],
                    'price' => $selectedParfume['price'],
                    'quantity' => $quantity,
                ];
            }

            session()->put('cart', $cartItems);

            Log::info('Product added to cart:', ['cartItems' => $cartItems]);

            // Emit an event or show a success message
            $this->dispatch('trigger-toast', type: 'success', message : 'Your cart successfully updated');

            $this->loadProducts();
        } else {
            Log::warning('Product not found in collection:', ['slug' => $slug]);
            session()->flash('error', 'Product not found!');
        }
    }


    public function loadProducts()
    {
        $parfumes = $this->getParfumeCollections();

        $items = session()->get('cart', []);

        $this->cartItems = array_map(function ($item) use ($parfumes) {
            $product = collect($parfumes)->firstWhere('slug', $item['slug']);

            return [
                'name' => $item['name'],
                'slug' => $item['slug'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $product ? $product['image'] : null,
            ];
        }, $items);

        $this->dispatch('cartUpdated')->to('utils.badge-count');

    }

    public function decrement($slug)
    {
        $cartItems = session()->get('cart', []);

        $parfumes = $this->getParfumeCollections();

        // check if the product exists in the collection
        $cartItems = session()->get('cart', []);
        $existingCartItemIndex = collect($cartItems)->search(fn($item) => $item['slug'] === $slug);

        if ($existingCartItemIndex !== false) {
            if ($cartItems[$existingCartItemIndex]['quantity'] > 1) {
                $cartItems[$existingCartItemIndex]['quantity']--;
            } else {
                unset($cartItems[$existingCartItemIndex]);
                $cartItems = array_values($cartItems);
            }
            session()->put('cart', $cartItems);
            $this->loadProducts();
            $this->dispatch('trigger-toast', type: 'success', message: 'Cart updated');
        }
    }

    public function increment($slug)
    {
        $cartItems = session()->get('cart', []);
        $existingCartItemIndex = collect($cartItems)->search(fn($item) => $item['slug'] === $slug);

        if ($existingCartItemIndex !== false) {
            $cartItems[$existingCartItemIndex]['quantity']++;
            session()->put('cart', $cartItems);
            $this->loadProducts();
            $this->dispatch('trigger-toast', type: 'success', message: 'Cart updated');
        }
    }

    public function removeFromCart($slug)
    {
        $cartItems = session()->get('cart', []);
        $filteredCart = array_filter($cartItems, fn($item) => $item['slug'] !== $slug);

        session()->put('cart', array_values($filteredCart));
        $this->loadProducts();
        $this->dispatch('trigger-toast', type: 'success', message: 'Item removed from cart');
    }
};
?>

<div class="flex flex-col pt-10 h-full">
    {{-- You must be the change you wish to see in the world. - Mahatma Gandhi --}}

        <div class="grow shrink-0">
            @if ($cartItems)
                <ul class="divide-y divide-gray-200">
                    @foreach ($cartItems as $item)
                        <li class="flex items-center gap-x-4 py-4">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded w-20 h-20 object-cover">
                            <div>
                                <h3 class="text-gray-900 text-sm">{{ $item['name'] }}</h3>
                                <p class="text-gray-900 text-base">IDR {{ number_format($item['price'], 0) }}</p>

                                <div class="flex items-center space-x-2 mt-4">
                                    <button
                                        wire:click="decrement('{{ $item['slug'] }}')"
                                        class="flex justify-center items-center border border-gray-800 size-7 cursor-pointer">
                                        <x-carbon-subtract class="fill-gray-800 size-4"/>
                                    </button>

                                    <div class="text-gray-800 text-lg" >{{ $item['quantity'] }}</div>


                                    <button
                                        wire:click="increment('{{ $item['slug'] }}')"
                                        class="flex justify-center items-center border border-gray-800 size-7 cursor-pointer">
                                        <x-carbon-add class="fill-gray-800 size-4"/>
                                    </button>
                                </div>

                                <button
                                    wire:click="removeFromCart('{{ $item['slug'] }}')"
                                    class="mt-4 text-right underline cursor-pointer">
                                    @lang("REMOVE")
                                </button>

                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-10 text-gray-500 text-2xl text-center">Your cart is empty.</p>
            @endif
        </div>

        @if ($cartItems)
            <div class="pb-10">
                <!-- Garis Pemisah Atas -->
                <div class="mb-8 border-slate-800 border-t"></div>

                <!-- Baris Total -->
                <div class="flex justify-between items-center mb-10 text-[#2D3142]">
                    <span class="font-medium text-sm uppercase tracking-[0.2em]">
                        Total
                    </span>
                    <span class="font-medium text-lg tracking-wider">
                        IDR {{ number_format($this->total, 0) }}
                    </span>
                </div>

                <!-- Tombol Checkout -->
                <button class="bg-black hover:bg-slate-800 px-6 py-5 w-full text-white transition-all duration-300 cursor-pointer">
                    <span class="font-semibold text-xs uppercase tracking-[0.3em]">
                        @lang("Proceed to Checkout")
                    </span>
                </button>
            </div>
        @endif


</div>

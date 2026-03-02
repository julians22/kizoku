<?php

use Livewire\Component;

new class extends Component
{
    use App\Livewire\Traits\ParfumeCollection;

    public bool $icon = false;
    public string|null $text = null;

    public string $slug;

    public function mount(string $slug, bool $icon = false, string|null $text = null)
    {
        $this->slug = $slug;
    }

    public function addToCart()
    {
        // verify slug first
        $parfumes = $this->getParfumeCollections();
        $selectedParfume = collect($parfumes)->firstWhere('slug', $this->slug);

        if (!$selectedParfume) {
            Log::warning('Product not found in collection:', ['slug' => $this->slug]);
            $this->dispatch('trigger-toast', type: 'warning', message : 'Product not found!');
            return;
        }
        $this->dispatch('addToCart', $this->slug)->to('cart-component');
    }
};
?>

<div>
    {{-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca --}}

    {{-- Create dynamic add to cart button --}}
    <button
        title="Add to cart"
        wire:loading.attr="disabled"
        wire:click.prevent="addToCart"

        {{ $attributes->merge(['class' => 'bg-black hover:bg-gray-800 px-4 py-2 font-medium text-white text-sm transition-colors cursor-pointer data-loading:opacity-50']) }}
    >
        @if($text)
            <span class="uppercase tracking-widest">{{ $text }}</span>
        @endif

        @if($icon)
            <span class="{{ $text ? 'ml-2' : '' }}">
                <x-carbon-add class="fill-white size-4"/>
            </span>
        @endif
    </button>

</div>

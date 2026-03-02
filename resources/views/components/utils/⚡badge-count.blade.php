<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $countFor = 'cart';
    public $count = 0;

    public function mount($countFor = 'cart')
    {
        $this->countFor = $countFor;
        $this->getCounter();
    }

    public function getCounter() : void
    {
        if ($this->countFor === 'cart') {
            $cartItems = session()->get('cart', []);
            $this->count = array_sum(array_column($cartItems, 'quantity'));
        }
    }

    #[On('cartUpdated')]
    public function onCartUpdated()
    {
        $this->getCounter();
    }
};
?>

<span class="ml-2">
    {{-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead --}}
    ({{ $count }})
</span>

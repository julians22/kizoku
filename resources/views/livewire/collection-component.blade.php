<div>
    {{-- An unexamined life is not worth living. - Socrates --}}
    @if ($this->collections->isNotEmpty())
        <div class="gap-12 grid grid-cols-1 md:grid-cols-2">
            @foreach ($this->collections as $collection)
                <a class="group block overflow-hidden"
                    wire:key="collection-{{ $collection['slug'] }}"
                    href="{{ route('products.detail', ['product'=>$collection['slug']]) }}"
                    >
                    <div class="w-full aspect-square overflow-hidden">
                        <img src="{{ $collection['image'] }}" alt="{{ $collection['name'] }}"
                            width="600" height="600"
                            class="size-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="relative space-y-2 bg-white py-6">
                        <h2>Eau De Parfume</h2>
                        <h3 class="font-semibold text-gray-900 text-lg tracking-widest">{{ $collection['name'] }}</h3>
                        <p class="text-gray-900">IDR {{ number_format($collection['price'], 0) }}</p>

                        <div
                            class="right-6 bottom-6 z-10 absolute">
                                <livewire:utils.add-to-cart-button slug="{{ $collection['slug'] }}" icon="true"/>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <p class="mt-10 text-gray-500 text-2xl text-center">No collections found.</p>
    @endif

    @if (session('message'))
        <div class="bg-green-100 mt-4 p-4 rounded text-green-800">
            {{ session('message') }}
        </div>
    @endif

</div>

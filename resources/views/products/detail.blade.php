@extends('layouts.app')

@section('title', 'Kizoku | Parfume Collection')

@section('content')

<!-- Hero -->
<section
    class="flex flex-col justify-center items-center bg-cover bg-no-repeat bg-center mt-16 2xl:mt-20"
>

    {{-- <div class="grid grid-cols-2 py-10 w-full">

        <div class="aspect-square">
            <img src="{{ asset($selectedParfume['image']) }}" alt="" class="w-full h-full object-cover">
        </div>
        <div class="aspect-square">
            <img src="{{ asset('img/insight-1.png') }}" alt="" class="w-full h-full object-cover">
        </div>

    </div> --}}
    <div
        x-data="{
            distance: 0,
            x: 0,
            measure() {
                const view = this.$refs.view;
                this.distance = Math.max(this.$refs.track.scrollWidth - view.clientWidth, 0);
                this.$el.style.height = `${this.distance + view.offsetHeight}px`;
                this.update();
            },
            update() {
                const stickyTop = parseFloat(getComputedStyle(this.$refs.view).top) || 0;
                const top = this.$el.getBoundingClientRect().top;
                this.x = Math.min(Math.max(stickyTop - top, 0), this.distance);
            }
        }"
        x-init="$nextTick(() => measure())"
        @scroll.window.passive="update()"
        @resize.window.debounce.150ms="measure()"
        class="relative w-full"
    >
        <div x-ref="view" class="top-16 2xl:top-20 sticky flex items-center w-full h-[calc(100dvh-4rem)] 2xl:h-[calc(100dvh-5rem)] overflow-hidden">

            <div
                x-ref="track"
                class="flex py-4 2xl:py-10 w-full will-change-transform"
                :style="`transform: translate3d(${-x}px, 0, 0)`"
            >
                @foreach ($selectedParfume['insight_images'] as $insightImage)
                    <div class="w-4/5 md:w-1/2 aspect-square shrink-0">
                        <img src="{{ asset($insightImage) }}" alt="" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <div class="gap-x-6 gap-y-10 grid grid-cols-1 2xl:grid-cols-10 mx-auto px-4 py-10 max-w-6xl">
        <div class="2xl:col-span-6">
            <p class="mb-5">Eau De Parfume</p>

            <h2 class="font-bold text-3xl">{{ $selectedParfume['name'] }}</h2>
            <p class="mb-5">IDR {{ number_format($selectedParfume['price'], 0) }}</p>

            <div class="mb-5">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum laboriosam ab corrupti rem dolores ducimus ipsa nisi beatae voluptatem doloribus omnis veritatis maxime voluptates nam, similique nulla quia rerum id!</p>
            </div>

            <!-- cart button -->
            <livewire:utils.add-to-cart-button slug="{{ $selectedParfume['slug'] }}" text="{{__('ADD TO CART')}}"/>
        </div>

        <div class="2xl:col-span-4">
            <div class="flex flex-col">

                @foreach ($selectedParfume['product_features'] as $feature)
                    <!-- Item Menu: {{ $feature['title'] }} -->
                    <div class="product-detail-dropdown-wrapper" x-data="{show: false}">
                        <a href="#" class="group product-detail-dropdown-link"
                            @click.prevent="show = !show">
                            <span class="font-medium text-slate-700 text-sm uppercase tracking-widest">{{ $feature['title'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" x-bind:class="show ? 'rotate-90 scale-110 -translate-x-4' : ''" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition duration-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <div class="product-detail-dropdown-item"
                            x-transition
                            x-cloak
                            x-show="show">
                            {{ $feature['description'] }}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

</section>


@endsection

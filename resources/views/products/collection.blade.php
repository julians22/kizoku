@extends('layouts.app')

@section('title', 'Kizoku | Parfume Collection')

@section('content')

<!-- Hero -->
{{-- <section
    class="flex justify-center items-center bg-gray-100 bg-cover bg-no-repeat bg-top bg-fixed aspect-[101/71]"
    style="background-image: url({{ asset('img/collection-hero-parfume.png') }});" --}}
<section
    x-data="{
        active: false,
        y: 0,
        updateParallax() {
            if (this.active) {
                // Adjust factor (0.2) to change speed
                this.y = window.pageYOffset * 0.1;
            }
        }
    }"
    @scroll.window="updateParallax"
    x-intersect="active = true"
    x-intersect:leave="active = false"
    class="z-20 before:z-10 before:absolute relative before:inset-0 flex justify-center items-center bg-gray-100 before:bg-[image:var(--tw-bg-section)] before:bg-cover before:bg-no-repeat bg-top bg-fixed before:size-full aspect-[4/5] md:aspect-[10/8] before:content-[''] before:transform-3d before:-translate-z-0.5 before:translate-y-[var(--tw-lax-transform)]"
    :style="`--tw-lax-transform: ${y}px; --tw-bg-section:  url({{ asset('img/collection_banner.webp') }});`"
    {{-- style="background-image: url({{ asset('img/collection-hero-parfume.png') }});" --}}
>
</section>

<!-- Collection -->
<section class="z-30 relative bg-white py-8 lg:py-12 2xl:py-16">

    <div class="mx-auto mb-8 lg:mb-12 2xl:mb-16 px-4 max-w-6xl">
        <img src="{{ asset('img/product_sub_banner.webp') }}" alt="Kizoku Collection">
    </div>

    <div class="mx-auto px-4 max-w-6xl">

        <livewire:collection-component :category="$slug" />

    </div>
</section>


@endsection

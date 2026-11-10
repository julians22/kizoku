@extends('layouts.app')

@section('title', 'Kizoku | Parfume Collection')

@section('content')

<!-- Hero -->
<section
    class="flex flex-col justify-center items-center bg-cover bg-no-repeat bg-center mt-16 2xl:mt-20"
>
    <div class="relative w-full">
        <div class="glide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    @foreach ($selectedParfume['insight_images'] as $insightImage)
                        <li class="glide__slide">
                            <img src="{{ asset($insightImage) }}" alt="" class="w-full h-full object-cover">
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="gap-x-6 gap-y-10 grid grid-cols-1 2xl:grid-cols-10 mx-auto px-4 py-10 max-w-6xl">
        <div class="2xl:col-span-6">
            <p class="mb-5">@lang('Extrait de Parfum')</p>

            <h2 class="font-bold text-3xl">{{ $selectedParfume['name'] }}</h2>
            <p class="mb-5">IDR {{ number_format($selectedParfume['price'], 0) }}</p>

            <div class="mb-5">
                <p>
                    {{ $selectedParfume['description'] }}
                </p>
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
                            @markdown($feature['description'])
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const glide = initGlide('.glide', {
                type: 'carousel',
                perView: 2,
                gap: 0,
            });
        });
    </script>
@endpush


@endsection

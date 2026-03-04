@extends('layouts.app')

@section('title', 'Kizoku | Parfume Collection')

@section('content')


<!-- Hero -->
<section
    class="flex flex-col justify-center items-center bg-cover bg-no-repeat bg-center mt-20"
>

    <div class="gap-x-6 grid grid-cols-2 mx-auto px-4 py-10 max-w-6xl">

        <div>
            <img src="{{ asset($selectedParfume['image']) }}" alt="" class="w-full h-full object-cover">
        </div>
        <div>
            <img src="{{ asset('img/insight-1.png') }}" alt="" class="w-full h-full object-cover">
        </div>

    </div>

    <div class="gap-x-6 grid grid-cols-10 mx-auto px-4 py-10 max-w-6xl">
        <div class="col-span-6">
            <p class="mb-5">Eau De Parfume</p>

            <h2 class="font-bold text-3xl">{{ $selectedParfume['name'] }}</h2>
            <p class="mb-5">IDR {{ number_format($selectedParfume['price'], 0) }}</p>

            <div class="mb-5">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum laboriosam ab corrupti rem dolores ducimus ipsa nisi beatae voluptatem doloribus omnis veritatis maxime voluptates nam, similique nulla quia rerum id!</p>
            </div>

            <!-- cart button -->
            <livewire:utils.add-to-cart-button slug="{{ $selectedParfume['slug'] }}" text="{{__('ADD TO CART')}}"/>
        </div>

        <div class="col-span-4">
            <div class="flex flex-col">

                <!-- Item Menu: PERSONA -->
                <div class="product-detail-dropdown-wrapper" x-data="{show: false}">
                    <a href="#" class="group product-detail-dropdown-link"
                        @click.prevent="show = !show">
                        <span class="font-medium text-slate-700 text-sm uppercase tracking-widest">Persona</span>
                        <svg xmlns="http://www.w3.org/2000/svg" x-bind:class="show ? 'rotate-90 scale-110 -translate-x-4' : ''" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition duration-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <div class="product-detail-dropdown-item"
                        x-transition
                        x-cloak
                        x-show="show">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit hic enim sit. Dolor odit maiores placeat minus modi commodi perferendis?
                    </div>
                </div>

                <!-- Item Menu: GOOD TO KNOW -->
                <div class="product-detail-dropdown-wrapper" x-data="{show: false}">
                    <a href="#" class="group product-detail-dropdown-link"
                        @click.prevent="show = !show">
                        <span class="font-medium text-slate-700 text-sm uppercase tracking-widest">Good To Know</span>
                        <svg xmlns="http://www.w3.org/2000/svg" x-bind:class="show ? 'rotate-90 scale-110 -translate-x-4' : ''" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition duration-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <div class="product-detail-dropdown-item"
                        x-transition
                        x-cloak
                        x-show="show">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit hic enim sit. Dolor odit maiores placeat minus modi commodi perferendis?
                    </div>
                </div>

                <!-- Item Menu: NOTES & ACCORDS -->
                <div class="product-detail-dropdown-wrapper" x-data="{show: false}">
                    <a href="#" class="group product-detail-dropdown-link"
                        @click.prevent="show = !show">
                        <span class="font-medium text-slate-700 text-sm uppercase tracking-widest">Notes & Accords</span>
                        <svg xmlns="http://www.w3.org/2000/svg" x-bind:class="show ? 'rotate-90 scale-110 -translate-x-4' : ''" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition duration-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <div class="product-detail-dropdown-item"
                        x-transition
                        x-cloak
                        x-show="show">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit hic enim sit. Dolor odit maiores placeat minus modi commodi perferendis?
                    </div>
                </div>

                <!-- Item Menu: INGREDIENTS -->
                <div class="product-detail-dropdown-wrapper" x-data="{show: false}">
                    <a href="#" class="group product-detail-dropdown-link"
                        @click.prevent="show = !show">
                        <span class="font-medium text-slate-700 text-sm uppercase tracking-widest">Ingredients</span>
                        <svg xmlns="http://www.w3.org/2000/svg" x-bind:class="show ? 'rotate-90 scale-110 -translate-x-4' : ''" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition duration-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <div class="product-detail-dropdown-item"
                        x-transition
                        x-cloak
                        x-show="show">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit hic enim sit. Dolor odit maiores placeat minus modi commodi perferendis?
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>


@endsection

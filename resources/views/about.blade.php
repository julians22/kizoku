@extends('layouts.app')

@section('title', 'About Us - Kizoku')

@section('content')

<section
    class="isolate relative xl:flex xl:flex-col justify-center items-center grid grid-cols-2 grid-rows-[minmax(0,1fr)_auto_minmax(0,1fr)] bg-white bg-cover bg-no-repeat bg-center px-5 xl:px-0 py-24 xl:py-0 h-[82svh] xl:h-screen min-h-[38rem] xl:min-h-0 about-hero"
>
    <div class="about-ingredients" aria-hidden="true">
        <div class="order-2 about-ingredient">
            <picture>
                <source srcset="{{ asset('img/about/kizoku1_lg.webp') }}" media="(min-width: 1280px)">
                <img src="{{ asset('img/about/kizoku1.webp') }}" alt="" draggable="false">
            </picture>
        </div>
        <div class="order-5 about-ingredient">
            <picture>
                <source srcset="{{ asset('img/about/kizoku2_lg.webp') }}" media="(min-width: 1280px)">
                <img src="{{ asset('img/about/kizoku2.webp') }}" alt="" draggable="false">
            </picture>
        </div>
        <div class="order-1 about-ingredient">
            <picture>
                <source srcset="{{ asset('img/about/kizoku3_lg.webp') }}" media="(min-width: 1280px)">
                <img src="{{ asset('img/about/kizoku3.webp') }}" alt="" draggable="false">
            </picture>
        </div>
        <div class="order-4 about-ingredient">
            <picture>
                <source srcset="{{ asset('img/about/kizoku4_lg.webp') }}" media="(min-width: 1280px)">
                <img src="{{ asset('img/about/kizoku4.webp') }}" alt="" draggable="false">
            </picture>
        </div>
    </div>

    <div class="z-20 order-3 col-span-2 xl:max-w-2xl max-w-3xl pointer-events-none about-hero-copy xl:mix-blend-difference">
        <h2 class="font-bold text-navy-500 xl:text-white text-base xl:text-lg text-center leading-relaxed">
            PERFUME IS THE FIRST LAYER OF DRESSING, AN
            UNSEEN ENSEMBLE THAT SPEAKS BEFORE WORDS,
            REVEALING CHARACTER. PRESENCE. AND INTENT.
        </h2>
    </div>
</section>

<section
    class="flex justify-center items-center bg-cover bg-no-repeat bg-center px-6 xl:px-0 py-20 xl:py-0 min-h-[26rem] xl:aspect-[45/32]"
>
    <div class="mx-auto max-w-3xl xl:max-w-6xl">
        <h2 class="font-bold text-lg sm:text-xl xl:text-xl text-center uppercase leading-relaxed">
            Rooted in this belief, we created KIZOKU to offer a new expression of fragrance
            inspired by modern Japanese culture. Each scent is meticulously crafted for the
            modern gentleman, using high-quality ingredients and refined compositions.
        </h2>
    </div>
</section>

<div>
    <img src="{{ asset('img/about/About_foot_2.webp') }}" class="block w-full h-auto" alt="">
</div>


@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hero = document.querySelector('.about-hero');
            const ingredients = hero.querySelectorAll('.about-ingredient');
            const desktopMedia = window.matchMedia('(min-width: 1280px)');
            const hoverMedia = window.matchMedia('(hover: hover)');

            const positionDesktopIngredients = () => {
                if (!desktopMedia.matches) {
                    ingredients.forEach(element => {
                        element.style.left = '';
                        element.style.top = '';
                    });
                    return;
                }

                ingredients.forEach(element => {
                    const randomPosition = generateRandomPosition(element, hero);
                    element.style.left = `${randomPosition.x}px`;
                    element.style.top = `${randomPosition.y}px`;
                });
            };

            const keepIngredientsInBounds = () => {
                if (!desktopMedia.matches) {
                    return;
                }

                ingredients.forEach(element => {
                    if (element.style.left === '' || element.style.top === '') {
                        return;
                    }

                    const maxX = Math.max(0, hero.clientWidth - element.offsetWidth);
                    const maxY = Math.max(0, hero.clientHeight - element.offsetHeight);
                    element.style.left = `${Math.min(parseFloat(element.style.left), maxX)}px`;
                    element.style.top = `${Math.min(parseFloat(element.style.top), maxY)}px`;
                });
            };

            ingredients.forEach(element => {
                element.addEventListener('pointerenter', (event) => {
                    if (!desktopMedia.matches || !hoverMedia.matches || event.pointerType === 'touch') {
                        return;
                    }

                    const randomPosition = generateRandomPosition(element, hero);
                    element.style.left = `${randomPosition.x}px`;
                    element.style.top = `${randomPosition.y}px`;
                });

                element.querySelector('img').addEventListener('load', keepIngredientsInBounds);
            });

            positionDesktopIngredients();
            desktopMedia.addEventListener('change', positionDesktopIngredients);
            window.addEventListener('resize', keepIngredientsInBounds);
        });

        function generateRandomPosition(element, container) {
            const containerRect = container.getBoundingClientRect();
            const elementRect = element.getBoundingClientRect();

            const maxX = Math.max(0, containerRect.width - elementRect.width);
            const maxY = Math.max(0, containerRect.height - elementRect.height);

            const randomX = Math.random() * maxX;
            const randomY = Math.random() * maxY;

            return { x: randomX, y: randomY };
        }
    </script>
@endpush

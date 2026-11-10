@extends('layouts.app')

@section('title', 'About Us - Kizoku')

@section('content')

<section
    class="isolate relative flex flex-col justify-center items-center bg-white bg-cover bg-no-repeat bg-center px-5 xl:px-0 py-24 xl:py-0 h-[82svh] xl:h-screen min-h-[38rem] xl:min-h-0 about-hero"
>
    <div class="about-ingredients" aria-hidden="true">
        <div class="drag-element">
            <img src="{{ asset('img/about/ingredient_1.webp') }}" alt="">
        </div>
        <div class="drag-element">
            <img src="{{ asset('img/about/ingredient_2.webp') }}" alt="">
        </div>
        <div class="drag-element">
            <img src="{{ asset('img/about/ingredient_3.webp') }}" alt="">
        </div>
        <div class="drag-element">
            <img src="{{ asset('img/about/ingredient_4.webp') }}" alt="">
        </div>
    </div>

    <div class="z-20 xl:max-w-xl max-w-3xl pointer-events-none about-hero-copy xl:mix-blend-difference">
        <h2 class="font-bold text-black xl:text-white text-base sm:text-lg xl:text-xl text-center leading-relaxed">
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
    <img src="{{ asset('img/about/About_foot.webp') }}" class="block w-full h-auto" alt="">
</div>


@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hero = document.querySelector('.about-hero');
            const dragElements = hero.querySelectorAll('.drag-element');
            const desktopMedia = window.matchMedia('(min-width: 1280px)');
            let activeElement = null;
            let offsetX = 0;
            let offsetY = 0;

            const positionDesktopIngredients = () => {
                if (!desktopMedia.matches) {
                    if (activeElement) {
                        activeElement.classList.remove('active');
                        activeElement = null;
                    }

                    dragElements.forEach(element => {
                        element.style.left = '';
                        element.style.top = '';
                    });
                    return;
                }

                dragElements.forEach(element => {
                    const randomPosition = generateRandomPosition(element, hero);
                    element.style.left = `${randomPosition.x}px`;
                    element.style.top = `${randomPosition.y}px`;
                });
            };

            dragElements.forEach(element => {
                element.addEventListener('mousedown', (event) => {
                    if (!desktopMedia.matches) {
                        return;
                    }

                    event.preventDefault();
                    element.classList.add('active');
                    activeElement = element;
                    offsetX = event.clientX - element.getBoundingClientRect().left;
                    offsetY = event.clientY - element.getBoundingClientRect().top;
                });
            });

            document.addEventListener('mousemove', (e) => {
                if (activeElement) {
                    activeElement.style.left = `${e.clientX - offsetX}px`;
                    activeElement.style.top = `${e.clientY - offsetY}px`;
                }
            });

            document.addEventListener('mouseup', () => {
                if (activeElement) {
                    activeElement.classList.remove('active');
                }
                activeElement = null;
            });

            positionDesktopIngredients();
            desktopMedia.addEventListener('change', positionDesktopIngredients);
        });

        function generateRandomPosition(element, container) {
            const containerRect = container.getBoundingClientRect();
            const elementRect = element.getBoundingClientRect();

            const maxX = containerRect.width - elementRect.width;
            const maxY = containerRect.height - elementRect.height;

            const randomX = Math.random() * maxX;
            const randomY = Math.random() * maxY;

            return { x: randomX, y: randomY };
        }
    </script>
@endpush

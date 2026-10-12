@extends('layouts.app')

@section('title', 'About Us - Kizoku')

@section('content')

<!-- Section 1 -->
<section
    class="isolate relative flex justify-center items-center bg-white bg-cover bg-no-repeat bg-center h-screen"
>

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


    <div class="z-20 max-w-xl pointer-events-none mix-blend-difference">
        <h2 class="font-bold text-white text-xl text-center">PERFUME IS THE FIRST LAYER OF DRESSING, AN
            UNSEEN ENSEMBLE THAT SPEAKS BEFORE WORDS,
            REVEALING CHARACTER. PRESENCE. AND INTENT.</h2>
    </div>



</section>

<!-- Section 2 -->
<section
    class="flex justify-center items-center bg-cover bg-no-repeat bg-center aspect-[45/32]"
>

    <div class="mx-auto max-w-6xl">

        <h2 class="font-bold text-xl text-center uppercase">
            Rooted in this belief, we created KIZUKO to offer a new expression of fragrance
            inspired by modern Japanese culture. Each scent is meticulously crafted for the
            modern gentleman, using high-quality ingredients and refined compositions.
        </h2>

    </div>


</section>


<div>
    <img src="{{ asset('img/about/About_foot.webp') }}" class="w-full h-auto" alt="">
</div>



@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dragElements = document.querySelectorAll('.drag-element');
            let activeElement = null;
            let offsetX = 0;
            let offsetY = 0;

            dragElements.forEach(element => {

                const randomPosition = generateRandomPosition(element);
                element.style.left = `${randomPosition.x}px`;
                element.style.top = `${randomPosition.y}px`;


                element.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    element.classList.add('active'); // Add active class for styling

                    activeElement = element;
                    offsetX = e.clientX - element.getBoundingClientRect().left;
                    offsetY = e.clientY - element.getBoundingClientRect().top;
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
                    activeElement.classList.remove('active'); // Remove active class when dragging stops
                }
                activeElement = null;
            });
        });


        function generateRandomPosition(element) {
            const container = document.querySelector('section');
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

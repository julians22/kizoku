@extends('layouts.app')

@section('title', 'About Us - Kizoku')

@section('content')

<!-- Section 1 -->
<section
    class="relative flex justify-center items-center bg-gray-white bg-cover bg-no-repeat bg-center h-screen"
>

    <div class="drag-element">
        <img src="{{ asset('img/d-1.png') }}" alt="">
    </div>
    <div class="drag-element">
        <img src="{{ asset('img/d-2.png') }}" alt="">
    </div>
    <div class="drag-element">
        <img src="{{ asset('img/d-3.png') }}" alt="">
    </div>
    <div class="drag-element">
        <img src="{{ asset('img/d-4.png') }}" alt="">
    </div>


    <div class="max-w-md">
        <h2 class="text-center">PERFUME IS THE FIRST LAYER OF DRESSING, AN
            UNSEEN ENSEMBLE THAT SPEAKS BEFORE WORDS,
            REVEALING CHARACTER. PRESENCE. AND INTENT.</h2>
    </div>



</section>

<!-- Section 2 -->
<section
    class="flex justify-center items-center bg-cover bg-no-repeat bg-center aspect-[45/32]"
    style="background-image: url({{ asset('img/S-5.png') }});"
>

    <div class="max-w-md">

        <h2 class="text-center">
            ROOTED IN THIS BELICF. WE CREATED KIZUKO TO OFFER
            A NEW EXPRESSION OF FRAGRANCE INSPIRED BY MODERN JAPANESE
            CULTURE. EACH SCENT IS METICULOUSLY CRAFTED FOR THE MODERN
            GENTLEMAN, USING HIGH QUALITY INGREDIENTS AND REFINED COMPOSITIONS.
        </h2>

    </div>

</section>



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
